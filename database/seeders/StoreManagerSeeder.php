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


class StoreManagerSeeder extends Seeder
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

        $storemanager = Role::create(['name' => 'store_manager', 'guard_name' => 'web'])->givePermissionTo([
            'production : manage_inputs',
            'production : receive_batches'
        ]);


        User::where('email', 'production@healthclassique.com')->assignRole($storemanager);

    }
}
