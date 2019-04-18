<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),

        'nickname' => $faker->name,
        'openid'=> Str::random(10),
        'avatar' => 'https://www.google.com/url?sa=i&rct=j&q=&esrc=s&source=images&cd=&cad=rja&uact=8&ved=2ahUKEwjvy7GYi97gAhXHi1QKHfabBTQQjRx6BAgBEAU&url=http%3A%2F%2Fwww.twoeggz.com%2Fnews%2F10427675.html&psig=AOvVaw12Uj8PNCOUhiXClO6GI0OH&ust=1551431587088034',
        'book_name' => $faker->name,
        'post_id' => rand(0, 40),

    ];
});
