<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistances', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // medical, legal, scholar
            $table->string('full_name');
            $table->string('barangay')->nullable(); // for medical & legal
            $table->string('school')->nullable();   // for scholars
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistances');
    }
};