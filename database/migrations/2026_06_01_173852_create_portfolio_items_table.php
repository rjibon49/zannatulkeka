<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            // type এর মধ্যে থাকবে: work_identity, achievement, book, experience, publication
            $table->string('type'); 
            $table->string('title'); // বইয়ের নাম / পদের নাম / অ্যাওয়ার্ডের নাম
            $table->string('subtitle')->nullable(); // পাবলিশার / কোম্পানির নাম
            $table->string('period')->nullable(); // সাল বা সময়কাল
            $table->string('url')->nullable(); // পেপার বা কলামের লিঙ্ক
            $table->text('description')->nullable(); // বিস্তারিত
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};