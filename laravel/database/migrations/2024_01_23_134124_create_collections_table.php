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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();

            $table->integer('order')->nullable();
            $table->boolean('featured')->default(false);

            $table->foreignId('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->foreignId('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->timestamps();
        });

        Schema::create('article_collection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id');
            $table->foreignId('collection_id');

            $table->integer('order')->nullable();

            $table->foreignId('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->foreignId('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::table('article_collection', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropForeign(['collection_id']);
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::dropIfExists('collections');
        Schema::dropIfExists('article_collection');
    }
};
