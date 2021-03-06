<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('po_factory_id')->unsigned()->comment('Po Factory ID');
            $table->integer('ocean_forwarder')->unsigned()->nullable();
            $table->integer('inland_forwarder')->unsigned()->nullable();
            $table->integer('china_inland_forwarder')->unsigned()->nullable();
            $table->string('name')->nullable()->comment('发货批次');
            $table->integer('sequence')->unsigned()->comment('序号');
            $table->integer('status')->unsigned()->default(\App\Enums\BatchStatus::InProduction)->comment('状态');

            $table->timestamp('estimated_production_completion')->nullable()->comment('预计生产完成时间');
            $table->timestamp('etd_port')->nullable()->comment('预计离岗时间');
            $table->timestamp('eta_port')->nullable()->comment('预计到港时间');
            $table->timestamp('eta_job_site')->nullable()->comment('预计到项目点时间');
            $table->text('epc_history')->nullable()->comment('预计生产完成时间');
            $table->text('etd_port_history')->nullable()->comment('预计离岗时间');
            $table->text('eta_port_history')->nullable()->comment('预计到港时间');

            $table->timestamp('actual_production_completion')->nullable()->comment('实际生产完成时间');
            $table->timestamp('atd_port')->nullable()->comment('实际离岗时间');
            $table->timestamp('ata_port')->nullable()->comment('实际到港时间');
            $table->timestamp('ata_job_site')->nullable()->comment('实际到项目点时间');
            $table->string('apc_remarks')->nullable()->comment('实际完成的备注');
            $table->string('atd_remarks')->nullable()->comment('实际离岗的备注');
            $table->string('ata_remarks')->nullable()->comment('实际到港的备注');
            $table->string('carrier')->nullable()->comment('船公司名称');
            $table->string('b_l')->nullable()->comment('提单号码');
            $table->string('vessel')->nullable()->comment('船的编码');
            $table->string('remarks')->nullable()->comment('有几个柜子');
            $table->string('shipping_method')->nullable()->comment('运输方式');
            $table->decimal('rmb', 10, 2)->nullable()->comment('人民币');
            $table->decimal('foreign_currency', 10, 2)->nullable()->comment('外币');
            $table->integer('foreign_currency_type')->nullable()->comment('外币类型');
            $table->string('port_of_departure')->nullable()->comment('启运港口');
            $table->string('destination_port')->nullable()->comment('目的地港口');
            $table->string('invoice_no')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('shipping_ate')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('batches');
    }
}
