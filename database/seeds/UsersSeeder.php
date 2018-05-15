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
            'first_name' => env('USER_FIRST_NAME', 'John'),
            'last_name' => env('USER_LAST_NAME', 'Smith'),
            'email' => env('USER_EMAIL', 'mail@somewhere.com'),
            'password' => Hash::make(env('USER_PASSWORD', 'secret')),
        ]);
    }
}
