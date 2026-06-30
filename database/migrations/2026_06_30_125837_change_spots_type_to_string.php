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
        Schema::table('spots', function (Blueprint $table) {
            $table->string('type', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->enum('type', [
                'billboard','lightbox','led_screen','banner',
                'transport','indoor','digital','event'
            ])->change();
        });
    }
};
