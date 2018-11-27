<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class HandleRefundRequest extends Request
{

    public function rules()
    {
        return [
            // agree 为必填, 并且是布尔值
            'agree'  => ['required', 'boolean'],
            // reason 为 agree==false 时必填
            'reason' => ['required_if:agree,false'], // 拒绝退款时需要输入拒绝理由
        ];
    }
}
