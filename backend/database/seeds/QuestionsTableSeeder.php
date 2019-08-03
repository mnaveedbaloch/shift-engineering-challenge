<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::table('questions')->insert($this->getQuestionsList());
  }

  private function getQuestionsList() {
    return json_decode(file_get_contents(dirname(__FILE__) . '\questions.json'), true);
  }
}
