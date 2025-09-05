<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 出品者
            $table->string('name');
            $table->string('brand')->nullable();
            $table->unsignedInteger('price');
            $table->text('description')->nullable();
            $table->enum('condition', ['良好','やや傷や汚れあり','状態が悪い'])->default('良好');
            $table->string('image_path')->nullable(); // 'images/xxx.jpg' を入れる
            $table->foreignId('buyer_id')->nullable()->constrained('users')->nullOnDelete(); // 買ったユーザー
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
