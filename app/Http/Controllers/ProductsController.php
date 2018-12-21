<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsController extends Controller
{
    protected $page    = 1;
    protected $perPage = 16;

    public function index(Request $request)
    {
        $page    = $request->page ?? $this->page;
        $perPage = $request->perPage ?? $this->perPage;

        // 构建查询
        $params = [
            'index' => 'products', //ElasticSearch index(索引) => mysql database（数据库）
            'type'  => '_doc', //ElasticSearch类型（Type） =>mysql  表（Table）
            'body'  => [
                // 通过当前页数与每页数量计算偏移值
                'from'  => ($page - 1) * $perPage, //ElasticSearch from => mysql offset
                'size'  => $perPage, //ElasticSearch size => mysql limit
                'query' => [
                    'bool' => [
                        'filter' => [
                            ['term' => ['on_sale' => true]],
                            /**
                             * ['term' => ['on_sale' => true]]，这个数组的 Key 是 term 代表这是一个『词项查询』。
                            『词项查询』通常用于搜索一个精确的值，Elasticsearch会拿搜索值在文档对应字段经过分词的结果里精确匹配。我们之前在定义索引数据结构时，on_sale 是一个 Bool 类型，其分词结果就是本身，所以上面这个条件就是查出所有 on_sale 字段是 true 的文档
                             */
                        ],
                    ],
                ],
            ],
        ];

        // order 参数用来控制商品的排序规则
        if ($order = $request->input('order', '')) {
            // 是否是以 _asc 或者 _desc 结尾
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    // 根据传入的排序值来构造排序参数
                    $params['body']['sort'] = [[$m[1] => $m[2]]]; //ElasticSearch-php sort => mysql orderby
                }
            }
        }

        if ($request->input('category_id') && $category = Category::find($request->input('category_id'))) {
            if ($category->is_directory) {
                // 如果是一个父类目，则使用 category_path 来筛选
                $params['body']['query']['bool']['filter'][] = [
                    'prefix' => ['category_path' => $category->path . $category->id . '-'],
                    // ElasticSearch-php prefix =>mysql like '${path}%'
                ];
            } else {
                $params['body']['query']['bool']['filter'][] = [
                    'term' => ['category_id' => $category->id],
                ];
            }
        }

        if ($search = $request->input('search', '')) {
            $params['body']['query']['bool']['must'] = [ //ElasticSearch-php must => mysql and
                [
                    'multi_match' => [ //多条件搜索
                        'query'  => $search,
                        'fields' => [ //字段 => mysql or...
                            'title^3', //^数字 =>权重越大越高
                            'long_title^2',
                            'category^2', // 类目名称
                            'description',
                            'skus_title',
                            'skus_description',
                            'properties_value',
                        ],
                    ],
                ],
            ];
        }

        /**
         *  要实现分面搜索并不是一个简单的事情，我们将一步一步往目标靠近，首先我们试着把搜索结果中所有的商品属性名取出来（即properties.name），比如上图中的『频率』、『单套容量』，这就需要用到 Elasticsearch 的聚合。
        Elasticsearch 中的聚合与 SQL 语句的 group by 有些类似，但更加灵活和强大
         */
        if ($search || isset($category)) {
            $params['body']['aggs'] = [
                // 这里的 properties 是我们给这个聚合操作的命名
                // 可以是其他字符串，与商品结构里的 properties 没有必然联系
                'properties' => [
                    // 由于我们要聚合的属性是在 nested 类型字段下的属性，需要在外面套一层 nested 聚合查询
                    'nested' => [
                        // 代表我们要查询的 nested 字段名为 properties
                        'path' => 'properties',
                    ],
                    // 在 nested 聚合下嵌套聚合
                    'aggs'   => [
                        // 聚合的名称
                        'properties' => [
                            // terms 聚合，用于聚合相同的值
                            'terms' => [
                                // 我们要聚合的字段名
                                'field' => 'properties.name',
                            ],
                            // value层
                            'aggs'  => [
                                'value' => [
                                    // // terms 聚合，用于聚合相同的值
                                    'terms' => [
                                        // 我们要聚合的字段名
                                        'field' => 'properties.value',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        }
        //=>结构返回的数据properties.name在$result['aggregations']['properties']['properties']['buckets']中properties.value在上层下$bucket['value']['buckets']中，这个看聚合的结构而定

        // filter
        // 从用户请求参数获取 filters
        $propertyFilters = [];
        if ($filterString = $request->input('filters')) {
            // 将获取到的字符串用符号 | 拆分成数组
            $filterArray = explode('|', $filterString);
            foreach ($filterArray as $filter) {
                // 将字符串用符号 : 拆分成两部分并且分别赋值给 $name 和 $value 两个变量
                list($name, $value) = explode(':', $filter);

                // 将用户筛选的属性添加到数组中
                $propertyFilters[$name] = $value;

                // 添加到 filter 类型中
                $params['body']['query']['bool']['filter'][] = [
                    // 由于我们要筛选的是 nested 类型下的属性，因此需要用 nested 查询
                    'nested' => [
                        // 指明 nested 字段
                        'path'  => 'properties',
                        'query' => [
                            ['term' => ['properties.name' => $name]],
                            ['term' => ['properties.value' => $value]],
                        ],
                    ],
                ];
            }
        }

        $result = app('es')->search($params); //ElasticSearch-php search($params) 搜索

        $properties = [];

        // 如果返回结果里有 aggregations 字段，说明做了分面搜索
        if (isset($result['aggregations'])) {
            // 使用 collect 函数将返回值转为集合
            $properties = collect($result['aggregations']['properties']['properties']['buckets'])
                ->map(function ($bucket) {
                    // 通过 map 方法取出我们需要的字段
                    return [
                        'key'    => $bucket['key'],
                        'values' => collect($bucket['value']['buckets'])->pluck('key')->all(),
                    ];
                })
                ->filter(function ($property) use ($propertyFilters) {
                    // 过滤掉只剩下一个值 或者 已经在筛选条件里的属性
                    return count($property['values']) > 1 && !isset($propertyFilters[$property['key']]);
                });
        }

        // 通过 collect 函数将返回结果转为集合，并通过集合的 pluck 方法取到返回的商品 ID 数组
        $productIds = collect($result['hits']['hits'])->pluck('_id')->all(); //ElasticSearch-php 搜索出来的数据结果在$result['hits']['hits']，$result是你定义的结果
        // 通过 whereIn 方法从数据库中读取商品数据
        $products = Product::query()
            ->whereIn('id', $productIds)
        // orderByRaw 可以让我们用原生的 SQL 来给查询结果排序
        // FIND_IN_SET(str,strlist) str在 strlist 位置，即顺序排序
            ->orderByRaw(sprintf("FIND_IN_SET(id, '%s')", join(',', $productIds)))
        // sprintf() 函数把格式化的字符串写入变量中
            ->get();
        // 返回一个 LengthAwarePaginator 对象（参数items=>数据，$total =>总记录数，$perPage =>分页大小，$page =>当前页数，$path =>分页url）
        $pager = new LengthAwarePaginator($products, $result['hits']['total'], $perPage, $page, [
            'path' => route('products.index', false), // 手动构建分页的 url
        ]);

        return view('products.index', [
            'products'        => $pager,
            'filters'         => [
                'search' => $search,
                'order'  => $order,
            ],
            'category'        => $category ?? null,
            'properties'      => $properties,
            'propertyFilters' => $propertyFilters,
        ]);
    }

    public function show(Product $product, Request $request)
    {
        // 判断商品是否已经上架，如果没有上架则抛出异常。
        if (!$product->on_sale) {
            throw new InvalidRequestException('商品未上架');
        }

        $favored = false;
        // 用户未登录时返回的是 null，已登录时返回的是对应的用户对象
        if ($user = $request->user()) {
            // 从当前用户已收藏的商品中搜索 id 为当前商品 id 的商品
            // boolval() 函数用于把值转为布尔值
            $favored = boolval($user->favoriteProducts()->find($product->id));
        }

        $reviews = OrderItem::query()
            ->with(['order.user', 'productSku']) // 预先加载关联关系
            ->where('product_id', $product->id)
            ->whereNotNull('reviewed_at') // 筛选出已评价的
            ->orderBy('reviewed_at', 'desc') // 按评价时间倒序
            ->limit(10) // 取出 10 条
            ->get();

        return view('products.show', ['product' => $product, 'favored' => $favored, 'reviews' => $reviews]);
    }

    public function favor(Product $product, Request $request)
    {
        $user = $request->user();
        if ($user->favoriteProducts()->find($product->id)) {
            return [];
        }

        $user->favoriteProducts()->attach($product);

        return [];
    }

    public function disfavor(Product $product, Request $request)
    {
        $user = $request->user();
        $user->favoriteProducts()->detach($product);

        return [];
    }

    public function favorites(Request $request)
    {
        $products = $request->user()->favoriteProducts()->paginate(16);

        return view('products.favorites', ['products' => $products]);
    }
}
