<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model {
  /**
   * Get the user record associated with the user.
   */
  public function user() {
    return $this->belongsTo('App\User');
  }
  /**
   * Get the question record associated with the user.
   */
  public function question() {
    return $this->belongsTo('App\Question');
  }
}
