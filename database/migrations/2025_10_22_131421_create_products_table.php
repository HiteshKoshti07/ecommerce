<?php

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
        Schema::create('products', function (Blueprint $table) {
            // Use UUID instead of auto-increment ID
            $table->uuid('id')->primary();

            // Basic Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->longText('description')->nullable();

            // Pricing & Stock
            $table->decimal('base_price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);

            // Category & Status
            $table->uuid('category_id')->nullable(); // use uuid if categories use uuid
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');

            // Flexible JSON Columns
            $table->json('variants')->nullable();
            $table->json('options')->nullable();
            $table->json('inventory')->nullable();
            $table->json('tags')->nullable();

            // Single Product Image
            $table->string('product_image')->nullable();

            // SEO (Optional)
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Adds created_at & updated_at
            $table->timestamps();

            // Soft Delete support
            $table->softDeletes();
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
