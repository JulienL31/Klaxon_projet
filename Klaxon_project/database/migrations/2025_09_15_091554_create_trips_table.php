<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_from_id')->constrained('agencies')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('agency_to_id')->constrained('agencies')->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('departure_dt');
            $table->dateTime('arrival_dt');
            $table->unsignedInteger('seats_total');
            $table->unsignedInteger('seats_free');
            // Infos contact (exigées pour la modale)
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('trips');
    }
};

