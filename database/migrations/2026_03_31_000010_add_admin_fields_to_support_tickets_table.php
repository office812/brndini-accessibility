<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            if (! Schema::hasColumn('support_tickets', 'admin_response')) {
                $table->text('admin_response')->nullable()->after('message');
            }

            if (! Schema::hasColumn('support_tickets', 'assigned_user_id')) {
                $table->foreignId('assigned_user_id')->nullable()->after('site_id')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            if (Schema::hasColumn('support_tickets', 'assigned_user_id')) {
                $table->dropConstrainedForeignId('assigned_user_id');
            }

            if (Schema::hasColumn('support_tickets', 'admin_response')) {
                $table->dropColumn('admin_response');
            }
        });
    }
};
