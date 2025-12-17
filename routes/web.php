<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\Gateway\Bkash\ExecutePaymentController;
use App\Http\Controllers\Gateway\PaymentGatewayController;
use App\Http\Controllers\Gateway\PayTabs\ProcessController;
use App\Http\Controllers\PassportStorageSupportController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Events\BirthdayCouponEvent;
use App\Models\BirthdayCoupon;
use App\Models\BirthdayCouponSetting;
use App\Models\DeviceKey;
use App\Models\User;
use App\Services\NotificationServices;

use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




// Clear application cache
Route::get('/clear-cache', function() {
    try {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        
        return response()->json([
            'success' => true,
            'message' => 'Application cache cleared successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to clear cache: ' . $e->getMessage()
        ], 500);
    }
});

Route::get("/check", function(){
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

Route::get('/schedule-run', function () {
    info("schedule run executed " . now());
    Artisan::call('schedule:run');
    echo "schedule-run";
});

// Change language
Route::get('/change-language', function () {
    if (request()->language) {
        App::setLocale(request()->language);
        session()->put('locale', request()->language);
    }

    return back();
})->name('change.language');

// Install Passport and storage routes
Route::controller(PassportStorageSupportController::class)->group(function () {
    Route::get('/install-passport', 'index')->name('passport.install.index');
    Route::get('/seeder-run', 'seederRun')->name('seeder.run.index');
    Route::get('/storage-install', 'storageInstall')->name('storage.install.index');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/{provider}/callback', 'callback');
    Route::post('/auth/{provider}/callback', 'callback');
});

// Payment gateway routes
Route::controller(PaymentGatewayController::class)->group(function () {
    // payment routes
    Route::get('/order/{payment}/payment', 'payment')->name('order.payment');

    // success and cancel routes for payment
    Route::get('/order/{payment}/payment/success', 'paymentSuccess')->name('order.payment.success');
    Route::get('/order/{payment}/payment/cancel', 'paymentCancel')->name('order.payment.cancel');

    // success and cancel routes for callback
    Route::get('/payment/{payment}/callback-success', 'success')->name('payment.success');
    Route::get('/payment/{payment}/callback-cancel', 'cancel')->name('payment.cancel');

    // success and cancel routes for callback
    Route::post('/payment/{payment}/callback-success', 'success')->name('payment.success');
    Route::post('/payment/{payment}/callback-cancel', 'cancel')->name('payment.cancel');
});

// Bkash Payment execute
Route::get('/bkash-payment/{payment}/execute', [ExecutePaymentController::class, 'index'])->name('bkash.payment.execute');

// Paytabs payment execute
// Route::get('/paytabs/{payment}/callback', [ProcessController::class, 'callback'])->name('paytabs.payment.callback');
Route::post('/paytabs/{payment}/callback', [ProcessController::class, 'callback'])->name('paytabs.payment.callback');




// handle frontend page load
Route::get('/{any}', function () {

    // manage admin and shop routes
    if (request()->is('admin/*', 'shop/*')) {
        return abort(404);
    }

    return view('app');
})->where('any', '.*');
