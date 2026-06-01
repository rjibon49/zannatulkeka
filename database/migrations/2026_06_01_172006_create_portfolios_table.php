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
            $table->text('bio')->nullable();
            // মিডিয়া লাইব্রেরির সাথে রিলেশন (ছবি ডিলিট হলে এখানে null হয়ে যাবে)
            $table->foreignId('profile_picture_id')->nullable()->constrained('media_libraries')->nullOnDelete();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};