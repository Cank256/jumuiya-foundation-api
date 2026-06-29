<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('category')->nullable(); // Forum, Workshop, Training, Fundraising, Symposium, Networking, Conference
            $table->string('location')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('time')->nullable(); // Human-readable e.g. "9:00 AM – 5:00 PM EAT"
            $table->string('seats')->nullable(); // Human-readable e.g. "Limited seats available"
            $table->string('status')->default('upcoming'); // upcoming, ongoing, completed
            $table->boolean('featured')->default(false);
            $table->string('featured_image')->nullable(); // stored path; served as absolute URL
            $table->string('registration_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('featured');
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
