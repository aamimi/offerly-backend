<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MetaTag;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::all()->each(function (Category $category): void {
            Product::factory()
                ->count(10)
                ->for($category)
                ->has(MetaTag::factory()->count(1), 'metaTag')
                ->create();
        });
    }
}
