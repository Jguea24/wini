<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('identificacion', 30)->nullable()->after('empresa');
            $table->string('direccion')->nullable()->after('telefono');
            $table->string('correo')->nullable()->after('direccion');
        });

        Schema::table('facturas', function (Blueprint $table) {
            $table->foreignId('updated_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->foreignId('anulada_by')->nullable()->after('updated_by')->constrained('users')->nullOnDelete();
            $table->timestamp('anulada_at')->nullable()->after('anulada_by');
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('updated_by');
            $table->dropConstrainedForeignId('anulada_by');
            $table->dropColumn('anulada_at');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['identificacion', 'direccion', 'correo']);
        });
    }
};
