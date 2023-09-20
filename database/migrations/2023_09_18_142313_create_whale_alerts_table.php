<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whale_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id');
            $table->string('hash');
            $table->double('amount');
            $table->double('amount_usd');
            $table->string('sender_address')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('receiver_address')->nullable();
            $table->string('receiver_name')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade')
                ->onUpdate('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whale_alerts');
    }
};
