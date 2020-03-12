<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_clients', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id')->unsigned()->comment('项目ID');
            $table->string('no')->nullable()->comment('客户订单编号');
            $table->timestamp('client_delivery_time')->nullable()->comment('客户要求到货时间');
            $table->timestamp('po_date')->nullable()->comment('客户下单日期');
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
        Schema::dropIfExists('po_clients');
    }
}
