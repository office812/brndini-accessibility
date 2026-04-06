<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop all foreign keys on sites.user_id (handles any naming convention)
        $fks = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'sites'
              AND COLUMN_NAME = 'user_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        foreach ($fks as $fk) {
            DB::statement("ALTER TABLE `sites` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        // Drop unique index if it still exists
        $uniqueIdx = DB::select("SHOW INDEX FROM `sites` WHERE Key_name = 'sites_user_id_unique'");
        if (!empty($uniqueIdx)) {
            DB::statement("ALTER TABLE `sites` DROP INDEX `sites_user_id_unique`");
        }

        // Add a plain index for the FK (if none exists)
        $plainIdx = DB::select("SHOW INDEX FROM `sites` WHERE Key_name = 'sites_user_id_index'");
        if (empty($plainIdx)) {
            DB::statement("ALTER TABLE `sites` ADD INDEX `sites_user_id_index` (`user_id`)");
        }

        // Re-add foreign key without unique constraint (skip if already exists)
        $existingFk = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'sites'
              AND COLUMN_NAME = 'user_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        if (empty($existingFk)) {
            DB::statement("ALTER TABLE `sites` ADD CONSTRAINT `sites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE");
        }
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }
};
