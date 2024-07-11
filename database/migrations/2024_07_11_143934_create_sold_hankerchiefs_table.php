<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sold_hankerchiefs', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->integer('address');
            $table->integer('sold_products');
            $table->integer('sold_defective_products');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('handkerchief_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_hankerchiefs');
    }
};
