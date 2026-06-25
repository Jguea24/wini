<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cocoa_market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 60)->default('investing');
            $table->string('symbol', 80)->default('US Cocoa');
            $table->decimal('price', 12, 2);
            $table->decimal('change_value', 12, 2)->nullable();
            $table->decimal('change_percent', 8, 4)->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('unit', 40)->default('tonelada');
            $table->timestamp('quoted_at');
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->index(['provider', 'symbol', 'quoted_at']);
            $table->index('quoted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cocoa_market_prices');
    }
};
