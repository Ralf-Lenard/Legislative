<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Committee name
            $table->text('focus')->nullable(); // optional focus field
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
