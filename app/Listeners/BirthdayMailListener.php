<?php

namespace App\Listeners;

use App\Events\BirthdayCouponEvent;
use App\Mail\BirthdayCouponMail;
use Illuminate\Support\Facades\Mail;

class BirthdayMailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BirthdayCouponEvent $event): void
    {
        if (config('app.mail_username') && config('app.mail_password') && config('app.mail_encryption')) {
            Mail::to($event->email)->send(new BirthdayCouponMail($event->email, $event->coupon, $event->validday));
        }
    }
}
