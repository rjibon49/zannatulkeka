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
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // কে পোস্ট করেছে
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // কোন ক্যাটাগরি
                
                $table->string('title');
                $table->string('subtitle')->nullable();
                $table->string('slug')->unique();
                $table->longText('description'); // Rich Text Editor 
                
                // যদি মিডিয়া টেবিল না থাকে, তবে আপাতত সাধারণ ইমেজ পাথ রাখার জন্য:
                $table->string('featured_image')->nullable(); 
                
                // আর যদি মিডিয়া টেবিল সত্যিই থেকে থাকে, তবে আপনার আগের লাইনটিই রাখবেন:
                // $table->foreignId('featured_media_id')->nullable()->constrained('media_libraries')->onDelete('set null');
                
                $table->string('video_url')->nullable();
                $table->string('status')->default('draft'); // published, draft
                $table->timestamp('published_at')->nullable();
                
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->unsignedBigInteger('views_count')->default(0);
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
