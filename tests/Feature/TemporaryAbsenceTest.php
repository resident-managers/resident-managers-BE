<?php

namespace Tests\Feature;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class TemporaryAbsenceTest extends TestCase
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

    public function test_can_register_temporary_absence(): void
    {
        $resident = Resident::factory()->create();

        $response = $this->auth()->graphQL('
            mutation ($residentId: ID!) {
                temporaryAbsenceCreate(input: {
                    residentId: $residentId
                    destination: "Hà Nội"
                    fromDate: "2026-02-01"
                    toDate: "2026-06-01"
                    reason: "Học tập"
                }) {
                    id
                    destination
                    fromDate
                    toDate
                    resident { id }
                }
            }
        ', ['residentId' => $resident->id]);

        $response->assertJson([
            'data' => [
                'temporaryAbsenceCreate' => [
                    'destination' => 'Hà Nội',
                    'fromDate'    => '2026-02-01',
                    'toDate'      => '2026-06-01',
                ],
            ],
        ]);

        $this->assertDatabaseHas('temporary_absences', ['resident_id' => $resident->id]);
    }

    public function test_registers_temporary_absence_updates_resident_type(): void
    {
        $resident = Resident::factory()->create(['type' => 'permanent']);

        $this->auth()->graphQL('
            mutation ($residentId: ID!) {
                temporaryAbsenceCreate(input: {
                    residentId: $residentId
                    destination: "Đà Nẵng"
                    fromDate: "2026-01-01"
                }) {
                    id
                }
            }
        ', ['residentId' => $resident->id]);

        $this->assertDatabaseHas('residents', ['id' => $resident->id, 'type' => 'absent']);
    }

    public function test_can_update_temporary_absence(): void
    {
        $resident = Resident::factory()->create();
        $record   = $resident->temporaryAbsences()->create([
            'destination' => 'Nơi cũ',
            'from_date'   => '2026-01-01',
        ]);

        $response = $this->auth()->graphQL('
            mutation ($id: ID!) {
                temporaryAbsenceUpdate(input: {
                    id: $id
                    destination: "Nơi mới"
                    toDate: "2026-12-31"
                }) {
                    id
                    destination
                    toDate
                }
            }
        ', ['id' => $record->id]);

        $response->assertJson([
            'data' => [
                'temporaryAbsenceUpdate' => [
                    'destination' => 'Nơi mới',
                    'toDate'      => '2026-12-31',
                ],
            ],
        ]);
    }

    public function test_can_delete_temporary_absence(): void
    {
        $resident = Resident::factory()->create();
        $record   = $resident->temporaryAbsences()->create([
            'destination' => 'Nơi test',
            'from_date'   => '2026-01-01',
        ]);

        $this->auth()->graphQL('
            mutation ($id: ID!) {
                temporaryAbsenceDelete(id: $id) { id }
            }
        ', ['id' => $record->id]);

        $this->assertDatabaseMissing('temporary_absences', ['id' => $record->id]);
    }
}
