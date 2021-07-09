<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->unsigned();
            $table->integer('u_s_carriers_id')->unsigned()->nullable();
            $table->decimal('amount')->nullable();
            $table->string('no');
            $table->string('type')->nullable();
            $table->timestamp('eta_job_site')->nullable()->comment('预计到项目点时间');
            $table->text('eta_job_site_history')->nullable()->comment('预计到项目点时间');
            $table->timestamp('ata_job_site')->nullable()->comment('实际到项目点时间');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('containers');
    }
}
