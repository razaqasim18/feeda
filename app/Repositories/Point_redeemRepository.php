<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\Point_redeem;

class Point_redeemRepository extends Repository
{
    public static function model()
    {
        return Point_redeem::class;    
    }
}