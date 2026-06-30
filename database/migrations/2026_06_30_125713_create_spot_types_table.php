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
        Schema::create('spot_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();      // 'billboard', 'radio', 'youtube_blogger'
            $table->string('name_ru');
            $table->string('name_ro');
            $table->string('name_en');
            $table->string('icon')->nullable();     // emoji или класс иконки
            $table->string('category')->default('outdoor'); // outdoor / digital / media
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spot_types');
    }
};
