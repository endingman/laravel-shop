<?php
namespace App\SearchBuilders;

use App\Models\Category;

class ProductSearchBuilder
{
    protected $params = [
        'index' => 'products', //elasticsearch index(索引) =>mysql database(数据库)
        'type'  => '_doc', //elasticsearch type(类型) =>mysql table(表)
        'body'  => [
            'query' => [
                'bool' => [
                    'filter' => [],
                    'must'   => [], //elasticsearch must =>mysql and
                ],
            ],
        ],
    ];

    // 添加分页查询
    public function paginate($size, $page)
    {
        $this->params['body']['from'] = ($page - 1) * $size; //elasticsearch from =>mysql offset
        $this->params['body']['size'] = $size; //elasticsearch size =>mysql limit

        return $this;
    }

    // 筛选上架状态的商品
    public function onSale()
    {
        $this->params['body']['query']['bool']['filter'][] = ['term' => ['on_sale' => true]]; //elasticsearch term =>mysql xxx='xxx',['term' => ['on_sale' => true]]=>mysql on_sale = true

        return $this;
    }

    // 按类目筛选商品
    public function category(Category $category)
    {
        if ($category->is_directory) {
            $this->params['body']['query']['bool']['filter'][] = [
                'prefix' => ['category_path' => $category->path . $category->id . '-'], //elasticsearch prefix =>mysql like '${path}%'
            ];
        } else {
            $this->params['body']['query']['bool']['filter'][] = ['term' => ['category_id' => $category->id]];
        }
    }

    // 添加搜索词
    public function keywords($keywords)
    {
        // 如果参数不是数组则转为数组
        $keywords = is_array($keywords) ? $keywords : [$keywords];
        foreach ($keywords as $keyword) {
            $this->params['body']['query']['bool']['must'][] = [
                'multi_match' => [ //多条件搜索
                    'query'  => $keyword,
                    'fields' => [ //字段 or
                        'title^3', //^数字，权重，数字越大权重越高
                        'long_title^2',
                        'category^2',
                        'description',
                        'skus_title',
                        'skus_description',
                        'properties_value',
                    ],
                ],
            ];
        }

        return $this;
    }

    // 分面搜索的聚合
    /**
    要实现分面搜索并不是一个简单的事情，我们将一步一步往目标靠近，首先我们试着把搜索结果中所有的商品属性名取出来（即properties.name），比如上图中的『频率』、『单套容量』，这就需要用到 Elasticsearch 的聚合。
    Elasticsearch 中的聚合与 SQL 语句的 group by 有些类似，但更加灵活和强大
     */
    public function aggregateProperties()
    {
        $this->params['body']['aggs'] = [ //aggs 增加聚合
            // properties 与字段无关设定一个字符串名搜索
            'properties' => [
                // 搜索的字段properties 是nested
                'nested' => [
                    'path' => 'properties',
                ],
                'aggs'   => [
                    'properties' => [
                        'terms' => [
                            'field' => 'properties.name',
                        ],
                        'aggs'  => [
                            'value' => [
                                'terms' => [
                                    'field' => 'properties.value',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $this;
    }

    // 添加一个按商品属性筛选的条件
    public function propertyFilter($name, $value)
    {
        $this->params['body']['query']['bool']['filter'][] = [
            'nested' => [
                'path'  => 'properties',
                'query' => [
                    ['term' => ['properties.search_value' => $name . ':' . $value]],
                ],
            ],
        ];

        return $this;
    }

    // 添加排序
    public function orderBy($field, $direction)
    {
        if (!isset($this->params['body']['sort'])) {
            $this->params['body']['sort'] = []; //elasticsearch sort =>mysql orderby
        }
        $this->params['body']['sort'][] = [$field => $direction];

        return $this;
    }

    // 返回构造好的查询参数
    public function getParams()
    {
        return $this->params;
    }
}
