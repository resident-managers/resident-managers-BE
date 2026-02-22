<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use MakesGraphQLRequests, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $clientRepository = new \Laravel\Passport\ClientRepository();
        $clientRepository->createPersonalAccessGrantClient(
            'Test Personal Access Client'
        );
    }

    public function test_user_can_login_via_graphql(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->graphQL('
            mutation {
                dangNhap(email: "test@example.com", password: "password") {
                    access_token
                    user {
                        email
                    }
                }
            }
        ');

        $response->assertJsonStructure([
            'data' => [
                'dangNhap' => [
                    'access_token',
                    'user' => [
                        'email'
                    ]
                ]
            ]
        ]);

        $this->assertNotEmpty($response->json('data.dangNhap.access_token'));
    }

    public function test_user_can_get_me_query_with_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->graphQL('
            query {
                me {
                    id
                    email
                }
            }
        ');

        $response->assertJson([
            'data' => [
                'me' => [
                    'id' => (string) $user->id,
                    'email' => $user->email,
                ]
            ]
        ]);
    }
}
