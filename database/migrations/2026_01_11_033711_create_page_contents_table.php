<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();

            // Images
            $table->string('welcome_image')->nullable();
            $table->string('about_us_image')->nullable();

            //organizational chart image
            $table->string('organizational_chart')->nullable();

            // Text contents
            $table->text('vice_mayor_message')->nullable();
            $table->text('about_us')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();

            // Gallery (store multiple images as JSON)
            $table->json('gallery_images')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};
