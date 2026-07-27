<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'show_in_org_chart')) {
                $table->boolean('show_in_org_chart')->default(false)->after('is_active');
            }

            if (! Schema::hasColumn('users', 'org_chart_position')) {
                $table->string('org_chart_position')->nullable()->after('show_in_org_chart');
            }

            if (! Schema::hasColumn('users', 'org_chart_level')) {
                $table->string('org_chart_level')->default('director')->after('org_chart_position');
            }

            if (! Schema::hasColumn('users', 'org_chart_order')) {
                $table->unsignedSmallInteger('org_chart_order')->default(0)->after('org_chart_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['org_chart_order', 'org_chart_level', 'org_chart_position', 'show_in_org_chart'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
