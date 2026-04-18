<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dealer_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('brand')->index();
            $table->string('model')->index();
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedInteger('price')->index();
            $table->unsignedInteger('previous_price')->nullable();
            $table->unsignedInteger('market_average_price')->nullable();
            $table->decimal('price_deviation_percentage', 8, 2)->nullable();
            $table->unsignedInteger('mileage')->index();
            $table->string('fuel_type')->index();
            $table->string('transmission')->index();
            $table->string('city')->index();
            $table->text('description');
            $table->string('seller_type')->index();
            $table->string('status')->default('published')->index();
            $table->unsignedTinyInteger('autoiq_score')->default(0)->index();
            $table->unsignedInteger('views_count')->default(0);
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamp('featured_until')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('last_price_drop_at')->nullable()->index();
            $table->text('rejected_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['brand', 'model', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
