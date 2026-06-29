<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // page_view, button_click, form_submission
            $table->string('path', 500)->nullable();
            $table->text('title')->nullable();
            $table->string('button_name')->nullable();
            $table->string('section')->nullable();
            $table->string('form_name')->nullable();
            $table->boolean('success')->nullable();
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->string('session_id', 100)->nullable();
            $table->dateTime('occurred_at');
            $table->timestamp('created_at')->useCurrent();

            $table->index('type');
            $table->index('occurred_at');
            $table->index('path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
