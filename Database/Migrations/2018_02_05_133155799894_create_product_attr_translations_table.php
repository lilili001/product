<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttrTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product__attr_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('attr_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['attr_id', 'locale']);
            $table->foreign('attr_id')->references('id')->on('product__attrs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product__attr_translations', function (Blueprint $table) {
            $table->dropForeign(['attr_id']);
        });
        Schema::dropIfExists('product__attr_translations');
    }
}
