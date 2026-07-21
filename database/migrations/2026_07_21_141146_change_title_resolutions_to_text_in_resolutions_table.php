<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resolutions', function (Blueprint $table) {
            $table->text('title_resolutions')->change();
            // if description_resolutions is also 'string' in the original migration, fix it too:
            // $table->text('description_resolutions')->change();
        });
    }

    public function down(): void
    {
        Schema::table('resolutions', function (Blueprint $table) {
            $table->string('title_resolutions')->change();
        });
    }
};