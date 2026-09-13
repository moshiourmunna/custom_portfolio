<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('tagline')->nullable();
            $table->string('website')->nullable();
            $table->string('canonical_base')->nullable();
            $table->text('footer_blurb')->nullable();
            $table->string('hours')->nullable();
            $table->string('office_address')->nullable();
            $table->string('mill_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('office_pin')->nullable();
            $table->string('mill_pin')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('logo')->nullable();
            $table->string('logo_dark')->nullable();
            $table->string('favicon')->nullable();
            $table->string('theme_primary', 16)->default('#004d40');
            $table->string('theme_deep', 16)->default('#003d33');
            $table->string('theme_accent', 16)->default('#548c84');
            $table->string('theme_surface', 16)->default('#f4f7f6');
            $table->string('ga')->nullable();
            $table->string('gtm')->nullable();
            $table->string('meta_pixel')->nullable();
            $table->string('meta_domain')->nullable();
            $table->string('gsc')->nullable();
            $table->string('bing')->nullable();
            $table->boolean('maintenance')->default(false);
            $table->timestamps();
        });

        Schema::create('setting_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_id')->constrained()->cascadeOnDelete();
            $table->string('kind', 16);
            $table->string('value');
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
            $table->index(['setting_id', 'kind']);
        });

        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('url')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
            $table->unique(['setting_id', 'label']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
        Schema::dropIfExists('setting_contacts');
        Schema::dropIfExists('settings');
    }
};
