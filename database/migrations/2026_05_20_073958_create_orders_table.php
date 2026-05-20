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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', [
                'pending',
                'paid_pending',
                'materials_ready',
                'active',
                'completed',
                'cancelled',
                'disputed'
            ])->default('pending');
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('commission', 10, 2)->default(0);
            $table->decimal('commission_pct', 5, 2)->default(10.00);
            $table->string('legal_signed_version')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
