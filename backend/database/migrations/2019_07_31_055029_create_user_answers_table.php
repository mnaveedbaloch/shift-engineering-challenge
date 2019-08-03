<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAnswersTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('user_answers', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id');
      $table->integer('question_id');
      $table->integer('choosen_number');
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('question_id')->references('id')->on('questions');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('user_answers');
  }
}
