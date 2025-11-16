<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();

            // Store Details
            $table->string('store_name')->nullable();
            $table->string('store_slug')->nullable();
            $table->string('store_logo')->nullable(); // path to logo image
            $table->string('cover_banner')->nullable(); // path to cover image
            $table->string('short_description', 255)->nullable();
            $table->text('about_store')->nullable();

            // Address / Owner Info
            $table->string('owner_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('country')->default('India');

            // General Settings
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
