<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('content')->default('')->comment('书评');
            $table->string('book_name')->default('')->comment('书名');
            $table->string('cover')->default('')->comment('封面图');
            $table->unsignedInteger('user_id')->default(0)->comment('评论人 ID');
            $table->string('user_nickname')->default('')->comment('评论人昵称');
            $table->unsignedInteger('likes')->default(0)->comment('点赞数');

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
        Schema::dropIfExists('posts');
    }
}
