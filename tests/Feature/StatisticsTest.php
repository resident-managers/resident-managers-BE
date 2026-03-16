<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\Household;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use MakesGraphQLRequests, RefreshDatabase;

    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $clientRepository = new \Laravel\Passport\ClientRepository();
        $clientRepository->createPersonalAccessGrantClient('Test Client');
        $this->token = User::factory()->create()->createToken('Test')->accessToken;
    }

    private function auth(): static
    {
        return $this->withHeaders(['Authorization' => 'Bearer ' . $this->token]);
    }

    public function test_statistics_returns_correct_counts(): void
    {
        Resident::factory()->count(3)->create(['gender' => 'male',   'type' => 'permanent']);
        Resident::factory()->count(2)->create(['gender' => 'female', 'type' => 'permanent']);
        Resident::factory()->count(1)->create(['gender' => 'male',   'type' => 'temporary']);
        Resident::factory()->count(1)->create(['gender' => 'female', 'type' => 'absent']);

        $response = $this->auth()->graphQL('
            query {
                statistics {
                    totalResidents
                    maleCount
                    femaleCount
                    permanentCount
                    temporaryCount
                    absentCount
                    movedOutCount
                    totalHouseholds
                }
            }
        ');

        $response->assertJson([
            'data' => [
                'statistics' => [
                    'totalResidents'  => 7,
                    'maleCount'       => 4,
                    'femaleCount'     => 3,
                    'permanentCount'  => 5,
                    'temporaryCount'  => 1,
                    'absentCount'     => 1,
                    'movedOutCount'   => 0,
                    'totalHouseholds' => 0,
                ],
            ],
        ]);
    }
}
