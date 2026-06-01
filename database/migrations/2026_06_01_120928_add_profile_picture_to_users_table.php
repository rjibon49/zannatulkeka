<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // মিডিয়া লাইব্রেরির সাথে রিলেশন তৈরি করা হলো
            $table->foreignId('profile_picture_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('media_libraries')
                  ->nullOnDelete(); 
                  // nullOnDelete() এর মানে হলো মিডিয়া থেকে ছবি ডিলিট হয়ে গেলেও ইউজার ডিলিট হবে না, শুধু ইউজারের ছবির ঘরটি ফাঁকা (null) হয়ে যাবে।
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_picture_id']);
            $table->dropColumn('profile_picture_id');
        });
    }
};