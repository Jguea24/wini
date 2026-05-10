<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (! Schema::hasColumn('clientes', 'empresa')) {
                $table->string('empresa')->nullable()->after('nombre');
            }
        });

        Schema::table('ventas', function (Blueprint $table) {
            if (Schema::hasColumn('ventas', 'precio_libra') && ! Schema::hasColumn('ventas', 'precio_por_libra')) {
                $table->renameColumn('precio_libra', 'precio_por_libra');
            }

            if (Schema::hasColumn('ventas', 'total_vendido') && ! Schema::hasColumn('ventas', 'total')) {
                $table->renameColumn('total_vendido', 'total');
            }
        });

        Schema::table('ventas', function (Blueprint $table) {
            if (! Schema::hasColumn('ventas', 'metodo_pago')) {
                $table->string('metodo_pago', 30)->default('efectivo')->after('total');
            }

            if (! Schema::hasColumn('ventas', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('cliente_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            }
        });

        Schema::table('gastos', function (Blueprint $table) {
            if (! Schema::hasColumn('gastos', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            }
        });

        $fallbackUserId = User::query()->value('id');

        if ($fallbackUserId !== null) {
            DB::table('ventas')->whereNull('user_id')->update(['user_id' => $fallbackUserId]);
            DB::table('gastos')->whereNull('user_id')->update(['user_id' => $fallbackUserId]);
        }

    }

    public function down(): void
    {
        Schema::table('gastos', function (Blueprint $table) {
            if (Schema::hasColumn('gastos', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });

        Schema::table('ventas', function (Blueprint $table) {
            if (Schema::hasColumn('ventas', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            if (Schema::hasColumn('ventas', 'metodo_pago')) {
                $table->dropColumn('metodo_pago');
            }
        });
    }
};
