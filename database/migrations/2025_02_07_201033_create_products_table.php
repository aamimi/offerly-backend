<?php

declare(strict_types=1);

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
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'slug')->unique();
            $table->string(column: 'title')->index();
            $table->text(column: 'summary')->nullable()->index();
            $table->text(column: 'description')->nullable();
            $table->decimal(column: 'price')->nullable();
            $table->decimal(column: 'discount_price')->nullable();
            $table->integer(column: 'rating')->default(value: 0);
            $table->integer(column: 'views')->default(value: 0);
            $table->foreignId(column: 'category_id')->index()->constrained('categories');
            $table->dateTime(column: 'published_at')->nullable()->index();
            $table->dateTime(column: 'created_at')->useCurrent();
            $table->dateTime(column: 'updated_at')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
