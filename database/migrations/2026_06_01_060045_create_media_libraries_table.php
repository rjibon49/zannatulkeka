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
        Schema::create('media_libraries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('file_name');
            $table->string('original_name')->nullable();
            $table->string('file_path');
            $table->string('file_url')->nullable();

            $table->string('mime_type')->nullable();
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->text('description')->nullable();

            $table->enum('type', [
                'image',
                'document',
                'video',
                'audio',
                'other',
            ])->default('image');

            $table->enum('disk', [
                'public',
                'local',
            ])->default('public');

            $table->timestamps();

            $table->index(['type', 'disk']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_libraries');
    }
};