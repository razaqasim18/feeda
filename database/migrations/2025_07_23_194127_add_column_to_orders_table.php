<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('birthday_coupon_id')->nullable()->constrained()->nullOnDelete()->before("coupon_discount");
            $table->boolean('is_birthday_coupon')->default(NULL)->after("coupon_discount");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['birthday_coupon_id']); // ✅ correct
            $table->dropColumn('birthday_coupon_id');
            $table->dropColumn('is_birthday_coupon');
        });
    }
};
