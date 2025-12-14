<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('committee_official', function (Blueprint $table) {
            $table->id();
            $table->foreignId('official_id')->constrained('officials')->cascadeOnDelete();
            $table->foreignId('committee_id')->constrained('committees')->cascadeOnDelete();
            $table->string('role'); // Chairperson, Ex-Officio Member
            $table->timestamps();

            $table->unique(['official_id', 'committee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_official');
    }
};
