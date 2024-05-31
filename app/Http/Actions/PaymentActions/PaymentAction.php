<?php

namespace App\Http\Actions\PaymentActions;

use YooKassa\Client;

class PaymentAction
{
    public Client $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuth(
            config('services.yookassa.shop_id', 356895),
            config('services.yookassa.secret_id', 'test_14bOwg2TSk_dHoYAUe1_eu0ve_GXYNsakzHD8x1uQaE')
        );
    }

    public function createPayment(int $amount, array $options = []): string
    {
        $payment = $this->client->createPayment(
            array(
                'amount' => array(
                    'value' => $amount,
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'http://bikini-star-test.ru:81/',
                ),
                'capture' => false,
                'description' => 'Покупка услуги на сайте bikini-star',
                'metadata' => [
                    'transaction_id' => $options['transaction_id'],
                ]
            ),
            uniqid('', true)
        );

        return $payment->getConfirmation()->getConfirmationUrl();
    }

    public function create()
    {
        $transaction = Statistic::create([

        ]);
        if ($transaction) {
            return response()->json(
                [
                    'status' => 'inject',
                    'link' => $this->paymentHelper->createPayment(
                        $transaction->type,
                        ['transaction_id' => $transaction->id]
                    )
                ]
            );
        } else {
            return response()->json(['status' => 'reject', 'message' => 'didnt create transaction']);
        }
    }
}
