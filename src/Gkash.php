<?php

namespace Laraditz\Gkash;

use Illuminate\Support\Str;
use Laraditz\Gkash\Enums\PaymentStatus;
use Laraditz\Gkash\Models\GkashPayment;
use LogicException;

class Gkash
{
    private $merchantID;

    private $signatureKey;

    private $sandboxMode = true;

    private $baseUrl;

    private $amount;

    private $returnUrl;

    private $refNo;

    public function __construct($merchantID = null, $signatureKey = null)
    {
        $this->setMerchantID($merchantID ?? config('gkash.merchant_id'));
        $this->setSignatureKey($signatureKey ?? config('gkash.signature_key'));
        $this->setSandboxMode(config('gkash.sandbox.mode'));
        $this->setCurrencyCode(config('gkash.currency_code'));
        $this->setBaseUrl();
    }

    public function createPayment(array $metadata = [])
    {
        throw_if(!$this->getMerchantID(), LogicException::class, 'Merchant ID not set.');
        throw_if(!$this->getSignatureKey(), LogicException::class, 'Signature Key not set.');
        throw_if(!$this->getRefNo(), LogicException::class, 'Ref No not set.');

        $metadata = array_merge(
            [
                'merchantID' => $this->getMerchantID(),
            ],
            $metadata
        );

        throw_if(!$this->getAmount(), LogicException::class, 'Please set an amount.');
        throw_if(!$this->getReturnUrl(), LogicException::class, 'Please set a return URL.');

        $payment = GkashPayment::create([
            'merchant_id' => $this->getMerchantID(),
            'ref_no' => $this->getRefNo(),
            'currency_code' => $this->getCurrencyCode(),
            'amount' => $this->getAmount(),
            'return_url' => $this->getReturnUrl(),
            'callback_url' => route('gkash.backend'),
            'status' => PaymentStatus::Created,
        ]);

        throw_if(!$payment, LogicException::class, 'Cant create request in database table.');

        return [
            'code' => $payment->code,
            'currency_code' => $payment->currency_code,
            'amount' => $payment->amount,
            'payment_url' => route('gkash.pay', ['payment' => $payment->code]),
        ];
    }

    public function amount($amount)
    {
        $this->setAmount($amount);

        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function returnUrl($returnUrl)
    {
        $this->setReturnUrl($returnUrl);

        return $this;
    }

    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    public function refNo($refNo)
    {
        $this->setRefNo($refNo);

        return $this;
    }

    public function setRefNo($refNo)
    {
        $this->refNo = $refNo;
    }

    public function getRefNo()
    {
        return $this->refNo;
    }


    public function setMerchantID($merchantID)
    {
        $this->merchantID = $merchantID;
    }

    public function getMerchantID()
    {
        return $this->merchantID;
    }

    public function setSignatureKey($signatureKey)
    {
        $this->signatureKey = $signatureKey;
    }

    public function getSignatureKey()
    {
        return $this->signatureKey;
    }

    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function getUrl($route)
    {
        $route = config('gkash.routes.' . $route);
        return $this->getBaseUrl() . '/' . $route;
    }

    public function setSandboxMode($sandboxMode)
    {
        $this->sandboxMode = $sandboxMode;
    }

    public function getSandboxMode()
    {
        return $this->sandboxMode;
    }

    public function setBaseUrl()
    {
        if ($this->getSandboxMode() === true) {
            $this->baseUrl = config('gkash.sandbox.base_url');
        } else {
            $this->baseUrl = config('gkash.base_url');
        }
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function generateSignature(GkashPayment $payment)
    {
        $amount = Str::of($payment->amount)->remove('.')->remove(',');
        $string = $this->getSignatureKey() . ';' . $payment->merchant_id . ';' . $payment->code . ';' . $amount . ';' . $payment->currency_code;

        return hash('sha512', strtoupper($string));
    }

    public function generatResponseeSignature(GkashPayment $payment, string $poid, string $status)
    {
        $amount = Str::of($payment->amount)->remove('.')->remove(',');
        $string = $this->getSignatureKey() . ';' . $payment->merchant_id . ';' . $poid . ';' . $payment->code . ';' . $amount . ';' . $payment->currency_code . ';' . $status;

        return hash('sha512', strtoupper($string));
    }

    public function paymentStatus(): array
    {
        return [
            88 => 'Transferred',
            66 => 'Failed',
            11 => 'Pending',
        ];
    }

    public function getPaymentStatus($status)
    {
        switch ($status) {
            default:
                return PaymentStatus::None;
            case 11:
                return PaymentStatus::Pending;
                break;
            case 66:
                return PaymentStatus::Failed;
                break;
            case 88:
                return PaymentStatus::Success;
                break;
        }
    }
}
