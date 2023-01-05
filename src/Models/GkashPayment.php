<?php

namespace Laraditz\Gkash\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laraditz\Gkash\Enums\PaymentStatus;

class GkashPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'merchant_id', 'code', 'ref_no', 'vendor_ref_no', 'currency_code', 'amount', 'return_url', 'callback_url',
        'status', 'status_description', 'refund_status', 'refund_amount', 'refunded_at', 'description', 'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'json',
    ];

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = $model->id ?? (string) Str::orderedUuid();
            $model->code = self::generateCode();
        });
    }

    public function getCanPayAttribute(): bool
    {
        return in_array($this->status, [PaymentStatus::Created, PaymentStatus::Pending]) ?  true : false;
    }

    private static function generateCode()
    {
        $code = self::randomAlphanumeric();

        while (self::getModel()->where('code', $code)->count()) {
            $code = self::randomAlphanumeric();
        }

        return $code;
    }

    private static function randomAlphanumeric(int $length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle($characters), 0, $length);
    }
}
