<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsColumnsForListing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
           
            $table->string('condition', 32)->change();

            
            $table->json('categories')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            
            $table->text('condition')->change();
            $table->text('categories')->nullable()->change();
        });
    }
};  