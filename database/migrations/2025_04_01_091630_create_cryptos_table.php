<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cryptos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol')->unique();
            $table->decimal('price', 20, 8);
            $table->decimal('market_cap', 20, 2);
            $table->decimal('percent_change_24h', 10, 2);
            $table->decimal('ath', 20, 8);
            $table->decimal('atl', 20, 8);
            $table->json('chart')->nullable();
            $table->timestamps();
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cryptos');
    }
};
