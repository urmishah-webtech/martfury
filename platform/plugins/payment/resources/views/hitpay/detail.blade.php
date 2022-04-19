@if ($payment)
    @php
        $results = $payment->payments;
        $result = $results[0];
    @endphp
    <div class="alert alert-success" role="alert">
        <p class="mb-2">{{ trans('plugins/payment::payment.payment_id') }}: <strong>{{ $result->id }}</strong></p>

{{--        <p class="mb-2">--}}
{{--            {{ trans('plugins/payment::payment.details') }}:--}}
{{--            <strong>--}}
{{--                @foreach($purchaseUnits as $purchase)--}}
{{--                    {{ $purchase->amount->value }} {{ $purchase->amount->currency_code }} @if (!empty($purchase->description)) ({{ $purchase->description }}) @endif--}}
{{--                @endforeach--}}
{{--            </strong>--}}
{{--        </p>--}}

        <p class="mb-2">{{ trans('plugins/payment::payment.email') }}: {{ $result->buyer_email }}</p>
        <p class="mb-2">{{ trans('plugins/payment::payment.payment_type') }}: {{ $result->payment_type }}</p>
        <p class="mb-2">{{ trans('plugins/payment::payment.payment_fees') }}: {{ $result->fees }}</p>
{{--        <p class="mb-0">--}}
{{--            {{ trans('plugins/payment::payment.shipping_address') }}:--}}
{{--            {{ $shipping->name->full_name }}, {{ $shipping->address->address_line_1 }}, {{ $shipping->address->admin_area_2 }}, {{ $shipping->address->admin_area_1 }} {{ $shipping->address->postal_code }}, {{ $shipping->address->country_code }}--}}
        </p>
    </div>
{{--    @include('plugins/payment::partials.view-payment-source')--}}
@endif
