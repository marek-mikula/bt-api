<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('name');
            $table->boolean('is_fiat')->default(false);
            $table->unsignedBigInteger('cmc_id');
            $table->unsignedBigInteger('cmc_rank')->nullable();
            $table->json('meta');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
