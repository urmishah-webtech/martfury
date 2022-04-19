<?php

namespace Botble\Payment\Http\Requests;

use Botble\Support\Http\Requests\Request;

class HitPayPaymentCallbackRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount'   => 'required',
            'currency' => 'required',
        ];
    }
}
