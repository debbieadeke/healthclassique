<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Reset cached roles and permissions
        app()[
        \Spatie\Permission\PermissionRegistrar::class
        ]->forgetCachedPermissions();

        $permissions = config('settings.permissions');
        foreach ($permissions as $key => $values) {
            foreach ($values as $permission) {
                try {
                    Permission::create([
                        'name' => $key." : ".$permission,
                        'guard_name' => 'web'
                    ]);
                } catch (Exception $exception) {
                    logger($exception);
                }
            }
        }


        //$roles = config('settings.roles');

        $super_admin_role = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $super_admin_role->givePermissionTo(Permission::all());

        $manager = Role::create(['name' => 'manager', 'guard_name' => 'web'])->givePermissionTo([
            'reports : view_reports',
            'user : create_user',
            'user : delete_user',
            'user : edit_user',
            'user : export_user',
            'user : import_user',
            'user : view_users',
        ]);
        $user = Role::create(['name' => 'user', 'guard_name' => 'web'])->givePermissionTo([
            'sales_call : create_calls',
            'sales_call : edit_calls',
            'sales_call : delete_calls',
            'sales_call : view_calls',
            'sales_plan : create_plans',
            'sales_plan : edit_plans',
            'sales_plan : delete_plans',
            'sales_plan : view_plans',
        ]);


        User::find(1)->assignRole($super_admin_role);
        User::find(2)->assignRole($super_admin_role);
        User::find(3)->assignRole($user);
		User::find(4)->assignRole($user);

    }
}
