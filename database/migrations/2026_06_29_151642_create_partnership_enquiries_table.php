<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partnership_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('organisation');
            $table->string('email');
            $table->string('partnership_type')->nullable(); // Institutional Partner, Funding Partner, Corporate Partner, Community Partner, Other
            $table->longText('message');
            $table->string('status')->default('unread'); // unread, read, replied, archived
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_enquiries');
    }
};
