<?php

namespace Tests\Feature;

use App\Models\Resident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\CreatesAuthenticatedUser;
use Tests\TestCase;

class ResidentTest extends TestCase
{
    use MakesGraphQLRequests, RefreshDatabase, CreatesAuthenticatedUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthenticatedUser();
    }

    public function test_can_create_resident(): void
    {
        $response = $this->auth()->graphQL('
            mutation {
                residentCreate(input: {
                    fullName: "Nguyễn Văn A"
                    gender: MALE
                    dateOfBirth: "1990-01-01"
                    phone: "0901234567"
                    nationalId: "079090001234"
                    address: "123 Lê Lợi, Q1, TP.HCM"
                    permanentAddress: "456 Nguyễn Huệ, Q1, TP.HCM"
                    residenceType: PERMANENT
                }) {
                    id
                    fullName
                    residenceType
                    permanentAddress
                }
            }
        ');

        $response->assertJson([
            'data' => [
                'residentCreate' => [
                    'fullName'         => 'Nguyễn Văn A',
                    'residenceType'    => 'PERMANENT',
                    'permanentAddress' => '456 Nguyễn Huệ, Q1, TP.HCM',
                ],
            ],
        ]);

        $this->assertDatabaseHas('residents', ['full_name' => 'Nguyễn Văn A']);
    }

    public function test_cannot_create_resident_with_duplicate_national_id(): void
    {
        Resident::factory()->create(['national_id' => '079090001234']);

        $response = $this->auth()->graphQL('
            mutation {
                residentCreate(input: {
                    fullName: "Nguyễn Văn B"
                    gender: MALE
                    nationalId: "079090001234"
                }) {
                    id
                }
            }
        ');

        $response->assertJsonPath('errors.0.extensions.validation.national_id.0', 'Số CCCD/CMND đã tồn tại.');
    }

    public function test_can_update_resident(): void
    {
        $resident = Resident::factory()->create();

        $response = $this->auth()->graphQL('
            mutation ($id: ID!) {
                residentUpdate(id: $id, input: {
                    fullName: "Tên Mới"
                    gender: FEMALE
                    residenceType: TEMPORARY
                }) {
                    id
                    fullName
                    residenceType
                }
            }
        ', ['id' => $resident->id]);

        $response->assertJson([
            'data' => [
                'residentUpdate' => [
                    'fullName' => 'Tên Mới',
                    'residenceType' => 'TEMPORARY',
                ],
            ],
        ]);
    }

    public function test_can_query_residents(): void
    {
        Resident::factory()->count(3)->create();

        $response = $this->auth()->graphQL('
            query {
                residents(first: 10) {
                    data { id fullName residenceType }
                    paginatorInfo { total }
                }
            }
        ');

        $response->assertJsonPath('data.residents.paginatorInfo.total', 3);
    }

    public function test_can_delete_resident(): void
    {
        $resident = Resident::factory()->create();

        $this->auth()->graphQL('
            mutation ($id: ID!) {
                residentDelete(id: $id) { id }
            }
        ', ['id' => $resident->id]);

        $this->assertDatabaseMissing('residents', ['id' => $resident->id]);
    }
}
