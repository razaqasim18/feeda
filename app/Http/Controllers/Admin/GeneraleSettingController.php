<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DiscountType;
use App\Http\Controllers\Controller;
use App\Http\Requests\GeneraleSettingRequest;
use App\Models\BirthdayCouponSetting;
use App\Models\Currency;
use App\Repositories\GeneraleSettingRepository;
use Illuminate\Http\Request as HttpRequest;


class GeneraleSettingController extends Controller
{
    /**
     * Display a listing of the generale settings.
     */
    public function index()
    {
        $currencies = Currency::all();

        return view('admin.generale-setting', compact('currencies'));
    }

    /**
     * Update the generale settings.
     */
    public function update(GeneraleSettingRequest $request)
    {
        if (app()->environment() == 'local') {
            return back()->with('demoMode', 'You can not update the generale settings in demo mode');
        }

        // store generale settings from generaleSettingRepository
        GeneraleSettingRepository::updateByRequest($request);

        return back()->withSuccess(__('Generale settings updated successfully'));
    }

    public function BirthdayCouponindex()
    {
        $setting = BirthdayCouponSetting::first();
        $discountTypes = DiscountType::cases();
        return view('admin.birthday-coupon-setting', compact('setting', 'discountTypes'));
    }

    public function BirthdayCouponupdate(HttpRequest $request)
    {
        $setting = BirthdayCouponSetting::first() ?? new BirthdayCouponSetting();
        $setting->discount =  $request->discount;
        $setting->type =  $request->discount_type;
        $setting->day = $request->day;
        $setting->save();
        return back()->withSuccess(__('Birthday Coupon settings updated successfully'));
    }
}
