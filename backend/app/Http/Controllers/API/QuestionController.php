<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class QuestionController extends Controller {
  /**
   * Get list of the resource
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    return ['questions' => \App\Question::select('description', 'id')->get()];
  }
}
