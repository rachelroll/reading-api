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

            $table->string('title')->default('')->comment('���������');
            $table->string('company')->default('')->comment('��˾����');
            $table->string('description')->default('')->comment('�����');
            $table->string('date')->default('')->comment('���������');
            $table->string('city')->default('')->comment('���е���');
            $table->string('address')->default('')->comment('��ϸ��ַ');
            $table->string('contact')->default('')->comment('��ϵ��');
            $table->string('phone')->default('')->comment('�ֻ�');
            $table->string('email')->default('')->comment('����');
            $table->string('subject')->default('')->comment('���������');
            $table->string('cover')->default('')->comment('����ͼ');
            $table->unsignedInteger('user_id')->default(0)->comment('�û�ID');

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
