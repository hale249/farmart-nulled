<?php

namespace Botble\AffiliatePro\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AffiliateRequest extends Request
{
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:ec_customers,id',
            'affiliate_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('affiliates')->ignore($this->route('affiliate')),
            ],
            'balance' => 'nullable|numeric|min:0',
            'total_commission' => 'nullable|numeric|min:0',
            'total_withdrawn' => 'nullable|numeric|min:0',
            'status' => 'required|' . Rule::in(BaseStatusEnum::values()),
        ];
    }
}
