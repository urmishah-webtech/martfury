<?php

require_once './vendor/autoload.php';

$apiKey = '4c79a35f901105881aeb140b2cafd5e1c34c559e0e1e44eaa97a9c8c3082039b';
$salt = 'OhHtKpOEe6Ze8q4CqmF8SvoksyyTZURxDPG9xaRxsaDf4PYKvUJHJRI5TuDmgA49';
$redirectUrl = 'http://localhost/hitpay/Examples/ClientExample.php';

$hitPayClient = new \HitPay\Client($apiKey, false);

try {
    $request = new \HitPay\Request\CreatePayment();

    $request->setAmount(66)
        ->setCurrency('SGD')
        ->setRedirectUrl($redirectUrl);
    $result = $hitPayClient->createPayment($request);
    print_r($result);

//    $data = $hitPayClient->getPaymentStatus($result->id);
//    print_r($data);

//    $data = $hitPayClient->deletePaymentRequest($data->getId());
//    print_r($data);

} catch (\Exception $e) {
    print_r($e->getMessage());
}