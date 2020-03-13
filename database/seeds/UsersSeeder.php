<?php

use App\Entities\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate([
            'first_name' => config('users.test.USER_FIRST_NAME'),
            'last_name' => config('users.test.USER_LAST_NAME'),
            'email' => config('users.test.USER_EMAIL'),
            'password' => Hash::make(config('users.test.USER_PASSWORD')),
        ]);
    }
}
