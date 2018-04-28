<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSkuTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product__sku_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('sku_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['sku_id', 'locale']);
            $table->foreign('sku_id')->references('id')->on('product__skus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product__sku_translations', function (Blueprint $table) {
            $table->dropForeign(['sku_id']);
        });
        Schema::dropIfExists('product__sku_translations');
    }
}
