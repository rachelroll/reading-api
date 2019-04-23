<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('post_id')->default(0)->comment('���� id');
            $table->unsignedInteger('user_id')->default(0)->comment('�û� id');
            $table->string('user_nickname')->default('')->comment('�������ǳ�');
            $table->string('user_avatar')->default('')->comment('������ͷ��');
            $table->string('content')->default('')->comment('��������');

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
        Schema::dropIfExists('comments');
    }
}
