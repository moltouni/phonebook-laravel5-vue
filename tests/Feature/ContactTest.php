<?php

namespace Tests\Feature;

use App\Entities\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Log;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Entities\Contact;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ContactTest extends TestCase
{

    use RefreshDatabase;

    public function testDestory() {

        $user = factory(User::class, 1)->create()->first();
        $contact = factory(Contact::class, 1)->create()->first();
        Passport::actingAs($user, ['*']);

        $url = '/api/contacts/' . $contact->id;
        $response = $this->json('DELETE', $url);

        $response
             ->assertStatus(200)
             ->assertJson([
                 'deleted' => true
             ]);

    }

    public function testUpdate() {

        $user = factory(User::class, 1)->create()->first();
        $contact = factory(Contact::class, 1)->create([
            'first_name' => 'John'
        ])->first();
        Passport::actingAs($user, ['*']);
        Storage::fake('public');

        $data = [
            'first_name' => 'Mark',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
            '_method' => 'PATCH'
        ];

        $url = '/api/contacts/' . $contact->id;
        $response = $this
            ->json('POST', $url, $data);

        $response
             ->assertStatus(200)
             ->assertJson([
                'data' => [
                    'first_name' => $data['first_name'],
                ]
            ]);

        $avatar_url = $response->getOriginalContent()['data']['avatar'];
        $avatar_filename = basename($avatar_url);
        Storage::disk('public')->assertExists('/avatar/' . $avatar_filename);


    }

    public function testStore() {

        $user = factory(User::class, 1)->create()->first();
        Passport::actingAs($user, ['*']);
        Storage::fake('public');

        $data = [
            'first_name' => 'Mark',
            'last_name' => 'Mark',
            'email' => 'mail@mail.com',
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ];

        $response = $this
            ->json('POST', '/api/contacts', $data);

        $response
             ->assertStatus(200)
             ->assertJson([
                'data' => [
                    'user_id' => $user->id,
                    'email' => $data['email'],
                ]
            ]);
        
        $avatar_url = $response->getOriginalContent()['data']['avatar'];
        $avatar_filename = basename($avatar_url);
        Storage::disk('public')->assertExists('/avatar/' . $avatar_filename);

    }
    
    public function testIndex() {
        
        $users = factory(User::class, 3)
            ->create()
            ->each(function ($u) {
                $u->contacts()->save(factory(Contact::class)->make([ 'user_id' => $u->id ]));
            });

        Passport::actingAs($users->first(), ['*']);

        $response = $this
            ->json('GET', '/api/contacts');

        $response
             ->assertStatus(200)
             ->assertJsonCount(1, 'data');

    }

    public function testIndexFavorites() {

        $user = factory(User::class, 1)->create()->first();
        
        $contact_regular = factory(Contact::class)
            ->create([
                'favorite' => 0,
                'user_id' => $user->id
            ]);

        $contact_favorite = factory(Contact::class)
            ->states('favorite')
            ->create([
                'user_id' => $user->id
            ]);

            
        Passport::actingAs($user, ['*']);

        $response = $this
            ->json('GET', '/api/contacts?search=favorite:1');

        $response
             ->assertStatus(200)
             ->assertJsonCount(1, 'data');

    }

    public function testShow() {

        $user = factory(User::class, 1)->create()->first();
        $contact_regular = factory(Contact::class)
            ->create([
                'user_id' => $user->id
            ]);
            
        Passport::actingAs($user, ['*']);

        $url = '/api/contacts/' . $contact_regular->id;
        $response = $this
            ->json('GET', $url);

        $response
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'id' => $contact_regular->id
                 ]
             ]);

    }    
}
