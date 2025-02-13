<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Config;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $summary
 * @property float|null $price
 * @property float|null $discount_price
 * @property int $rating
 * @property int $views
 * @property int $category_id
 * @property Carbon|null $published_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Category $category
 * @property-read MetaTag $metaTag
 * @property-read ProductDetail $details
 */
final class Product extends Model implements HasMedia
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    use InteractsWithMedia;

    /**
     * Register the product media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(name: Config::string('app.media_collections.products.name'))
            ->useDisk(diskName: Config::string('app.media_collections.products.disk'))
            ->onlyKeepLatest(
                maximumNumberOfItemsInCollection: Config::integer(
                    'app.media_collections.products.maximum_number_of_items'
                )
            );
    }

    /**
     * Get the product detail.
     *
     * @return HasOne<ProductDetail, $this>
     */
    public function details(): HasOne
    {
        return $this->hasOne(ProductDetail::class);
    }

    /**
     * Get the category of the product.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the meta tag of the model.
     *
     * @return MorphOne<MetaTag, $this>
     */
    public function metaTag(): MorphOne
    {
        return $this->morphOne(MetaTag::class, 'metaable');
    }

    /**
     * Get the comments for the product.
     *
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
}
