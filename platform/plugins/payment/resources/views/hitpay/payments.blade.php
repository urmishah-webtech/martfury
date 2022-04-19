<ul>
    @foreach($payments->payments as $payment)
        <li>
            @include('plugins/payment::hitpay.detail', compact('payment'))
        </li>
    @endforeach
</ul>
