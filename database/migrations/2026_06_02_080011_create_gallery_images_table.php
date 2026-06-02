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
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gallery_id')
                ->constrained('galleries')
                ->cascadeOnDelete();

            $table->foreignId('media_library_id')
                ->constrained('media_libraries')
                ->cascadeOnDelete();

            $table->string('title')->nullable();
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->index(['gallery_id', 'status', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
    }
};