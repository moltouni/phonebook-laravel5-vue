<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = [
            [
                'id' => 1,
                'name' => 'Postman - Password Grant Client',
                'secret' => config('oauth.POSTMAN_CLIENT_SECRET'),
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
            ],
        ];

        DB::table('oauth_clients')->insert($clients);

    }
}
