<?php

namespace Laraditz\Gkash\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laraditz\Gkash\Enums\PaymentStatus;
use Laraditz\Gkash\Events\BackendReceived;
use Laraditz\Gkash\Models\GkashMessage;
use Laraditz\Gkash\Models\GkashPayment;
use LogicException;

class GkashController extends Controller
{
    public function pay(GkashPayment $payment)
    {
        throw_if(!$payment->can_pay, LogicException::class, 'Invalid Payment URL');

        $formAction = app('gkash')->getUrl('payment');
        $formInputs = [
            'version' => config('gkash.api_version'),
            'CID' => app('gkash')->getMerchantID(),
            'v_cartid' => $payment->code,
            'v_currency' => $payment->currency_code,
            'v_amount' => $payment->amount,
            'signature' => app('gkash')->generateSignature($payment),
            'returnurl' => route('gkash.complete'),
            'callbackurl' => $payment->callback_url,
        ];

        if (config('gkash.log_request') === true) {
            logger()->info('Gkash: Payment Request', $formInputs);
        }

        return view('gkash::gkash.pay', compact('payment', 'formAction', 'formInputs'));
    }

    public function complete(Request $request)
    {
        $payment = GkashPayment::where('code', $request->cartid)->firstOrFail();
        $data = $request->all();
        $status_code = trim(Str::before(data_get($data, 'status'), '-'));
        $status_text = trim(Str::after(data_get($data, 'status'), '-'));
        $description = data_get($data, 'description');

        return view('gkash::gkash.complete', compact('payment', 'data', 'status_code', 'status_text', 'description'));
    }

    public function backend(Request $request)
    {
        echo 'OK';

        if (config('gkash.log_request') === true) {
            logger()->info('Gkash Backend : Received', $request->all());
        }

        $merchant_id = $request->CID;
        $poid = $request->POID;
        $code = $request->cartid;
        $status = $request->status;
        $signature = $request->signature;


        if ($code) {
            $data = $request->all();
            $dataCollection = $request->collect();

            GkashMessage::create([
                'action' => Str::after(__METHOD__, '::'),
                'response' => $data
            ]);

            $payment = GkashPayment::where('code', $code)->first();

            if ($payment) {

                $matchSignature = app('gkash')->generatResponseeSignature($payment, $poid, $status);

                if ($signature !== $matchSignature) {
                    logger()->info('Gkash Backend : Signature not match', $data);
                    exit;
                }

                $status_code = trim(Str::before($request->status, '-'));
                $status_text = trim(Str::after($request->status, '-'));

                $storeStatus = app('gkash')->getPaymentStatus($status_code);

                $payment->status = $storeStatus;
                if ($request->has('POID')) {
                    $payment->vendor_ref_no = $poid;
                }

                $payment->save();
                $payment->refresh();

                $dataCollection = $dataCollection->reject(function ($value, $key) {
                    return in_array($key, ['CID', 'POID', 'cartid']) ? true : false;
                })->mapWithKeys(function ($item, $key) {
                    return [Str::snake($key) => $item];
                })->merge([
                    'id' => $payment->id,
                    'merchant_id' => $merchant_id,
                    'code' => $payment->code,
                    'ref_no' => $payment->ref_no,
                    'vendor_ref_no' => $payment->vendor_ref_no,
                    'status' => $payment->status,
                    'status_text' => PaymentStatus::getDescription($payment->status),
                ])->sortKeys();

                event(new BackendReceived($dataCollection->toArray()));
            } else {
                logger()->info('Gkash Backend : Payment record not found', $data);
                exit;
            }
        } else {
            // no payload received
            logger()->error('Gkash Backend : No payload');
        }
    }
}
