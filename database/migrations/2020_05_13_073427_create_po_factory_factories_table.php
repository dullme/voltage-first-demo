<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoFactoryFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_factory_factories', function (Blueprint $table) {
            $table->id();
            $table->integer('po_factory_id')->unsigned();
            $table->integer('factory_id')->nullable()->unsigned();
            $table->string('product')->nullable();
            $table->integer('number')->unsigned()->default(0)->comment('当前版本号');
            $table->longText('remarks')->nullable()->comment('remarks');
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
        Schema::dropIfExists('po_factory_factories');
    }
}
