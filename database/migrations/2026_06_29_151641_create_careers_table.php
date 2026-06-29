<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('status')->default('active'); // active, closed
            $table->string('department')->nullable();
            $table->string('employment_type')->nullable(); // full-time, part-time, contract, internship, consultancy, volunteer
            $table->string('location')->nullable();
            $table->string('salary_range')->nullable();
            $table->date('application_deadline')->nullable();
            $table->string('reports_to')->nullable();
            $table->string('supervises_who')->nullable();
            $table->longText('description')->nullable();
            $table->longText('purpose_of_role')->nullable();
            $table->longText('responsibilities')->nullable();
            $table->longText('requirements')->nullable();
            $table->longText('core_competencies')->nullable();
            $table->longText('application_requirements')->nullable();
            $table->longText('application_process')->nullable();
            $table->longText('disclaimer')->nullable();
            $table->string('apply_here')->nullable(); // external application URL
            $table->boolean('has_document')->default(false);
            $table->string('document_path')->nullable(); // stored path; served as absolute URL
            $table->string('document_name')->nullable();
            $table->unsignedBigInteger('document_size')->nullable(); // bytes
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('application_deadline');
            $table->index('employment_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
