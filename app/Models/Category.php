<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;

/**
 * @property-read int $id
 * @property string $name
 * @property string $slug
 * @property string $image_url
 * @property string $meta_title
 * @property string $meta_description
 * @property int $display_order
 * @property int $views
 * @property int|null $parent_id
 * @property-read Category $parent
 * @property-read Collection<int, Category> $subcategories
 */
final class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    /**
     * Get the subcategories for the category.
     *
     * @return HasMany<Category, $this>
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(related: self::class, foreignKey: 'parent_id');
    }

    /**
     * Get the category that owns the category.
     *
     * @return BelongsTo<Category, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(related: self::class, foreignKey: 'parent_id');
    }

    /**
     * Get the image URL for the category.
     */
    public function getImageUrl(): string
    {
        return $this->image_url ?? asset(Config::string('app.default_images.category'));
    }
}
