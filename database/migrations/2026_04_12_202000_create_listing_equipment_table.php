<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->string('equipment_key', 80);
            $table->timestamps();

            $table->unique(['listing_id', 'equipment_key']);
            $table->index('equipment_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_equipment');
    }
};
