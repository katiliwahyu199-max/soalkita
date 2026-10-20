<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionSetting extends Model
{
    protected $fillable = [
        'monthly_price',
        'payment_instructions',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'monthly_price' => 75000,
            'payment_instructions' => '',
        ]);
    }
}