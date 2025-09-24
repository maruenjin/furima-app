<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();   
            $table->foreignId('product_id')->constrained()->cascadeOnDelete(); 
            $table->unsignedInteger('amount'); 
            $table->string('payment_method', 20); 
            $table->string('shipping_postcode', 20);
            $table->string('shipping_address', 255);
            $table->string('shipping_building', 255)->nullable();
            $table->timestamps();
            $table->unique('product_id'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
