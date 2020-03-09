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
            $table->string('name')->comment('发货批次');
            $table->integer('status')->unsigned()->default(\App\Enums\BatchStatus::InProduction)->comment('状态');
            $table->timestamp('estimated_production_completion')->nullable()->comment('预计生产完成时间');
            $table->timestamp('etd_port')->nullable()->comment('预计离岗时间');
            $table->timestamp('eta_port')->nullable()->comment('预计到港时间');
            $table->timestamp('eta_job_site')->nullable()->comment('预计到项目点时间');
            $table->timestamp('actual_production_completion')->nullable()->comment('实际生产完成时间');
            $table->timestamp('atd_port')->nullable()->comment('实际离岗时间');
            $table->timestamp('ata_port')->nullable()->comment('实际到港时间');
            $table->timestamp('ata_job_site')->nullable()->comment('实际到项目点时间');
            $table->string('carrier')->nullable()->comment('船公司名称');
            $table->string('b_l')->nullable()->comment('提单号码');
            $table->string('vessel')->nullable()->comment('船的编码');
            $table->string('container_no')->nullable()->comment('柜子编码');
            $table->string('remarks')->nullable()->comment('有几个柜子');
            $table->string('shipping_method')->nullable()->comment('运输方式');
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
