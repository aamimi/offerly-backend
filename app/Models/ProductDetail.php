<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\ProductDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $product_id
 * @property string|null $description
 * @property string|null $conditions
 * @property string|null $instructions
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Product $product
 */
final class ProductDetail extends Model
{
    /** @use HasFactory<ProductDetailFactory> */
    use HasFactory;

    /**
     * Get the product that owns the detail.
     *
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
