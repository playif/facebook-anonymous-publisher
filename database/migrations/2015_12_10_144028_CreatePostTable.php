<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->increments('id');
            $table->string('post_key');
            $table->integer('post_state');
            $table->integer('post_type');
            $table->text('post_message');
            $table->string('post_url');
            $table->string('post_user_ip');
            $table->bigInteger('insert_time');
            $table->bigInteger('publish_time');
            $table->string('facebook_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('post');
    }
}
