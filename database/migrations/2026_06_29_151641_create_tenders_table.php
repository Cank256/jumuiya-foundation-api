<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('status')->default('open'); // open, closed, awarded
            $table->string('reference_number')->nullable()->unique();
            $table->longText('description')->nullable();
            $table->longText('requirements')->nullable();
            $table->dateTime('deadline')->nullable();
            // Flat legacy document fields (kept for backwards compat)
            $table->string('document_url')->nullable();
            $table->boolean('has_rfp_document')->default(false);
            $table->string('rfp_path')->nullable();
            $table->string('rfp_document_name')->nullable();
            $table->unsignedBigInteger('rfp_document_size')->nullable();
            $table->boolean('has_tor_document')->default(false);
            $table->string('tor_path')->nullable();
            $table->string('tor_document_name')->nullable();
            $table->unsignedBigInteger('tor_document_size')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
