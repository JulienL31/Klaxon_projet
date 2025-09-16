<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('agency_from_id')->constrained('agencies')->restrictOnDelete();
            $table->foreignId('agency_to_id')->constrained('agencies')->restrictOnDelete();

            $table->dateTime('departure_dt');
            $table->dateTime('arrival_dt');

            $table->unsignedInteger('seats_total');
            $table->unsignedInteger('seats_free');

            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();

            // auteur du trajet (utilisateur existant)
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();

            $table->timestamps();

            // (optionnel) équivalents "checks"
            $table->index('departure_dt');
            $table->index('seats_free');
        });
    }

    public function down(): void {
        Schema::dropIfExists('trips');
    }
};
