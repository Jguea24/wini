<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
        });

        Schema::table('clientes', function (Blueprint $table) {
            if (! Schema::hasColumn('clientes', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('telefono')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('clientes', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('clientes', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('ventas', function (Blueprint $table) {
            if (! Schema::hasColumn('ventas', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('ventas', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('ventas', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->after('updated_by')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('ventas', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('gastos', function (Blueprint $table) {
            if (! Schema::hasColumn('gastos', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('gastos', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('gastos', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->after('updated_by')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('gastos', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
