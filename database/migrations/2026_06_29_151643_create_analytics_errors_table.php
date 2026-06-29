<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_errors', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->json('context')->nullable();
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->dateTime('occurred_at');
            $table->timestamp('created_at')->useCurrent();

            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_errors');
    }
};
