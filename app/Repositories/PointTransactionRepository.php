<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\PointTransaction;

class PointTransactionRepository extends Repository
{
    public static function model()
    {
        return PointTransaction::class;    
    }
}