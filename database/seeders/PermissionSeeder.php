<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    // [id, guard, name]
    private const PERMISSIONS = [
        // guard: api
        [ 1, 'api', 'view residents'],
        [ 2, 'api', 'create residents'],
        [ 3, 'api', 'update residents'],
        [ 4, 'api', 'delete residents'],
        [ 5, 'api', 'view households'],
        [ 6, 'api', 'create households'],
        [ 7, 'api', 'update households'],
        [ 8, 'api', 'delete households'],
        [ 9, 'api', 'view health insurance'],
        [10, 'api', 'create health insurance'],
        [11, 'api', 'update health insurance'],
        [12, 'api', 'delete health insurance'],
        [13, 'api', 'view social insurance'],
        [14, 'api', 'create social insurance'],
        [15, 'api', 'update social insurance'],
        [16, 'api', 'delete social insurance'],
        [17, 'api', 'view temporary residence'],
        [18, 'api', 'create temporary residence'],
        [19, 'api', 'update temporary residence'],
        [20, 'api', 'delete temporary residence'],
        [21, 'api', 'view temporary absence'],
        [22, 'api', 'create temporary absence'],
        [23, 'api', 'update temporary absence'],
        [24, 'api', 'delete temporary absence'],
        [25, 'api', 'view statistics'],

        // guard: admin (starts at 101)
        [101, 'admin', 'view residents'],
        [102, 'admin', 'create residents'],
        [103, 'admin', 'update residents'],
        [104, 'admin', 'delete residents'],
        [105, 'admin', 'view households'],
        [106, 'admin', 'create households'],
        [107, 'admin', 'update households'],
        [108, 'admin', 'delete households'],
        [109, 'admin', 'view health insurance'],
        [110, 'admin', 'create health insurance'],
        [111, 'admin', 'update health insurance'],
        [112, 'admin', 'delete health insurance'],
        [113, 'admin', 'view social insurance'],
        [114, 'admin', 'create social insurance'],
        [115, 'admin', 'update social insurance'],
        [116, 'admin', 'delete social insurance'],
        [117, 'admin', 'view temporary residence'],
        [118, 'admin', 'create temporary residence'],
        [119, 'admin', 'update temporary residence'],
        [120, 'admin', 'delete temporary residence'],
        [121, 'admin', 'view temporary absence'],
        [122, 'admin', 'create temporary absence'],
        [123, 'admin', 'update temporary absence'],
        [124, 'admin', 'delete temporary absence'],
        [125, 'admin', 'view statistics'],
        [126, 'admin', 'create users'],
    ];

    // [id, guard, name]
    private const ROLES = [
        [1, 'api',   'user'],
        [2, 'admin', 'admin'],
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Schema::disableForeignKeyConstraints();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        Role::truncate();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();

        foreach (self::PERMISSIONS as [$id, $guard, $name]) {
            Permission::create(['id' => $id, 'name' => $name, 'guard_name' => $guard]);
        }

        $userRole  = Role::create(['id' => 1, 'name' => 'user',  'guard_name' => 'api']);
        $adminRole = Role::create(['id' => 2, 'name' => 'admin', 'guard_name' => 'admin']);

        $userRole->syncPermissions(
            Permission::query()->where('guard_name', 'api')->pluck('name')->all()
        );

        $adminRole->syncPermissions(
            Permission::query()->where('guard_name', 'admin')->pluck('name')->all()
        );
    }
}
