<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('category');
            $table->decimal('stock', 10, 2);
            $table->string('unit');
            $table->decimal('price', 12, 2);
            $table->string('supplier');
            $table->decimal('minimum_stock', 10, 2);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};
