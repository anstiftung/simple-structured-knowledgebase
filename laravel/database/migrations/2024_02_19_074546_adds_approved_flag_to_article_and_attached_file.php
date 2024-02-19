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
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('approved')->default(false)->after('state_id');
        });

        Schema::table('attached_files', function (Blueprint $table) {
            $table->boolean('approved')->default(false)->after('license_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('approved');
        });

        Schema::table('attached_files', function (Blueprint $table) {
            $table->dropColumn('approved');
        });
    }
};
