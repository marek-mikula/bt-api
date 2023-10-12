<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currency_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_currency_id')->nullable();
            $table->foreignId('quote_currency_id')->nullable();
            $table->string('symbol');

            $table->double('min_quantity')->nullable();
            $table->double('max_quantity')->nullable();
            $table->double('step_size')->nullable();
            $table->double('min_notional')->nullable();
            $table->double('max_notional')->nullable();
            $table->unsignedTinyInteger('base_currency_precision');
            $table->unsignedTinyInteger('quote_currency_precision');

            $table->foreign('base_currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('quote_currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currency_pairs');
    }
};
