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
        Schema::create('registration_tokens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('proposed_username');
            $table->char('value', 10)->unique();
            $table->boolean('is_valid')->default(1);
            $table->dateTime('expired_time');
            $table->dateTime('used_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_tokens');
    }
};
