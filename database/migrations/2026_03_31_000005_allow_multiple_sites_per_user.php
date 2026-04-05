<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            // Must drop the foreign key first, then the unique index
            $table->dropForeign(['user_id']);
            $table->dropUnique('sites_user_id_unique');
            // Re-add foreign key without unique constraint
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }
};
