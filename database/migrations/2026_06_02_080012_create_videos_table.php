<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();

            // Optional: কোনো article-এর সাথে video connect করতে চাইলে
            $table->foreignId('article_id')
                ->nullable()
                ->constrained('articles')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('description')->nullable();

            // Full YouTube URL
            $table->string('video_url');

            // Example: https://www.youtube.com/watch?v=ABC123 হলে এখানে ABC123 থাকবে
            $table->string('youtube_video_id')->nullable();

            $table->foreignId('thumbnail_media_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->index(['status', 'sort_order']);
            $table->index('article_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};