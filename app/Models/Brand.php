<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the shop from the brand.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TranslateUtility::class);
    }

    /**
     * Get the products from the brand.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to only include active brands.
     */
    public function scopeIsActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Retrieves the associated media for this model.
     */
    protected $appends = ['thumbnail'];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Generates a thumbnail attribute for the media.
     *
     * @return Attribute The generated thumbnail attribute.
     */
    public function thumbnail(): Attribute
    {
        $thumbnail = asset('default/default.jpg');
        if ($this->media && Storage::exists($this->media->src)) {
            $thumbnail = Storage::url($this->media->src);
        }

        return Attribute::make(
            get: fn() => $thumbnail
        );
    }
}
