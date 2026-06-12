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
        Schema::create('spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->enum('type', [
                'billboard','lightbox','led_screen','banner',
                'transport','indoor','digital','event'
            ]);
            $table->string('address');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->string('city')->default('Chisinau');
            $table->string('district')->nullable();
            $table->decimal('size_w', 6, 2)->nullable(); // ширина в метрах
            $table->decimal('size_h', 6, 2)->nullable(); // высота в метрах
            $table->decimal('price_month', 10, 2);
            $table->text('description')->nullable();
            $table->boolean('lighting')->default(false);
            $table->enum('traffic', ['low','medium','high'])->default('medium');
            $table->json('file_types_allowed')->nullable(); // ['pdf','tiff','png']
            $table->enum('status', ['draft','moderation','active','blocked'])->default('draft');
            $table->json('translations')->nullable(); // мультиязычность
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spots');
    }
};
