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
        Schema::create('handkerchief_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('handkerchief_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->boolean('storage_in');
//            $table->integer('all_products')->default(0);
            $table->integer('finished_products')->default(0);
            $table->integer('defective_products')->default(0);
            $table->boolean('sold_out')->default(false);
            $table->integer('sold_products')->default(0);
            $table->integer('sold_defective_products')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handkerchief_histories');
    }
};
