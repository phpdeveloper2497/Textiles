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
            $table->foreignId('box_id')->constrained();
            $table->string('name')->unique();
            $table->string('sort_plane')->unique();
            $table->integer('all_products')->default(0);
            $table->integer('finished_products')->default(0);
            $table->integer('defective_products')->default(0);
            $table->integer('not_packaged')->nullable()->default(0);
            $table->string('image_path')->nullable();
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
