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
        Schema::table('article_category', function (Blueprint $table) {
            if (!Schema::hasColumn('article_category', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('category_id');
            }

            if (!Schema::hasColumn('article_category', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });

        Schema::table('article_tag', function (Blueprint $table) {
            if (!Schema::hasColumn('article_tag', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('tag_id');
            }

            if (!Schema::hasColumn('article_tag', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_category', function (Blueprint $table) {
            if (Schema::hasColumn('article_category', 'created_at')) {
                $table->dropColumn('created_at');
            }

            if (Schema::hasColumn('article_category', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });

        Schema::table('article_tag', function (Blueprint $table) {
            if (Schema::hasColumn('article_tag', 'created_at')) {
                $table->dropColumn('created_at');
            }

            if (Schema::hasColumn('article_tag', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
};