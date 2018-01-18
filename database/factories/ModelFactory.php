<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'confirmed'=>true,
        'confirmation_token'=>null,
        'password' => $password ?: $password = bcrypt('test12'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Channel::class, function (Faker\Generator $faker) {

    $name=$faker->word;
    return [
        'name'=>$name,  //php , Java , like category
        'slug'=>$name,
    ];
});

$factory->define(App\Thread::class, function (Faker\Generator $faker) {

    $title=$faker->sentence;
    return [
        /*'user_id'=>function(){
            return factory('App\User')->create()->id;
        },*/
        'user_id'=>App\User::all()->random()->id,
        'channel_id'=>App\Channel::all()->random()->id,
        'title'=>$title,
        'slug'=> str_slug($title),
        'body'=>$faker->text,
        'locked'=>false,
    ];
});


$factory->define(App\Reply::class, function (Faker\Generator $faker) {

    return [
        /*'user_id'=>function(){
            return factory('App\User')->create()->id;
        },*/
        'user_id'=>App\User::all()->random()->id,
        'thread_id'=>App\Thread::all()->random()->id,
        'body'=>$faker->text,
    ];
});

//notifications
$factory->define(\Illuminate\Notifications\DatabaseNotificationCollection::class, function (Faker\Generator $faker) {

    return [
       'id'=> \Ramsey\Uuid\Uuid::uuid4()->toString(),
       'type'=>'App\Notifications\ThreadWasUpdated' ,
        'notifiable_id'=>function(){
          return auth()->user->id ?: App\User::random()->id;
        },
        'notifiable_type'=>'App\User',
        'data'=>['foo'=>'bar']
    ];
});