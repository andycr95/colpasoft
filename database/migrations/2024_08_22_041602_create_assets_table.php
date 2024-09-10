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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity');
            $table->string('code');
            $table->string('area');
            $table->unsignedBigInteger('category_id')->nullable()->constrained();
            $table->unsignedBigInteger('company_id')->nullable()->constrained();
            $table->string('brand');
            $table->string('model');
            $table->string('serie');
            $table->string('state');
            $table->string('status');
            $table->string('performance');
            $table->integer('age');
            $table->integer('usefulLife');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
