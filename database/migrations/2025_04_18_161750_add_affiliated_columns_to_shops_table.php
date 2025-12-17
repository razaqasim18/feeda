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
        Schema::table('shops', function (Blueprint $table) {
            //
            $table->string('affiliate_promo_code')->after('description')->nullable();
            $table->string('affiliate_promo_discount')->after('affiliate_promo_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            //
            $table->dropColumn('affiliate_promo_code');
            $table->dropColumn('affiliate_promo_discount');
        });
    }
};
