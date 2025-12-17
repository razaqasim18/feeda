<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Media;
use App\Models\Shop;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->foreignIdFor(Media::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Shop::class)->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('status')->default(1);
            $table->string('product_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
