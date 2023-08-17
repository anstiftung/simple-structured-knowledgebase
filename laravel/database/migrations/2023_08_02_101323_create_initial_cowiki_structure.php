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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->string('slug',255)->unique();
            $table->text('description');
            $table->string('filename', 255);
            $table->text('source');
            /** ENUM */
            $table->string('status');
            /** end ENUM */
            /** ENUM */
            $table->string('license');
            /** end ENUM */
            $table->timestamps();
        });

        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->string('slug',255)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->string('slug',255)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('collection_recipe', function (Blueprint $table) {
            $table->foreignId('collection_id');
            $table->foreignId('recipe_id');
            $table->foreign('collection_id')->references('id')->on('collections');
            $table->foreign('recipe_id')->references('id')->on('recipes');
        });

        Schema::create('ingredient_recipe', function (Blueprint $table) {
            $table->foreignId('ingredient_id');
            $table->foreignId('recipe_id');
            $table->foreign('ingredient_id')->references('id')->on('ingredients');
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

        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('collections');

        Schema::dropIfExists('ingredient_recipe');
        Schema::dropIfExists('collection_recipe');
    }
};
