<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refresh_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->uuid('refresh_token')->unique();
            $table->string('device');
            $table->boolean('invalidated')->default(false);
            $table->timestamp('valid_until');
            $table->timestamp('invalidated_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refresh_tokens');
    }
};
