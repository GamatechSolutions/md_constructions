<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		app()[PermissionRegistrar::class]->forgetCachedPermissions();

		$roles = [
			'admin'			=> Role::create([ 'name' => 'Administrator' ]),
			'moderator'		=> Role::create([ 'name' => 'Prodavac' ]),
			'observer'		=> Role::create([ 'name' => 'Korisnik' ]),
			'bookkeeper'	=> Role::create([ 'name' => 'Računovođa' ]),
		];

		Permission::create([ 'name' => 'product.create' ]);
		Permission::create([ 'name' => 'product.edit' ]);
		Permission::create([ 'name' => 'product.view' ]);
		Permission::create([ 'name' => 'product.delete' ]);
		Permission::create([ 'name' => 'product.increase-quantity' ]);
		Permission::create([ 'name' => 'product.decrease-quantity' ]);
		Permission::create([ 'name' => 'activity.view' ]);

		$roles['admin']->givePermissionTo(
			'product.create',
			'product.edit',
			'product.view',
			'product.delete',
			'product.increase-quantity',
			'product.decrease-quantity',
			'activity.view'
		);

		$roles['bookkeeper']->givePermissionTo(
			'product.increase-quantity',
			'product.decrease-quantity',
			'product.view'
		);

		$roles['moderator']->givePermissionTo(
			'product.increase-quantity',
			'product.decrease-quantity',
			'product.view'
		);

		$roles['observer']->givePermissionTo(
			'product.view'
		);
    }
}
