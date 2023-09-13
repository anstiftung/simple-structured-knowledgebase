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

        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description');
            $table->string('filename', 255)->nullable();
            $table->text('source');

            $table->foreignId('license_id')->nullable();
            $table->foreign('license_id')->references('id')->on('licenses');

            $table->foreignId('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->foreignId('updated_by_id')->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users');

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

        Schema::create('collections', function (Blueprint $table) {
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

        Schema::create('ingredient_recipe', function (Blueprint $table) {
            $table->foreignId('ingredient_id');
            $table->foreignId('recipe_id');
            $table->foreign('ingredient_id')->references('id')->on('ingredients');
            $table->foreign('recipe_id')->references('id')->on('recipes');
        });

        Schema::create('collection_recipe', function (Blueprint $table) {
            $table->foreignId('collection_id');
            $table->foreignId('recipe_id');
            $table->foreign('collection_id')->references('id')->on('collections');
            $table->foreign('recipe_id')->references('id')->on('recipes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_recipe', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->dropForeign(['recipe_id']);
        });

        Schema::table('ingredient_recipe', function (Blueprint $table) {
            $table->dropForeign(['ingredient_id']);
            $table->dropForeign(['recipe_id']);
        });

        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropForeign(['license_id']);
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['updated_by_id']);
        });

        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('licenses');

        Schema::dropIfExists('ingredient_recipe');
        Schema::dropIfExists('collection_recipe');
    }
};
