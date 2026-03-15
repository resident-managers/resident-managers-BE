<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class TemporaryResidenceTest extends TestCase
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

    public function test_can_register_temporary_residence(): void
    {
        $resident = Resident::factory()->create();

        $response = $this->auth()->graphQL('
            mutation ($residentId: ID!) {
                temporaryResidenceCreate(input: {
                    residentId: $residentId
                    address: "123 Trần Hưng Đạo, Q5"
                    hostName: "Nguyễn Văn B"
                    fromDate: "2026-01-01"
                    reason: "Làm việc"
                }) {
                    id
                    address
                    hostName
                    resident { id }
                }
            }
        ', ['residentId' => $resident->id]);

        $response->assertJson([
            'data' => [
                'temporaryResidenceCreate' => [
                    'address'  => '123 Trần Hưng Đạo, Q5',
                    'hostName' => 'Nguyễn Văn B',
                ],
            ],
        ]);

        $this->assertDatabaseHas('temporary_residences', ['resident_id' => $resident->id]);
    }

    public function test_registers_temporary_residence_updates_resident_type(): void
    {
        $resident = Resident::factory()->create(['type' => 'permanent']);

        $this->auth()->graphQL('
            mutation ($residentId: ID!) {
                temporaryResidenceCreate(input: {
                    residentId: $residentId
                    address: "123 Lê Lai"
                    fromDate: "2026-01-01"
                }) {
                    id
                }
            }
        ', ['residentId' => $resident->id]);

        $this->assertDatabaseHas('residents', ['id' => $resident->id, 'type' => 'temporary']);
    }

    public function test_can_update_temporary_residence(): void
    {
        $resident = Resident::factory()->create();
        $record   = $resident->temporaryResidences()->create([
            'address'   => 'Địa chỉ cũ',
            'from_date' => '2026-01-01',
        ]);

        $response = $this->auth()->graphQL('
            mutation ($id: ID!) {
                temporaryResidenceUpdate(input: {
                    id: $id
                    address: "Địa chỉ mới"
                    toDate: "2026-12-31"
                }) {
                    id
                    address
                    toDate
                }
            }
        ', ['id' => $record->id]);

        $response->assertJson([
            'data' => [
                'temporaryResidenceUpdate' => [
                    'address' => 'Địa chỉ mới',
                    'toDate'  => '2026-12-31',
                ],
            ],
        ]);
    }

    public function test_can_delete_temporary_residence(): void
    {
        $resident = Resident::factory()->create();
        $record   = $resident->temporaryResidences()->create([
            'address'   => 'Địa chỉ test',
            'from_date' => '2026-01-01',
        ]);

        $this->auth()->graphQL('
            mutation ($id: ID!) {
                temporaryResidenceDelete(id: $id) { id }
            }
        ', ['id' => $record->id]);

        $this->assertDatabaseMissing('temporary_residences', ['id' => $record->id]);
    }
}
