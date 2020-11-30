<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserService
{
    /**
     * Create a new user
     *
     * @param string|NULL $name
     * @param string|NULL $email
     * @param string|NULL $password
     * @param string|NULL $role
     * @return User
     */
    public static function create(string $name = NULL, string $email = NULL, string $password = NULL, string $role = NULL)
    {
        // init Faker
        $faker = Faker::create();

        // create new user
        $user = new User();
        $user->name = $name ?: $faker->name;
        $user->email = $email ?: $faker->safeEmail;
        $user->role = $role ?: User::ROLE_BOT;
        $user->status = User::STATUS_ACTIVE;
        $user->password = Hash::make($password ?: str_random(8));
        $user->remember_token = str_random(10);
        $user->last_login_from = $faker->ipv4;
        $user->last_login_at = Carbon::now();
        $user->save();

        // throw Registered event
        event(new Registered($user));

        return $user;
    }
}
