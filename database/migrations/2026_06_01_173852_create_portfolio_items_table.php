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

            $table->foreignId('portfolio_id')
                ->nullable()
                ->constrained('portfolios')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Type Examples
            |--------------------------------------------------------------------------
            | work_identity, education, experience, skill, service, project,
            | achievement, award, book, publication, certificate, social_link
            */
            $table->enum('type', [
                'work_identity',
                'education',
                'experience',
                'skill',
                'service',
                'project',
                'achievement',
                'award',
                'book',
                'publication',
                'certificate',
                'social_link'
            ]);

            $table->string('title');
            $table->string('subtitle')->nullable();

            $table->string('organization_name')->nullable();
            $table->string('location')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('period')->nullable();

            $table->string('url')->nullable();

            $table->foreignId('media_library_id')
                ->nullable()
                ->constrained('media_libraries')
                ->nullOnDelete();

            $table->longText('description')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->index(['portfolio_id', 'type', 'status', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};