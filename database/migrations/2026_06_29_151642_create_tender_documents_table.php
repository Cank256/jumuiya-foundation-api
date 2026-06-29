<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable(); // display filename
            $table->string('type')->default('other'); // rfp, tor, specification, other
            $table->string('path'); // stored path; served as absolute URL
            $table->unsignedBigInteger('size')->nullable(); // bytes
            $table->timestamps();

            $table->index('tender_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_documents');
    }
};
