<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('officials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position'); // Vice Mayor / Presiding Officer
            $table->string('main_committee')->nullable();
            $table->string('image')->nullable();
            $table->text('bio')->nullable();
            $table->string('division')->nullable();
            $table->string('type')->default('official');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('officials');
    }
};
