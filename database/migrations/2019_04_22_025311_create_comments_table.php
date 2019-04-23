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

            $table->unsignedInteger('post_id')->default(0)->comment('书评 id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户 id');
            $table->string('user_nickname')->default('')->comment('评论人昵称');
            $table->string('user_avatar')->default('')->comment('评论人头像');
            $table->string('content')->default('')->comment('评论内容');

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
