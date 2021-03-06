<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoFactoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_factories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('po_client_id')->unsigned();
            $table->integer('factory_id')->nullable()->unsigned();
            $table->integer('type')->unsigned();
            $table->string('no')->comment('编号自动生成');
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
        Schema::dropIfExists('po_factories');
    }
}
