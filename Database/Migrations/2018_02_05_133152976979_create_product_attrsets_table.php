<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttrsetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product__attrsets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your fields
            $table->string('key');
            $table->integer('pid')->default(0);
            $table->integer('sort_order');
            $table->timestamps();
        });
        Schema::create('attrset_attr',function (Blueprint $table){
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('attrset_id')->unsigned()->index();
            $table->integer('attribute_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product__attrsets');
        Schema::dropIfExists('attrset_attr');
    }
}
