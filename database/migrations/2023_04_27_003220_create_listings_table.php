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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->string('vendor')->nullable();
            $table->string('vendor_id')->nullable();
            $table->string('title');
            $table->string('short_description')->nullable();
            $table->string('location')->nullable();
            $table->string('salary')->nullable();
            $table->integer('hours')->nullable();
            $table->string('company_name')->nullable();
            $table->dateTime('listing_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
