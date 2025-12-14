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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relation to product
            $table->string('product_id');

            // Reviewer info
            $table->string('customer_name');
            $table->integer('rating'); // 1–5
            $table->string('review_title')->nullable();
            $table->text('review_text')->nullable();

            // Optional review images (multiple)
            $table->json('images')->nullable();

            // Optional: Logged-in user
            $table->string('customer_id')->nullable();

            // Verified purchase (if ordered from your site)
            $table->boolean('verified_purchase')->default(false);

            // Helpful review count
            $table->integer('helpful_count')->default(0);

            // IP Address (spam protection)
            $table->ipAddress('ip_address')->nullable();

            // Admin can approve reviews
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
