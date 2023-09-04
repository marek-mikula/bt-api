<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->boolean('as_mail')->default(false);
            $table->boolean('as_notification')->default(false);
            $table->string('title');
            $table->mediumText('content')->nullable();
            $table->date('date_at');
            $table->time('time_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('queued_at')->nullable();
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
        Schema::dropIfExists('alerts');
    }
};
