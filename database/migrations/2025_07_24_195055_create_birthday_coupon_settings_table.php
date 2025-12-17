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
        Schema::create('birthday_coupon_settings', function (Blueprint $table) {
            $table->id();
            $table->string('discount');
            $table->string('type');
            $table->string('day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birthday_coupon_settings');
    }
};
