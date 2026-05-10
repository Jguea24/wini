<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('telefono')->nullable();
            $table->text('direccion')->nullable();
            $table->timestamps();
        });

        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('empresa')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps();

            $table->unique(['nombre', 'empresa']);
        });

        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('fecha');
            $table->decimal('libras', 12, 2);
            $table->decimal('precio_libra', 10, 2);
            $table->decimal('total_pagado', 12, 2);
            $table->timestamps();
        });

        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->date('fecha');
            $table->decimal('libras', 12, 2);
            $table->decimal('precio_por_libra', 10, 2);
            $table->decimal('total', 12, 2);
            $table->string('metodo_pago', 30);
            $table->timestamps();

            $table->index('fecha');
            $table->index('metodo_pago');
        });

        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->date('fecha');
            $table->string('tipo');
            $table->text('descripcion')->nullable();
            $table->decimal('monto', 12, 2);
            $table->timestamps();

            $table->index(['fecha', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('proveedores');
    }
};
