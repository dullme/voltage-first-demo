<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoFactoryHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_factory_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('po_factory_id')->unsigned();
            $table->integer('po_client_id')->unsigned();
            $table->integer('factory_id')->unsigned();
            $table->integer('type')->unsigned();
            $table->string('no')->comment('编号自动生成');
            $table->integer('number')->unsigned()->comment('当前版本号');
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
        Schema::dropIfExists('po_factory_histories');
    }
}
