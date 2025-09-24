<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('agency_from_id')->constrained('agencies');
            $table->foreignId('agency_to_id')->constrained('agencies');
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();

            $table->dateTime('departure_at');
            $table->dateTime('arrival_at');

            $table->unsignedTinyInteger('seats_total');
            $table->unsignedTinyInteger('seats_free');

            $table->timestamps();

            $table->index('departure_at');
            $table->index('seats_free');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
