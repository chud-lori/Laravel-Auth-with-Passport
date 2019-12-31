<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function registerUser()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => 'secret', // secret
            'password_confirmation' => 'secret', // secret
            'remember_token' => Str::random(10)
        ];

        $this->post('/api/register', $data)
            //  ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
    }

    /** @test */
    public function loginUser()
    {
        $data = [
            'username' => 'lori@email.com',
            'password' => '123123'
        ];
        $this->json('POST','/api/login', $data, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['access_token','token_type','expires_at']);
    }

    /** @test */
    // public function userDetail()
    // {
    //     Passport::actingAs(
    //         factory(User::class)->create(),
    //         ['create-servers']
    //     );
    
    //     $this->post('/api/user/details')
    //          ->assertJsonFragment([
    //             'message' => 'Successfully Login!',
    //         ]);
    // }
}
