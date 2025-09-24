<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('contact_name', 100)->nullable()->after('author_id');
            $table->string('contact_email', 190)->nullable()->after('contact_name');
            $table->string('contact_phone', 30)->nullable()->after('contact_email');
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['contact_name','contact_email','contact_phone']);
        });
    }
};
