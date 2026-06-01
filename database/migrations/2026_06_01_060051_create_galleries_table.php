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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            // মিডিয়া লাইব্রেরির সাথে সঠিক রিলেশন
            $table->foreignId('media_library_id')->constrained('media_libraries')->onDelete('cascade');
            // যদি গ্যালারি আর্টিকেলের সাথে সম্পর্কিত রাখতে চান (ঐচ্ছিক)
            $table->foreignId('article_id')->nullable()->constrained('articles')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};