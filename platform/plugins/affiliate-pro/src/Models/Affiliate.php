<?php

namespace Botble\AffiliatePro\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends BaseModel
{
    protected $table = 'affiliates';

    protected $fillable = [
        'customer_id',
        'affiliate_code',
        'balance',
        'total_commission',
        'total_withdrawn',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'balance' => 'float',
        'total_commission' => 'float',
        'total_withdrawn' => 'float',
        'affiliate_code' => SafeContent::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
