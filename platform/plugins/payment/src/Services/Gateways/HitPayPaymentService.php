<?php

namespace Botble\Payment\Services\Gateways;

use Botble\Ecommerce\Repositories\Interfaces\ShipmentInterface;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Payment\Services\Abstracts\HitPayPaymentAbstract;
use Botble\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\StoreLocatorInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShipmentHistoryInterface;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;
use Botble\Ecommerce\Supports\OrderHelper;

class HitPayPaymentService extends HitPayPaymentAbstract
{
    /**
     * Make a payment
     *
     * @param Request $request
     *
     * @return mixed
     * @throws Exception
     */
    public function makePayment(Request $request)
    {
        $amount = round((float)$request->input('amount'), $this->isSupportedDecimals() ? 2 : 0);

        $data = [
            'name' => $request->input('name'),
            'quantity' => 1,
            'price' => $amount,
            'sku' => null,
            'type' => PaymentMethodEnum::HITPAY,
        ];

        $currency = $request->input('currency', config('plugins.payment.payment.currency'));
        $currency = strtoupper($currency);

        $queryParams = [
            'type' => PaymentMethodEnum::HITPAY,
            'amount' => $amount,
            'currency' => $currency,
            'order_id' => $request->input('order_id'),
        ];

        $checkoutUrl = $this
            ->setReturnUrl($request->input('callback_url1') . '?' . http_build_query($queryParams))
            ->setAmount($amount)
            ->setCurrency($currency)
//            ->setCustomer($request->input('address.email'))
            ->createPayment();

        return $checkoutUrl;
    }

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param Request $request
     * @return mixed
     */
    public function afterMakePayment(Request $request)
    {
        $status = PaymentStatusEnum::COMPLETED;
        $chargeId = session('hitpay_payment_id');

        $orderIds = (array)$request->input('order_id', []);

        do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency'),
            'charge_id' => $chargeId,
            'order_id' => $orderIds,
            'customer_id' => $request->input('customer_id'),
            'customer_type' => $request->input('customer_type'),
            'payment_channel' => PaymentMethodEnum::HITPAY,
            'status' => $status
        ]);
        $id = $orderIds[0];
        $order = app(OrderInterface::class)->findOrFail($id);
        OrderHelper::confirmPayment($order, true);
        session()->forget('hitpay_payment_id');
        $weight = 0;
        foreach ($order->products as $product) {
            if ($product && $product->weight) {
                $weight += $product->weight;
            }
        }
        $weight = $weight > 0.1 ? $weight : 0.1;

        $shipment = [
            'order_id' => $id,
            'user_id' => Auth::id(),
            'weight' => $weight,
            'note' => '',
            'cod_amount' => 0,
            'type' => $order->shipping_method,
            'status' => ShippingStatusEnum::DELIVERING,
            'price' => $order->shipping_amount,
        ];


        $defaultStore = app(StoreLocatorInterface::class)->getFirstBy(['is_primary' => true]);
        $shipment['store_id'] = ($defaultStore ? $defaultStore->id : null);
//            $result =
//        switch ($order->shipping_method) {
//            default:
//                $result = $result->setMessage(trans('plugins/ecommerce::order.order_was_sent_to_shipping_team'));
//                break;
//        }

       app(OrderInterface::class)->createOrUpdate([
            'status' => OrderStatusEnum::DELIVERING
        ], compact('id'));

        $shipment = app(ShipmentInterface::class)->createOrUpdate($shipment);

        app(OrderHistoryInterface::class)->createOrUpdate([
            'action' => 'create_shipment',
            'description' => trans('plugins/ecommerce::order.order_was_sent_to_shipping_team') . ' ' . trans('plugins/ecommerce::order.by_username'),
            'order_id' => $id,
            'user_id' => Auth::id(),
        ]);

        app(ShipmentHistoryInterface::class)->createOrUpdate([
            'action' => 'create_from_order',
            'description' => trans('plugins/ecommerce::order.shipping_was_created_from'),
            'shipment_id' => $shipment->id,
            'order_id' => $id,
            'user_id' => Auth::id(),
        ]);
//
        return $chargeId;
    }


}
