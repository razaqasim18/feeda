<?php

namespace App\Console\Commands;

use App\Events\BirthdayCouponEvent;
use App\Models\BirthdayCoupon;
use App\Models\BirthdayCouponSetting;
use App\Models\DeviceKey;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\NotificationServices;

class SendBirthdayCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-birthday-coupons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            $users = User::whereRaw('MONTH(date_of_birth) = ? AND DAY(date_of_birth) = ?', [
                now()->month,
                now()->day
            ])
            ->where('is_active', 1)
            ->get();
            $setting = BirthdayCouponSetting::findOrFail(1);

            foreach ($users as $user) {
                // Generate a unique coupon code
                do {
                    $randomCode = strtoupper(Str::random(10));
                } while (BirthdayCoupon::where('code', $randomCode)->exists());

                // Create the birthday coupon
                $birthday = new BirthdayCoupon();
                $birthday->user_id = $user->id;
                $birthday->code = $randomCode;
                $birthday->type = $setting->type;
                $birthday->discount = $setting->discount;
                $birthday->is_active = 1;
                $birthday->started_at = now();
                $birthday->expired_at = now()->addDays((int) $setting->day);
                $birthday->save();

                // FCM notification
                $keys = DeviceKey::whereIn('user_id',[$user->id])->pluck('key')->toArray();
                $message = "Here’s a birthday coupon $birthday->code Get discount on your purchase till $birthday->expired_at";
                $response = NotificationServices::sendNotification($message, $keys, "Birthday Coupon");
            
                // Dispatch the event to send the email
                BirthdayCouponEvent::dispatch($user->email, $birthday->code, $birthday->expired_at);
            }
            
        });
    }
}
