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
        Schema::create('box_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('box_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('in_storage');
            $table->integer('out_storage');
            $table->integer('returned');
            $table->integer('per_pc_meter');
            $table->integer('pc');
            $table->integer('length');
            $table->text('commentary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('box_histories');
    }
};