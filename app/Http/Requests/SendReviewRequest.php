<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class SendReviewRequest extends Request
{
    public function rules()
    {
        // 其中 $this->route('order') 可以获得当前路由对应的订单对象，Rule::exists() 判断用户提交的 ID 是否属于此订单。
        return [
            'reviews'          => ['required', 'array'],
            'reviews.*.id'     => [
                'required',
                Rule::exists('order_items', 'id')->where('order_id', $this->route('order')->id),
            ],
            'reviews.*.rating' => ['required', 'integer', 'between:1,5'],
            'reviews.*.review' => ['required'],
        ];
    }

    public function attributes()
    {
        return [
            'reviews.*.rating' => '评分',
            'reviews.*.review' => '评价',
        ];
    }
}
