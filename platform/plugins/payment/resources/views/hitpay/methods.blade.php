@if (setting('payment_hitpay_status') == 1)
    <li class="list-group-item">
        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_hitpay"
               @if (setting('default_payment_method') == \Botble\Payment\Enums\PaymentMethodEnum::HITPAY) checked @endif
               value="hitpay" data-bs-toggle="collapse" data-bs-target=".payment_hitpay_wrap" data-parent=".list_payment_method">
        <label for="payment_hitpay" class="text-start">{{ setting('payment_hitpay_name', trans('plugins/payment::payment.payment_via_hitpay')) }}</label>
        <div class="payment_hitpay_wrap payment_collapse_wrap collapse @if (setting('default_payment_method') == \Botble\Payment\Enums\PaymentMethodEnum::HITPAY) show @endif" style="padding: 15px 0;">
            {!! clean(setting('payment_hitpay_description')) !!}

            @php $supportedCurrencies = (new \Botble\Payment\Services\Gateways\HitPayPaymentService)->supportedCurrencyCodes(); @endphp
            @if (function_exists('get_application_currency') && !in_array(get_application_currency()->title, $supportedCurrencies))
                <div class="alert alert-warning" style="margin-top: 15px;">
                    {{ __(":name doesn't support :currency. List of currencies supported by :name: :currencies.", ['name' => 'HitPay', 'currency' => get_application_currency()->title, 'currencies' => implode(', ', $supportedCurrencies)]) }}

                    <div style="margin-top: 10px;">
                        {{ __('Learn more') }}: <a href="https://hit-pay.com/" target="_blank" rel="nofollow">https://hit-pay.com/</a>
                    </div>

                    @php
                        $currencies = get_all_currencies();

                        $currencies = $currencies->filter(function ($item) use ($supportedCurrencies) { return in_array($item->title, $supportedCurrencies); });
                    @endphp
                    @if (count($currencies))
                        <div style="margin-top: 10px;">{{ __('Please switch currency to any supported currency') }}:&nbsp;&nbsp;
                            @foreach ($currencies as $currency)
                                <a href="{{ route('public.change-currency', $currency->title) }}" @if (get_application_currency_id() == $currency->id) class="active" @endif><span>{{ $currency->title }}</span></a>
                                @if (!$loop->last)
                                    &nbsp; | &nbsp;
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </li>
@endif
