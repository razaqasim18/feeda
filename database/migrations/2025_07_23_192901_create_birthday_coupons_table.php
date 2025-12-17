<?php

use App\Models\Shop;
use App\Models\User;
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
        Schema::create('birthday_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignIdFor(Shop::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->nullable(true);
            $table->string('type');
            $table->float('discount');
            $table->timestamp('started_at');
            $table->timestamp('expired_at')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_used')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birthday_coupons');
    }
};
