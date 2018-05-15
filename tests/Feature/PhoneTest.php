<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Entities\User;
use App\Entities\Phone;
use App\Entities\Contact;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhoneTest extends TestCase
{

    use RefreshDatabase;

    public function testIndex() {
        
        $user = factory(User::class, 1)->create()->first();
        $contact = factory(Contact::class, 1)->create([ 'user_id' => $user->id ])->first();
        $phones = factory(Phone::class, 3)->create([ 'contact_id' => $contact->id ]);

        Passport::actingAs($user, ['*']);

        $url = '/api/contacts/' . $contact->id . '/phones';
        $response = $this->json('GET', $url);

        $response
             ->assertStatus(200)
             ->assertJsonCount(3, 'data');

    }

    public function testStore() {

        $user = factory(User::class, 1)->create()->first();
        $contact = factory(Contact::class, 1)->create([ 'user_id' => $user->id ])->first();
        Passport::actingAs($user, ['*']);

        $data = [
            'phones' => [
                [ 'number' => '+38591883381', 'label' => 'Home'],
                [ 'number' => '+38591883382', 'label' => 'Work'],
                [ 'number' => '+38591883383', 'label' => 'Space'],
            ]
        ];

        $url = '/api/contacts/' . $contact->id . '/phones';
        $response = $this->json('POST', $url, $data);

        $response
             ->assertStatus(200)
             ->assertJsonCount(3, 'data');

    }
   
}
