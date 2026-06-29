<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annual_reports', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();   // preferred: "2024 / 2025 Annual Report"
            $table->string('title')->nullable();   // fallback
            $table->string('year')->nullable();    // fallback if neither label/title
            $table->string('file_path')->nullable(); // stored path; served as absolute download_url
            $table->string('href')->nullable();    // fallback URL if no file uploaded
            $table->unsignedBigInteger('file_size')->nullable(); // bytes; formatted_file_size computed
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annual_reports');
    }
};
