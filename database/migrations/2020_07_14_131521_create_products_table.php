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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->nullable();

            $table->integer('price')->unsigned();
            $table->integer('discount_price')->unsigned()->nullable();

            $table->text('short_description')->nullable();
            $table->mediumText('description')->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('priority')->nullable();
            $table->integer('weight')->unsigned()->nullable();
            $table->string('size')->nullable();

            $table->string('image')->nullable();

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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('products');
    }
}
