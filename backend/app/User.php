<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

  /**
   * Get the answers for the user.
   */
  public function answers() {
    return $this->hasMany('App\UserAnswer');
  }

  /**
   * Delete all existing user_answers rows from database
   * @return mixed
   */
  public function cleanPreviousAnswers() {
    return $this->answers()->delete();
  }

  /**
   * Map incoming data from view to database model of this user
   * @param array $answeredQuestions array of answers posted from frontend
   */
  public function saveAnswers(array $answeredQuestions) {
    $userAnswers = [];
    foreach ($answeredQuestions as $answer) {
      $userAnswer = new \App\UserAnswer;
      $userAnswer->question_id = $answer['q_id'];
      $userAnswer->choosen_number = $answer['choosen_number'];
      $userAnswers[] = $userAnswer;
    }
    return $this->answers()->saveMany($userAnswers);
  }

  /**
   * Add new user and answers or update existing user by deleting existing answers rows
   * @param string $email
   * @param array $answers array of answers posted from frontend
   */
  public static function addOrUpdateUserWithAnswers(string $email, array $answers) {
    $user = \App\User::where('email', $email)->first();
    if (empty($user)) {
      $user = new \App\User;
      $user->email = $email;
      $user->save();
    } else {
      /**
       * If user exits, clear previous answers before inserting new ones
       */
      $user->cleanPreviousAnswers();
    }
    $user->saveAnswers($answers);
  }
}
