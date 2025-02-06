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
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'parent_id')->nullable()->constrained('categories');
            $table->string(column: 'slug', length: 100)->unique();
            $table->string(column: 'name', length: 100);
            $table->string('image_url')->nullable();
            $table->string(column: 'meta_title', length: 255)->nullable();
            $table->string(column: 'meta_description', length: 255)->nullable();
            $table->unsignedBigInteger('display_order')->default(0);
            $table->unsignedBigInteger('views')->default(0);
            $table->dateTime(column: 'created_at')->useCurrent();
            $table->dateTime(column: 'updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
