<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //user id, on_blog, fromuser, body, timestamp
        Schema::create('comments', function(Blueprint $table)
        {
          $table->increments('id');
          $table->integer('on_post')-> unsigned() -> default(0);
          $table->foreign('on_post')
                ->references('id')->on('posts')
                ->onDelete('cascade');
          $table->text('body');
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
        //drop comments
        Schema::drop('comments');
    }
}
