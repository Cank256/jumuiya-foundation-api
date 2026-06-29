<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable()->unique();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('category')->nullable(); // Community, Education, Gender Empowerment, Health & Wellbeing, Environment, Annual Report
            $table->string('featured_image')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->boolean('featured')->default(false);
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('featured');
            $table->index('published_at');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
