<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');

            $table->boolean('trade_enabled')->default(false);
            $table->integer('trade_daily')->nullable();
            $table->integer('trade_weekly')->nullable();
            $table->integer('trade_monthly')->nullable();

            $table->boolean('cryptocurrency_enabled')->default(false);
            $table->integer('cryptocurrency_min')->nullable();
            $table->integer('cryptocurrency_max')->nullable();

            $table->boolean('market_cap_enabled')->default(false);
            $table->unsignedTinyInteger('market_cap_margin')->nullable();
            $table->boolean('market_cap_micro_enabled')->default(false);
            $table->unsignedTinyInteger('market_cap_micro')->nullable();
            $table->boolean('market_cap_small_enabled')->default(false);
            $table->unsignedTinyInteger('market_cap_small')->nullable();
            $table->boolean('market_cap_mid_enabled')->default(false);
            $table->unsignedTinyInteger('market_cap_mid')->nullable();
            $table->boolean('market_cap_large_enabled')->default(false);
            $table->unsignedTinyInteger('market_cap_large')->nullable();
            $table->boolean('market_cap_mega_enabled')->default(false);
            $table->unsignedTinyInteger('market_cap_mega')->nullable();

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
        Schema::dropIfExists('limits');
    }
};
