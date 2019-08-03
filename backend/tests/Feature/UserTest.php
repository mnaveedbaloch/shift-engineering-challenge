<?php

namespace Tests\Feature;

use App\User;
use App\UserAnswer;
use DB;
use Tests\TestCase;

class UserTest extends TestCase {

  protected function setUp(): void {
    parent::setUp();
    $this->prepareQuestions();
  }

  /**
   * @var array
   */
  private $testCasesUserInputs = [
    [4, 3, 1, 6, 7, 3, 5, 3, 6, 6],
    [1, 5, 4, 6, 5, 2, 6, 3, 3, 2],
    [3, 2, 6, 1, 7, 3, 2, 5, 2, 7],
    [3, 4, 7, 1, 2, 5, 4, 3, 2, 6],
    [4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
    [1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
    [7, 7, 7, 7, 7, 7, 7, 7, 7, 7],
  ];

  public function testCaseA() {
    $email = 'testa@check.com';
    $this->performTest($email, 0, 'ENTP');
  }

  public function testCaseB() {
    $email = 'testb@check.com';
    $this->performTest($email, 1, 'ESTJ');
  }

  public function testCaseD() {
    $email = 'testd@check.com';
    $this->performTest($email, 2, 'INFP');
  }
  public function testCaseE() {
    $email = 'teste@check.com';
    $this->performTest($email, 3, 'ISFP');
  }
  public function testCaseF() {
    $email = 'testf@check.com';
    $this->performTest($email, 4, 'ESTJ');
  }
  public function testCaseG() {
    $email = 'testg@check.com';
    $this->performTest($email, 5, 'ISTJ');
  }
  public function testCaseH() {
    $email = 'testh@check.com';
    $this->performTest($email, 6, 'ESTP');
  }

  /**
   * @param $email
   * @param $index
   * @param $expectedPerception
   */
  private function performTest($email, $index, $expectedPerception) {
    $this->prepareData($email, $index);
    $this->assertDatabaseHas(with(new User)->getTable(), [
      'email' => $email,
    ]);
    $response = $this->get('/api/user/perspective?email=' . $email);
    $response
      ->assertStatus(200)
      ->assertJson([
        'perspective' => $expectedPerception,
      ]);
  }

  /**
   * @param $email
   * @param $userInputIndex
   */
  private function prepareData($email, $userInputIndex) {
    $user = new User;
    $user->email = $email;
    $answers = [];
    $questionId = 1;
    foreach ($this->testCasesUserInputs[$userInputIndex] as $choosen_number) {
      $answer = new UserAnswer;
      $answer->choosen_number = $choosen_number;
      $answer->question_id = $questionId;
      $answers[] = $answer;
      $questionId++;
    }
    $user->save();
    $user->answers()->saveMany($answers);
    /**
     * Assert that userAnswers are saved in database
     */
    $this->assertEquals(count($user->answers), 10, 'Answers saved in database');
  }

  private function prepareQuestions() {
    DB::table('questions')->insert($this->getQuestionsList());
  }

  private function getQuestionsList() {
    return json_decode(file_get_contents(dirname(__FILE__) . '/../../database/seeds/questions.json'), true);
  }
}
