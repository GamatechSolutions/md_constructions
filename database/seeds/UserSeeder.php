<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$users = [
			'admin'			=> User::create([
				'name'		=> 'admin',
				'email'		=> 'admin@admin',
				'password'	=> \Hash::make('admin')
			])
		];

		$users['admin']->assignRole('Administrator');
    }
}
