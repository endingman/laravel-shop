<?php

namespace App\Listeners;

use App\Events\OrderReviewed;
use App\Models\OrderItem;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductRating implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  OrderReviewed  $event
     * @return void
     */
    public function handle(OrderReviewed $event)
    {
        $items = $event->getOrder()->items()->with(['product'])->get();
        foreach ($items as $item) {
            $result = OrderItem::query()
                ->where('product_id', $item->product_id)
                ->whereHas('order', function ($query) {
                    $query->whereNotNull('paid_at');
                })
                ->first([

                    // 点看一下 first() 方法，first() 方法接受一个数组作为参数，代表此次 SQL 要查询出来的字段，默认情况下 Laravel 会给数组里面的值的两边加上 ` 这个符号，比如 first(['name', 'email']) 生成的 SQL 会类似：
                    // select `name`, `email` from xxx

                    DB::raw('count(*) as review_count'),
                    DB::raw('avg(rating) as rating'),
                ]);
            // 更新商品的评分和评价数
            $item->product->update([
                'rating'       => $result->rating,
                'review_count' => $result->review_count,
            ]);
        }
    }
}
