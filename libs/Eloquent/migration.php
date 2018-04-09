<?php
require '../../vendor/autoload.php';

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once "config.php";

Capsule::schema()->dropIfExists('users');

Capsule::schema()->create('users', function (Blueprint $table) {
    $table->increments('id');
    $table->string('login', 20);
    $table->string('password');
    $table->text('name');
    $table->integer('age');
    $table->longText('description');
    $table->string('photo');
    // $table->timestamps(); //created_at&updated_at тип datetime
});

for ($i = 0; $i < 10; $i++) {
    $faker = Faker\Factory::create('ru_RU');
    $user = new \App\Users();
    $user->login = $faker->lastName;
    $user->password = crypt($faker->password(6, 10), '$6$rounds=5458$yopta23GDs43yopta$');
    $user->name = $faker->name;
    $user->age = $faker->numberBetween(10, 50);
    $user->description = $faker->text(20);
    $faker->image($dir = '../../photos', $width = 100, $height = 100);
    $user->photo = $faker->image($dir, $width = 100, $height = 100, '', false);
    $user->save();
}



