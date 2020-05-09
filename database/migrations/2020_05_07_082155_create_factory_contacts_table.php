<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactoryContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factory_contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('factory_id')->unsigned()->comment('工厂ID');
            $table->string('name')->comment('姓名');
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
        Schema::dropIfExists('factory_contacts');
    }
}
