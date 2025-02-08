<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Random\RandomException;

final class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @throws RandomException
     */
    public function run(): void
    {
        Category::factory()
            ->count(10)
            ->has(Category::factory()->count(random_int(10, 20)), 'subcategories')
            ->create();
    }
}
