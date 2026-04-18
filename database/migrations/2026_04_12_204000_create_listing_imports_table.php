<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source_name')->index();
            $table->string('source_listing_id')->nullable()->index();
            $table->string('source_url')->unique();
            $table->string('status')->default('pending')->index();
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->boolean('challenge_detected')->default(false)->index();
            $table->string('title')->nullable();
            $table->string('brand')->nullable()->index();
            $table->string('model')->nullable()->index();
            $table->unsignedSmallInteger('year')->nullable()->index();
            $table->unsignedInteger('price')->nullable()->index();
            $table->unsignedInteger('mileage')->nullable()->index();
            $table->string('fuel_type')->nullable()->index();
            $table->string('transmission')->nullable()->index();
            $table->string('city')->nullable()->index();
            $table->string('seller_type')->nullable()->index();
            $table->string('cover_image_url')->nullable();
            $table->text('description')->nullable();
            $table->json('image_urls')->nullable();
            $table->json('payload')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('fetched_at')->nullable()->index();
            $table->timestamp('imported_at')->nullable()->index();
            $table->timestamps();

            $table->index(['source_name', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_imports');
    }
};
