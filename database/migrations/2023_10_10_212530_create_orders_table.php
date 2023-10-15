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
            $table->unsignedBigInteger('binance_id');
            $table->foreignId('user_id');
            $table->foreignId('pair_id');
            $table->string('side');
            $table->string('status');
            $table->double('base_quantity');
            $table->double('quote_quantity');
            $table->double('price');
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
