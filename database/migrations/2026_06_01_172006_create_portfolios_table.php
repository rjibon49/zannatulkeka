<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();

            $table->string('name')->default('Zannatul Keka');
            $table->string('designation')->nullable();
            $table->string('headline')->nullable();

            $table->text('short_intro')->nullable();
            $table->longText('bio')->nullable();

            $table->foreignId('profile_picture_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->foreignId('cover_media_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->foreignId('resume_pdf_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            $table->string('website_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('github_url')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};