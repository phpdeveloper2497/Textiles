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
            $table->boolean('in_storage');
            $table->boolean('out_storage');
            $table->boolean('returned');
            $table->integer('per_pc_meter');
            $table->integer('pc');
            $table->integer('length')->nullable();
            $table->text('commentary')->nullable();
            $table->softDeletes();
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
