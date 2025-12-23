<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BirthdayCouponMail;
use App\Models\BirthdayCoupon;
use App\Models\BirthdayCouponSetting;
use Illuminate\Http\Request;

class BirthdayCouponController extends Controller
{
    public function index()
    {
        $couponType = 'birthday_coupon';
        $coupons = BirthdayCoupon::with('user')->latest()->paginate(20);
        return view('admin.birthday_coupon.index', [
            'couponType' => $couponType,
            'coupons' => $coupons
        ]);
    }
}
