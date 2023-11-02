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
        Schema::create('access_lists', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['page', 'request'])->default('page');
            $table->unsignedInteger('parent');
            $table->smallInteger('order')->default(1);
            $table->string('icon')->default('uil-label-alt');
            $table->string('name')->default('Home');
            $table->string('link')->default('#');
            $table->enum('child', ['1', '0'])->default('1');
            $table->enum('active', ['1', '0'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_lists');
    }
};
