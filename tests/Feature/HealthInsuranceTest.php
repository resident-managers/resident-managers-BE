<?php

namespace Tests\Feature;

use App\Models\HealthInsurance;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class HealthInsuranceTest extends TestCase
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

    public function test_can_create_health_insurance(): void
    {
        $resident = Resident::factory()->create();

        $response = $this->auth()->graphQL('
            mutation ($residentId: ID!) {
                healthInsuranceCreate(input: {
                    residentId: $residentId
                    code: "DN4012001234567"
                    healthcareFacility: "Bệnh viện Chợ Rẫy"
                    issuedDate: "2024-01-01"
                    expiryDate: "2028-12-31"
                }) {
                    id
                    code
                    healthcareFacility
                    expiryDate
                }
            }
        ', ['residentId' => $resident->id]);

        $response->assertJson([
            'data' => [
                'healthInsuranceCreate' => [
                    'code'               => 'DN4012001234567',
                    'healthcareFacility' => 'Bệnh viện Chợ Rẫy',
                    'expiryDate'         => '2028-12-31',
                ],
            ],
        ]);

        $this->assertDatabaseHas('health_insurances', ['resident_id' => $resident->id]);
    }

    public function test_cannot_create_duplicate_health_insurance_for_same_resident(): void
    {
        $resident = Resident::factory()->create();
        HealthInsurance::factory()->create([
            'resident_id' => $resident->id,
            'code'        => 'DN4012001111111',
        ]);

        $response = $this->auth()->graphQL('
            mutation ($residentId: ID!) {
                healthInsuranceCreate(input: {
                    residentId: $residentId
                    code: "DN4012002222222"
                }) {
                    id
                }
            }
        ', ['residentId' => $resident->id]);

        $this->assertNotNull($response->json('errors'));
    }

    public function test_cannot_create_health_insurance_with_duplicate_code(): void
    {
        $resident1 = Resident::factory()->create();
        $resident2 = Resident::factory()->create();
        HealthInsurance::factory()->create([
            'resident_id' => $resident1->id,
            'code'        => 'DN4012001234567',
        ]);

        $response = $this->auth()->graphQL('
            mutation ($residentId: ID!) {
                healthInsuranceCreate(input: {
                    residentId: $residentId
                    code: "DN4012001234567"
                }) {
                    id
                }
            }
        ', ['residentId' => $resident2->id]);

        $this->assertNotNull($response->json('errors'));
    }

    public function test_can_update_health_insurance(): void
    {
        $resident = Resident::factory()->create();
        $record   = HealthInsurance::factory()->create(['resident_id' => $resident->id]);

        $response = $this->auth()->graphQL('
            mutation ($id: ID!) {
                healthInsuranceUpdate(input: {
                    id: $id
                    healthcareFacility: "Bệnh viện 115"
                    expiryDate: "2030-12-31"
                }) {
                    id
                    healthcareFacility
                    expiryDate
                }
            }
        ', ['id' => $record->id]);

        $response->assertJson([
            'data' => [
                'healthInsuranceUpdate' => [
                    'healthcareFacility' => 'Bệnh viện 115',
                    'expiryDate'         => '2030-12-31',
                ],
            ],
        ]);
    }

    public function test_can_delete_health_insurance(): void
    {
        $resident = Resident::factory()->create();
        $record   = HealthInsurance::factory()->create(['resident_id' => $resident->id]);

        $this->auth()->graphQL('
            mutation ($id: ID!) {
                healthInsuranceDelete(id: $id) { id }
            }
        ', ['id' => $record->id]);

        $this->assertDatabaseMissing('health_insurances', ['id' => $record->id]);
    }
}
