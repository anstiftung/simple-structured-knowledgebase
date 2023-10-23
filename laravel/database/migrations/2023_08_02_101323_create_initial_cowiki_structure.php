<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });

        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();

            $table->foreignId('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->foreignId('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->timestamps();
        });

        Schema::create('attached_files', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('description', 400)->nullable();

            $table->string('filename', 255)->nullable();
            $table->string('preview_file', 255)->nullable();
            $table->text('source');

            $table->foreignId('license_id')->nullable();
            $table->foreign('license_id')->references('id')->on('licenses');

            $table->foreignId('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->foreignId('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->timestamps();
        });

        Schema::create('attached_urls', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('description', 400)->nullable();

            $table->text('url');
            $table->string('preview_file', 255)->nullable();

            $table->datetime('crawled_at');
            $table->integer('crawled_status');

            $table->foreignId('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->foreignId('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->timestamps();
        });

        Schema::create('recipe_attachments', function (Blueprint $table) {
            $table->foreignId('recipe_id');
            $table->morphs('attachment');
            $table->foreign('recipe_id')->references('id')->on('recipes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipe_attachments', function (Blueprint $table) {
            $table->dropForeign(['recipe_id']);
            $table->dropMorphs('attachment');
        });

        Schema::table('attachmed_files', function (Blueprint $table) {
            $table->dropForeign(['license_id']);
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::table('attachmed_urls', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::dropIfExists('attachmed_files');
        Schema::dropIfExists('attachmed_urls');

        Schema::dropIfExists('recipes');
        Schema::dropIfExists('licenses');

        Schema::dropIfExists('recipe_attachments');
    }
};
