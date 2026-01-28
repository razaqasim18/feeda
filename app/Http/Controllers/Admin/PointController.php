<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Point_redeem;
use App\Models\PointRedeemCoupon;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index(){

        return view('admin.points.index',[
            'points' => Point_redeem::paginate(10),
        ]);
    }

  
    public function store(Request $request){
        $request->validate([
            'amount' => 'required|numeric',
            'points' => 'required|numeric',
        ]);

        Point_redeem::create([
            'amount' => $request->amount,
            'points' => $request->points,
        ]);
        return redirect()->back()->with('success', 'Point Redeem created successfully.'); 
    }

    public function update(Request $request, $id){
        $request->validate([
            'amount' => 'required|numeric',
            'points' => 'required|numeric',
        ]);

        $point = Point_redeem::findOrFail($id);
        $point->update([
            'amount' => $request->amount,
            'points' => $request->points,
        ]);

        return redirect()->back()->with('success', 'Point Redeem updated successfully.');
    }

    public function indexCoupon(){
        $couponType = 'point_redeem_coupon';
        $coupons = PointRedeemCoupon::with('user')->latest()->paginate(20);
        return view('admin.points.coupon', [
            'couponType' => $couponType,
            'coupons' => $coupons
        ]);
    }
  
}
