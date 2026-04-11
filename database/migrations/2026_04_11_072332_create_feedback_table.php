<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            // Link to users table (optional)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Added Category Column
            $table->enum('category', ['suggestion', 'concern', 'commendation', 'inquiry', 'other'])
                ->default('other');

            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};