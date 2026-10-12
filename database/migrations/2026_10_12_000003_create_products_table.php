<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->unsignedInteger('sold_count')->default(0);
            $table->string('affiliate_url');
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_viral')->default(false);
            $table->boolean('is_recommended')->default(false);
            $table->unsignedInteger('clicks')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
