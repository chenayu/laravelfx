<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddblogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title')->comment('标题');
            $table->longText('content')->comment('内容');
            $table->unsignedInteger('display')->default(0)->comment('浏览量');
            $table->enum('accessable',['public','protected','private'])->comment('权限');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->unsignedInteger('zan')->default(0)->comment('点赞');
            $table->index('user_id');
            $table->engine='innodb';
            $table->comment='日志表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addblogs');
    }
}
