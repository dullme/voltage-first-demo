<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUSCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_s_carriers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('公司名称');
            $table->string('address')->nullable()->comment('公司地址');
            $table->string('agent_name')->nullable()->comment('代理商');
            $table->string('position')->nullable()->comment('职位');
            $table->string('tel')->nullable()->comment('电话');
            $table->string('email')->nullable()->comment('邮箱');
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
        Schema::dropIfExists('u_s_carriers');
    }
}
