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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->string('turkish_description');
            $table->string('description');
            $table->string('category');
            $table->string('data_type')->default('Unknown');
            $table->string('fdc_id')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('food');
        Schema::enableForeignKeyConstraints();
    }
};
