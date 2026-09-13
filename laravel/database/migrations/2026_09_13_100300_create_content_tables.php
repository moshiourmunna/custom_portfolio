<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('lead')->nullable();
            $table->longText('body')->nullable();
            $table->string('cover')->nullable();
            $table->string('category', 64)->nullable();
            $table->date('published_on')->nullable();
            $table->string('status', 32)->default('published')->index();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('career_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('location')->nullable();
            $table->string('department', 64)->nullable();
            $table->string('employment_type', 32)->nullable();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('status', 32)->default('open')->index();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_job_id')->nullable()->constrained('career_jobs')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->string('cv_path')->nullable();
            $table->timestamps();
            $table->index('email');
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('interest')->nullable();
            $table->text('message')->nullable();
            $table->string('status', 32)->default('new')->index();
            $table->text('note')->nullable();
            $table->string('image')->nullable();
            $table->string('attachment_path')->nullable();
            $table->date('received_on')->nullable();
            $table->timestamps();
        });

        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('career_jobs');
        Schema::dropIfExists('posts');
    }
};
