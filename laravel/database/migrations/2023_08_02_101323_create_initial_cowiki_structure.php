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

        Schema::create('articles', function (Blueprint $table) {
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
            $table->string('title', 255)->nullable();
            $table->string('description', 400)->nullable();

            $table->string('filename', 255)->nullable();
            $table->string('mime_type', 255)->nullable();
            $table->unsignedBigInteger('filesize')->nullable();
            $table->string('preview_file', 255)->nullable();
            $table->text('source')->nullable();

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
            $table->string('title', 255)->nullable();
            $table->string('description', 400)->nullable();

            $table->text('url');
            $table->string('preview_file', 255)->nullable();

            $table->datetime('crawled_at')->nullable();
            $table->integer('crawled_status')->nullable();

            $table->foreignId('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->foreignId('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->timestamps();
        });

        Schema::create('article_attachments', function (Blueprint $table) {
            $table->foreignId('article_id')->references('id')->on('articles')->onDelete('cascade');
            $table->morphs('attachment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_attachments', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropMorphs('attachment');
        });

        Schema::table('attached_files', function (Blueprint $table) {
            $table->dropForeign(['license_id']);
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::table('attached_urls', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::dropIfExists('attachmed_files');
        Schema::dropIfExists('attachmed_urls');

        Schema::dropIfExists('articles');
        Schema::dropIfExists('licenses');

        Schema::dropIfExists('article_attachments');
    }
};
