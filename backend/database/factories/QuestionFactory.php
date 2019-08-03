<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
  return [
    'description' => $faker->sentence(),
    'meaning' => 'E',
    'direction' => -1,
    'dimention' => 'EI',
  ];
});
