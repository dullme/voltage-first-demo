<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForwarderContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forwarder_contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('forwarder_id')->unsigned();
            $table->string('name')->comment('姓名');
            $table->string('cn_name')->comment('中文姓名');
            $table->string('position')->nullable()->comment('中文职位');
            $table->string('cn_position')->nullable()->comment('中文职位');
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
        Schema::dropIfExists('forwarder_contacts');
    }
}
