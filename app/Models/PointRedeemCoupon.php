<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointRedeemCoupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'code',
        'discount',
        'type',
        'started_at',
        'expired_at',
        'shop_id',
        'is_active',
        'is_used'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}