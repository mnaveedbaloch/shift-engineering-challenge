<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('questions', function (Blueprint $table) {
      $table->increments('id');
      $table->string('description', 500);
      $table->tinyInteger('direction');
      $table->string('meaning', 1);
      $table->enum('dimention', ['EI', 'SN', 'TF', 'JP']);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('questions');
  }
}
