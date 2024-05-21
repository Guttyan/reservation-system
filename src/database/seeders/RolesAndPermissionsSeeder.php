<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ロールの作成
        $adminRole = Role::create(['name' => 'admin']);
        $representativeRole = Role::create(['name' => 'representative']);
        $userRole = Role::create(['name' => 'user']);

        // 権限の作成
        $createShopPermission = Permission::create(['name' => 'create shop']);
        $updateShopPermission = Permission::create(['name' => 'update shop']);
        $viewReservationsPermission = Permission::create(['name' => 'view reservations']);
        $createRepresentativePermission = Permission::create(['name' => 'create representative']);

        // ロールに権限を割り当てる
        $adminRole->givePermissionTo($createRepresentativePermission);
        $representativeRole->givePermissionTo($createShopPermission, $updateShopPermission, $viewReservationsPermission);
    }
}
