<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCouponRequest;
use App\Http\Requests\VoucherRequest;
use App\Http\Resources\CouponResource;
use App\Models\Point_redeem;
use App\Models\PointRedeemCoupon;
use App\Models\PointTransaction;
use App\Models\Shop;
use App\Models\User;
use App\Repositories\CouponCollectRepository;
use App\Repositories\CouponRepository;
use App\Repositories\PointRedeemCouponRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    /**
     * get shop voucher from shop
     */
    public function index(VoucherRequest $request)
    {
        $shopId = $request->shop_id;

        $coupons = CouponRepository::query()->whereShopId($shopId)->Active()->isValid()->get();

        // $coupons = CouponRepository::query()->whereShopId($shopId)
        //     ->orWhereHas('shops', function ($query) use ($shopId) {
        //         $query->where('id', $shopId);
        //     })->Active()->isValid()->get();

        return $this->json('Shop vouchers', [
            'coupons' => CouponResource::collection($coupons),
        ]);
    }

    /**
     * collect voucher
     * */
    public function store(VoucherRequest $request)
    {
        $hasExistCoupon = CouponCollectRepository::hasExistCoupon($request);

        if ($hasExistCoupon) {
            return $this->json('Voucher already collected');
        }

        $coupon = CouponCollectRepository::storeByRequest($request);

        return $this->json('Voucher collected successfully', [
            'coupon' => CouponResource::make($coupon->coupon),
        ]);
    }

    /**
     * get collected vouchers
     *
     * @param  VoucherRequest  $request
     * */
    public function collectedVouchers(Request $request)
    {
        // get shop id
        $shopId = $request->shop_id;

        // get collected vouchers from repository
        $coupons = CouponRepository::getCollectedCoupons($shopId);

        return $this->json('available collected vouchers', [
            'coupons' => CouponResource::collection($coupons),
        ]);
    }

    /**
     * Apply voucher from user collected vouchers
     */
    public function applyVoucher(ApplyCouponRequest $request)
    {
        $couponDiscount = CouponRepository::getCouponDiscount($request);

        $message = $couponDiscount['discount_amount'] > 0 ? 'Voucher applied successfully' : 'Voucher not applied';

        $status = $couponDiscount['discount_amount'] > 0 ? 200 : 201;

        return $this->json($message, [
            'total_order_amount' => (float) number_format($couponDiscount['total_amount'], 2, '.', ''),
            'total_discount_amount' => (float) number_format($couponDiscount['discount_amount'], 2, '.', ''),
        ], $status);
    }

    /**
     * Apply coupon from coupon code
     * */
    public function getDiscount(ApplyCouponRequest $request)
    {
        $couponDiscount = CouponRepository::getCouponDiscount($request);

        $message = $couponDiscount['discount_amount'] > 0 ? 'Voucher applied successfully' : 'Voucher not applied';

        $status = $couponDiscount['discount_amount'] > 0 ? 200 : 201;

        return $this->json($message, [
            'total_order_amount' => (float) round($couponDiscount['total_amount'], 2),
            'total_discount_amount' => (float) round($couponDiscount['discount_amount'], 2),
        ], $status);
    }

    public function pointRedeemCoupon(Request $request)
    {
       $userPoint = User::find($request->header('userid'))?->points;
        // get collected vouchers from repository
        $pointredeem = Point_redeem::all();
        return $this->json('available point redeem list', [
            'pointredeem' => $pointredeem,
            'userpoint' => $userPoint,
        ]);
    }


    public function redeemPoints(Request $request)
    {
        $user = User::find($request->userid);

        if (!$user) {
            return $this->json('User not found', [], 404);
        }

        $pointRedeem = Point_redeem::find($request->pointredeem_id);

        if (!$pointRedeem) {
            return $this->json('Invalid point redeem option', [], 404);
        }

        if ($user->points < $pointRedeem->points) {
            return $this->json('Insufficient points to redeem this coupon', [], 400);
        }

        DB::beginTransaction();

        try {
            // Deduct points
            $user->decrement('points', $pointRedeem->points);

            // Point transaction
            PointTransaction::create([
                'user_id'   => $user->id,
                'point'     => $pointRedeem->points,
                'is_credit' => 0,
                'description' => "Redeemed coupon of {$pointRedeem->amount} for {$pointRedeem->points} points",
            ]);

            // Coupon dates
            $startDateTime   = now();
            $expiredDateTime = now()->addDays(4);

            // Unique 8-digit code
            do {
                $code = random_int(10000000, 99999999);
            } while (PointRedeemCoupon::where('code', $code)->exists());

            // Active shop
            $shop = Shop::isActive()->first();

            if (!$shop) {
                throw new \Exception('No active shop found');
            }

            // Create coupon
            PointRedeemCoupon::create([
                'user_id'    => $user->id,
                'code'       => $code,
                'discount'   => $pointRedeem->amount,
                'type'       => 'amount',
                'started_at' => $startDateTime,
                'expired_at' => $expiredDateTime,
                'shop_id'    => $shop->id,
                'is_active'  => true,
                'is_used'    => 0,
            ]);

            DB::commit();

            return $this->json('Points redeemed successfully', [
                'new_point_balance' => $user->fresh()->points,
                'coupon_code' => $code,
                'expires_at' => $expiredDateTime,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->json('Failed to redeem points', [
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function userPointRedeemCoupon(Request $request)
    {
        $perPage = $request->get('per_page', 10); // default 10

        $coupons = PointRedeemCoupon::where('user_id', $request->header('userid'))
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return $this->json('Points redeemed coupon list', [
            'pointcoupons' => $coupons,
        ]);
    }
}
