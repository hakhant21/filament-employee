<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $permissions = [
        'create',
        'read',
        'edit',
        'delete',
    ];

    public function run(): void
    {
        foreach($this->permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }

        $user = User::where('id', 1)->first();

        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
