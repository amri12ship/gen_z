<?php

namespace App\Models;

use App\Support\Uploads;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const MAX_IMAGES = 4;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'rating',
        'sold_count',
        'affiliate_url',
        'is_trending',
        'is_viral',
        'is_recommended',
        'clicks',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rating' => 'decimal:2',
            'sold_count' => 'integer',
            'is_trending' => 'boolean',
            'is_viral' => 'boolean',
            'is_recommended' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->latest();
    }

    public function affiliateClicks(): HasMany
    {
        return $this->hasMany(AffiliateClick::class);
    }

    public function imageUrl(): ?string
    {
        $image = $this->loadMissing('primaryImage')->primaryImage;

        return $image ? Uploads::url($image->image_path) : null;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
