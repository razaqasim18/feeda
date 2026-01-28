<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            // Make sure point_coupon_id type matches point_redeem_coupons.id (unsignedBigInteger)
            $table->unsignedBigInteger('point_coupon_id')->nullable()->after('coupon_discount');

            // Add foreign key
            $table->foreign('point_coupon_id')->references('id')->on('point_redeem_coupons')->nullOnDelete();

            // Add boolean column
            $table->boolean('is_point_coupon')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->dropForeign(['point_coupon_id']); // ✅ correct
            $table->dropColumn('point_coupon_id');
            $table->dropColumn('is_point_coupon');
        });
    }
};
