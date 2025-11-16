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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary(); // ✅ UUID primary key

            // Basic info
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('mobile_no')->nullable();

            // Shipping info
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('India');

            // Optional additional info
            $table->string('alternate_contact')->nullable();
            $table->string('landmark')->nullable();

            $table->timestamps();
            $table->softDeletes(); // ✅ soft delete support

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
