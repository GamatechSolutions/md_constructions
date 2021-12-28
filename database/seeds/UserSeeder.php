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
				'name'		=> 'Administrator',
				'email'		=> 'admin@zavarivackg',
				'password'	=> \Hash::make('zavarivackgfakture123')
			])
		];

		$users['admin']->assignRole('Administrator');
    }
}
