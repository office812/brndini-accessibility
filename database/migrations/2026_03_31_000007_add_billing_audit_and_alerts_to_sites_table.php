<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->json('billing_settings')->nullable()->after('purchase_url');
            $table->json('audit_snapshot')->nullable()->after('billing_settings');
            $table->json('alert_settings')->nullable()->after('audit_snapshot');
            $table->timestamp('license_expires_at')->nullable()->after('alert_settings');
            $table->timestamp('last_audited_at')->nullable()->after('license_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn([
                'billing_settings',
                'audit_snapshot',
                'alert_settings',
                'license_expires_at',
                'last_audited_at',
            ]);
        });
    }
};
