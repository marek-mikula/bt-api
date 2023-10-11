<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('binance_uuid');
            $table->foreignId('user_id');
            $table->foreignId('pair_id')->nullable();
            $table->string('type');
            $table->string('status');
            $table->double('quantity');
            $table->timestamps();

            $table->foreign('pair_id')
                ->references('id')
                ->on('currency_pairs')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
