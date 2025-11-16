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
        Schema::table('products', function (Blueprint $table) {
            //
            $table->text('product_images')->nullable();   // store JSON or comma string
            $table->string('product_video')->nullable();  // video URL or path
            $table->string('product_fabric')->nullable();
            $table->string('product_work')->nullable();
            $table->string('product_length')->nullable();
            $table->string('product_care')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropColumn([
                'product_images',
                'product_video',
                'product_fabric',
                'product_work',
                'product_length',
                'product_care',
            ]);
        });
    }
};
