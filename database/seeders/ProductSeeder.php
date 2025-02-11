<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MetaTag;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Random\RandomException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

final class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::all()->each(function (Category $category): void {
            $products = Product::factory()
                ->count(10)
                ->for($category)
                ->has(MetaTag::factory()->count(1), 'metaTag')
                ->create([
                    'published_at' => random_int(0, 1) !== 0 ? now() : null,
                ]);
            try {
                $products->each(function (Product $product): void {
                    for ($i = 0; $i < random_int(1, 4); ++$i) {
                        $product->addMedia(public_path('images/placeholderX400.jpg'))
                            ->preservingOriginal()
                            ->toMediaCollection(Config::string('app.media_collections.products.name'));
                    }
                });
            } catch (FileCannotBeAdded|RandomException $e) {
                $this->command->error($e->getMessage());
            }
        });
    }
}
