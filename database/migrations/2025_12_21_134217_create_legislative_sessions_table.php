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
        Schema::create('legislative_sessions', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('session_number')->unique(); // e.g., '001', '005-A'
            $table->string('session_title'); // Title of the session
            $table->date('date_of_session'); // Date of the session
            $table->enum('session_type', ['Regular', 'Special']); // Type: Regular or Special
            $table->text('summary'); // Summary of the session
            $table->json('images')->nullable(); // JSON column for images
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legislative_sessions');
    }
};
