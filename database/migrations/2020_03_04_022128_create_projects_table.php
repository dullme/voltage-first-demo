<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client_id')->unsigned()->comment('客户表ID');
            $table->string('contacts')->nullable()->comment('联系人');
            $table->string('name')->comment('项目名称');
            $table->string('number')->comment('项目编号');
            $table->string('address')->nullable()->comment('地址');
            $table->string('author')->nullable()->comment('创建人');
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
        Schema::dropIfExists('projects');
    }
}
