<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\SocialInsurance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\CreatesAuthenticatedUser;
use Tests\TestCase;

class SocialInsuranceTest extends TestCase
{
    use MakesGraphQLRequests, RefreshDatabase, CreatesAuthenticatedUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthenticatedUser();
    }

    public function test_can_create_social_insurance(): void
    {
        $resident = Resident::factory()->create();

        $response = $this->auth()->graphQL('
            mutation ($residentId: ID!) {
                socialInsuranceCreate(input: {
                    residentId: $residentId
                    code: "0123456789"
                    employer: "Công ty ABC"
                    enrolledDate: "2020-01-01"
                    insuranceType: COMPULSORY
                    status: ACTIVE
                }) {
                    id
                    code
                    employer
                    insuranceType
                    status
                }
            }
        ', ['residentId' => $resident->id]);

        $response->assertJson([
            'data' => [
                'socialInsuranceCreate' => [
                    'code'          => '0123456789',
                    'employer'      => 'Công ty ABC',
                    'insuranceType' => 'COMPULSORY',
                    'status'        => 'ACTIVE',
                ],
            ],
        ]);

        $this->assertDatabaseHas('social_insurances', ['resident_id' => $resident->id]);
    }

    public function test_cannot_create_duplicate_social_insurance_for_same_resident(): void
    {
        $resident = Resident::factory()->create();
        SocialInsurance::factory()->create([
            'resident_id' => $resident->id,
            'code'        => '1111111111',
        ]);

        $response = $this->auth()->graphQL('
            mutation ($residentId: ID!) {
                socialInsuranceCreate(input: {
                    residentId: $residentId
                    code: "2222222222"
                }) {
                    id
                }
            }
        ', ['residentId' => $resident->id]);

        $this->assertNotNull($response->json('errors'));
    }

    public function test_can_update_social_insurance_status(): void
    {
        $resident = Resident::factory()->create();
        $record   = SocialInsurance::factory()->create(['resident_id' => $resident->id]);

        $response = $this->auth()->graphQL('
            mutation ($id: ID!) {
                socialInsuranceUpdate(input: {
                    id: $id
                    status: RESERVED
                }) {
                    id
                    status
                }
            }
        ', ['id' => $record->id]);

        $response->assertJson([
            'data' => [
                'socialInsuranceUpdate' => [
                    'status' => 'RESERVED',
                ],
            ],
        ]);
    }

    public function test_can_delete_social_insurance(): void
    {
        $resident = Resident::factory()->create();
        $record   = SocialInsurance::factory()->create(['resident_id' => $resident->id]);

        $this->auth()->graphQL('
            mutation ($id: ID!) {
                socialInsuranceDelete(id: $id) { id }
            }
        ', ['id' => $record->id]);

        $this->assertDatabaseMissing('social_insurances', ['id' => $record->id]);
    }
}
