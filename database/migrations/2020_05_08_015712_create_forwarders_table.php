<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForwardersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forwarders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('公司名称');
            $table->string('cn_name')->comment('中文公司名称');
            $table->string('agent_name')->comment('代理商');
            $table->string('cn_agent_name')->comment('中文代理商');
            $table->string('address')->comment('公司地址');
            $table->string('cn_address')->comment('中文公司地址');
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
        Schema::dropIfExists('forwarders');
    }
}
