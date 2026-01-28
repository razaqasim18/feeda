<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\PointRedeemCoupon;

class PointRedeemCouponRepository extends Repository
{
    public static function model()
    {
        return PointRedeemCoupon::class;    
    }
    
    public static function getPointRedeeList()
    {
        $coupons = self::query()->get();
        return $coupons;
    }
}