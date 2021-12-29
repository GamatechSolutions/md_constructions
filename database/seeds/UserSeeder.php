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
				'email'		=> 'office@mdconstructions.rs',
				'password'	=> \Hash::make('mladen123')
			])
		];

		$users['admin']->assignRole('Administrator');
    }
}
