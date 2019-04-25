<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfflinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offlines', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title')->default('')->comment('读书会名称');
            $table->string('company')->default('')->comment('公司名称');
            $table->string('description')->default('')->comment('活动介绍');
            $table->string('date')->default('')->comment('读书会日期');
            $table->string('city')->default('')->comment('城市地区');
            $table->string('address')->default('')->comment('详细地址');
            $table->string('contact')->default('')->comment('联系人');
            $table->string('phone')->default('')->comment('手机');
            $table->string('email')->default('')->comment('邮箱');
            $table->string('subject')->default('')->comment('读书会主题');
            $table->string('cover')->default('')->comment('封面图');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');

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
        Schema::dropIfExists('offlines');
    }
}
