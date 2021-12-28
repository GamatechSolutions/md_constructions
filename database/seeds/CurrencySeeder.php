<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		if (\DB::table('currencies')->count() > 0) {
            return;
        }
           
        \DB::table('currencies')->insert([
			[ 'code' =>'RSD' , 'name' => 'Srpski dinar', 'symbol' => 'Дин.' ],
			[ 'code' =>'EUR' , 'name' => 'Evro', 'symbol' => '€' ],
		]);
    }
}
