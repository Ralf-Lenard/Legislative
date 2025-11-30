<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resolutions', function (Blueprint $table) {
            $table->id();

            $table->string('resolutions_number')->unique(); // EX: 2025-001
            $table->string('title_resolutions');
            $table->text('description_resolutions')->nullable();
            $table->date('date_approved_resolutions')->nullable();

            // File upload (PDF)
            $table->string('file_path_resolutions')->nullable();

            // Image upload (thumbnail or cover)
            $table->string('image_resolutions')->nullable();

            // Optional: for SB Member author/sponsor
            $table->string('author_resolutions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resolutions');
    }
};
