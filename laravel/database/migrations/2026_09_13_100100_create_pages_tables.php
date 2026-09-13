<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('eyebrow')->nullable();
            $table->text('lead')->nullable();
            $table->longText('body')->nullable();
            $table->string('line')->nullable();
            $table->string('image')->nullable();
            $table->string('card_title')->nullable();
            $table->text('card_lead')->nullable();
            $table->string('commitment_title')->nullable();
            $table->string('updated_on', 32)->nullable();
            $table->string('status', 32)->default('published')->index();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('page_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->unique(['page_id', 'key']);
        });

        Schema::create('page_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('group', 64);
            $table->unsignedInteger('sort')->default(0);
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->string('value')->nullable();
            $table->string('suffix')->nullable();
            $table->string('meta')->nullable();
            $table->string('year', 16)->nullable();
            $table->string('role')->nullable();
            $table->string('initials', 8)->nullable();
            $table->string('note')->nullable();
            $table->string('href')->nullable();
            $table->string('link_label')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->index(['page_id', 'group', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_blocks');
        Schema::dropIfExists('page_fields');
        Schema::dropIfExists('pages');
    }
};
