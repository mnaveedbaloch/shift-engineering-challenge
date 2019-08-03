<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class UserAnswerController extends Controller {
  /**
   * Store incoming bulk answers in database
   *
   * @param  \Illuminate\Http\Request  $request
   */
  public function storeBulk(Request $request) {
    $validator = $this->validateStoreBulkRequest($request);
    if ($validator->fails()) {
      return [
        'success' => false,
        'errors' => $validator->errors()->all(),
      ];
    }

    \App\User::addOrUpdateUserWithAnswers($request->email, $request->answeredQuestions);
    return [
      'success' => true,
    ];
  }

  /**
   * @param Request $request
   */
  private function validateStoreBulkRequest(Request $request) {
    return Validator::make($request->all(), [
      'answeredQuestions' => 'required|array',
      'email' => 'required|email',
    ]);
  }
}
