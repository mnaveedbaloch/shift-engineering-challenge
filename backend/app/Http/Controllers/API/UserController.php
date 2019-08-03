<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller {
  /**
   * @param Request $request
   */
  public function getPerspective(Request $request) {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|exists:users,email',
    ]);

    if ($validator->fails()) {
      return $this->buildFailureResponse($validator->errors()->all());
    }

    $user = $user = \App\User::where('email', $request->email)
      ->with(['answers:user_id,choosen_number,question_id,id', 'answers.question:dimention,id,meaning,direction'])
      ->first();

    $answersGroupedByDimention = [];
    foreach ($user->answers as $answer) {
      if (!isset($answersGroupedByDimention[$answer->question->dimention])) {
        $answersGroupedByDimention[$answer->question->dimention] = [];
      }
      $answersGroupedByDimention[$answer->question->dimention][] = $answer;
    }
    $perspectiveDimentions = [];

    foreach ($answersGroupedByDimention as $dimention => $answers) {
      $perspectiveDimentions[] = [
        'dimention' => $dimention,
        'direction' => $this->getDimentionPerspective($answers, $dimention),
      ];
    }
    $perspective = '';
    foreach ($perspectiveDimentions as $pDimention) {
      $dimentionComponents = str_split($pDimention['dimention']);
      $perspective .= $pDimention['direction'];
    }
    return ['dimentions' => $perspectiveDimentions, 'perspective' => $perspective];
  }

  /**
   * Calculate the final component of a dimention based on the user answers
   * @param array $answers App\UserAnswers with App\Question object
   * @param $dimention
   * @return string
   */
  private function getDimentionPerspective(array $answers, string $dimention) {
    // split the dimention string into single character array
    $dimentionComponents = str_split($dimention);

    /**
     * Associative array where key is the dimention component and value is the weightage of the component
     */
    $weightageByDimentionComponent = [
      $dimentionComponents[0] => 0,
      $dimentionComponents[1] => 0,
    ];

    foreach ($answers as $answer) {
      // undefined dimention component, i.e the direction and meaning not defined for this component
      $undefinedComponent = $dimentionComponents[0] === $answer->question->meaning
      ? $dimentionComponents[1]
      : $dimentionComponents[0];

      // 4 is a neutral response, should not be added in the weightage
      if ($answer->choosen_number == 4) {
        continue;
      }

      /**
       * Weightage value is calculated based on how far the choosen_number is from the neutral point i.e 4
       * if,
       * choosen_number = 7, Weightage value = 3
       * choosen_number = 1, Weightage value = 3
       */
      $weightageValue = $answer->choosen_number > 4 ? $answer->choosen_number - 4 : 4 - $answer->choosen_number;
      if ($answer->choosen_number > 4) {
        $weightageByDimentionComponent[$answer->question->meaning] += $weightageValue;
      } else {
        $weightageByDimentionComponent[$undefinedComponent] += $weightageValue;
      }
    } // end of foreach

    $perspective = ''; // dimention component

    /**
     * In case of neutral responses for all questions in a dimention each 'weightageByDimentionComponent' would be zero
     * and first component of the dimention would be selected
     */
    if ($weightageByDimentionComponent[$dimentionComponents[0]] === 0
      && $weightageByDimentionComponent[$dimentionComponents[1]] === 0) {
      $perspective = $dimentionComponents[0];
    } else {
      arsort($weightageByDimentionComponent); // sort the associative array on value descending order
      $perspective = array_keys($weightageByDimentionComponent)[0]; // after sort, bigger values would be on top of array

    }

    return $perspective;
  }

  /**
   * @param $errors
   */
  private function buildFailureResponse($errors) {
    return [
      'success' => false,
      'errors' => $errors,
    ];
  }
}
