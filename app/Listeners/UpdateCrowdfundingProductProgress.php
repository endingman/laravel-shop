<?php

namespace App\Listeners;

use App\Events\Orderpaid;
use App\Models\Order;

class UpdateCrowdfundingProductProgress
{
    /**
     * Handle the event.
     *
     * @param  Orderpaid  $event
     * @return void
     */
    public function handle(Orderpaid $event)
    {
        $order = $event->getOrder();

        // 如果订单类型不是众筹商品订单，无需处理
        if ($order->type !== Order::TYPE_CROWDFUNDING) {
            return;
        }

        $crowdfunding = $order->items[0]->product->crowdfunding;

        $data = Order::query()
        // 查出订单类型为众筹订单
            ->where('type', Order::TYPE_CROWDFUNDING)
        // 并且是支付的
            ->whereNotNull('paid_at')
            ->whereHas('items', function ($query) use ($crowdfunding) {
                // 并且包含了本商品
                $query->where('product_id', $crowdfunding->product_id);
            })
            ->first([
                // 取出订单总金额
                \DB::raw('sum(total_amount) as total_amount'),
                // 取出去重的支持用户数
                \DB::raw('count(distinct(user_id)) as user_count'),
            ]);

        // 重点看一下 first() 方法，first() 方法接受一个数组作为参数，代表此次 SQL 要查询出来的字段，默认情况下 Laravel 会给数组里面的值的两边加上 ` 这个符号，比如 first(['name', 'email']) 生成的 SQL 会类似：

        // select `name`, `email` from xxx

        $crowdfunding->update([
            'total_amount' => $data->total_amount,
            'user_count'   => $data->user_count,
        ]);
    }
}
