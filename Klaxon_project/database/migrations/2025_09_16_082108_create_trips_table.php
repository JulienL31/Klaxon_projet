<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('trips')) {
            return;
        }

        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            // FKs
            $table->foreignId('agency_from_id')->constrained('agencies');
            $table->foreignId('agency_to_id')->constrained('agencies');
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();

            // Datetimes
            $table->dateTime('departure_at');
            $table->dateTime('arrival_at');

            // Places
            $table->unsignedTinyInteger('seats_total'); // >= 1
            $table->unsignedTinyInteger('seats_free');  // <= seats_total

            $table->timestamps();

            // Index
            $table->index('departure_at');
            $table->index('seats_free');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
