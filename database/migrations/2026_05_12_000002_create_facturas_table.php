<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->unique()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('numero', 30)->unique();
            $table->date('fecha_emision')->index();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('impuesto', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('estado', 30)->default('emitida')->index();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
