<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('site_name')->default('Zannatul Keka');
            $table->string('site_title')->nullable();
            $table->text('site_description')->nullable();

            $table->foreignId('logo_media_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->foreignId('favicon_media_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->foreignId('banner_media_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->string('heading')->nullable();
            $table->string('subheading')->nullable();

            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();

            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('github_url')->nullable();

            $table->string('default_meta_title')->nullable();
            $table->text('default_meta_description')->nullable();

            $table->foreignId('default_og_media_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->text('footer_text')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};