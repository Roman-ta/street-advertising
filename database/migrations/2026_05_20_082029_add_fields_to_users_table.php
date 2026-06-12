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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['client', 'partner', 'admin'])->default('client');
            $table->string('phone')->nullable();
            $table->string('lang', 2)->default('ru');
            $table->boolean('is_active')->default(true);
            $table->boolean('legal_signed')->default(false);
            $table->timestamp('legal_signed_at')->nullable();
            $table->string('idno', 20)->nullable();       // молд. ID компании
            $table->string('iban')->nullable();           // для партнёра
            $table->string('bank_name')->nullable();
            $table->string('legal_address')->nullable();
            $table->boolean('profile_complete')->default(false);
            $table->string('telegram_chat_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
