<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('featured_media_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('slug')->unique();

            $table->text('excerpt')->nullable();
            $table->longText('description');

            $table->string('video_url')->nullable();
            $table->string('youtube_video_id')->nullable();

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->string('canonical_url')->nullable();

            $table->foreignId('og_media_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->unsignedBigInteger('views_count')->default(0);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            $table->index(['user_id', 'status', 'published_at']);
            $table->index(['is_featured', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};