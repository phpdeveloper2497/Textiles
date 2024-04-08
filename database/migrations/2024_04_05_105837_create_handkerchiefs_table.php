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
        Schema::create('handkerchiefs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('sort_plane')->unique();
            $table->string('all_products')->default(0);
            $table->string('finished_products')->default(0);
            $table->string('defective_products')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handkerchiefs');
    }
};
