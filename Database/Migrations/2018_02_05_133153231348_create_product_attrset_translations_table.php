<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttrsetTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product__attrset_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields
            $table->string('name');

            $table->integer('attrset_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['attrset_id', 'locale']);
            $table->foreign('attrset_id')->references('id')->on('product__attrsets')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product__attrset_translations', function (Blueprint $table) {
            $table->dropForeign(['attrset_id']);
        });
        Schema::dropIfExists('product__attrset_translations');
    }
}
