<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordinances', function (Blueprint $table) {
            $table->id();

            $table->string('ordinance_number')->unique(); // EX: 2025-001
            $table->string('title_ordinances');
            $table->text('description_ordinances')->nullable();
            $table->date('date_approved_ordinances')->nullable();

            // File upload (PDF)
            $table->string('file_path_ordinances')->nullable();

            // Image upload (thumbnail or cover)
            $table->string('image_ordinances')->nullable();

            // Optional: for SB Member author/sponsor
            $table->string('author_ordinances')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordinances');
    }
};
