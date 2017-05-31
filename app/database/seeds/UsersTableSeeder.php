<?php

use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('users')->delete();
        
        $faker = Faker::create();

		\DB::table('users')->insert(array (
			0 => 
			array (
				'id' => 1,
				'first_name' => 'lam',
				'last_name' => 'quang minh',
				'email' => 'lqminhdev@yahoo.com',
				'image' => NULL,
				'password' => '$2y$10$J9qgAyyZl.Gq.0tmeJA0OuMHUV5Z4fBcSrDWPDimxR8qQtMtwQpq.',
				'description' => $faker->paragraph,
				'subscribe' => 0,
				'remember_token' => 'GP7WmHr00iCxBxGwo4JErreI5zGmfx96d0LQJyWcnK7buhpCNoTuXBsLMK7f',
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 1,
				'created_at' => '2015-05-15 08:26:56',
				'updated_at' => '2015-05-29 02:18:13',
			),
			1 => 
			array (
				'id' => 2,
				'first_name' => 'kei',
				'last_name' => '.',
				'email' => 'hth.tung90@gmail.com',
				'image' => NULL,
				'password' => '$2y$10$zNVAwUfgquo71.BaJyJGWerAScgXK7rtQK3R6SQe8UVM5w6VOioyq',
				'description' => $faker->paragraph,
				'subscribe' => 0,
				'remember_token' => '',
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-17 19:43:11',
				'updated_at' => '2015-05-17 19:43:50',
			),
			2 => 
			array (
				'id' => 3,
				'first_name' => 'demo',
				'last_name' => '.',
				'email' => 'anvydigital0519@gmail.com',
				'image' => NULL,
				'password' => '$2y$10$xEniqvWw1oBZlMQZBz/hqeCj2PDWPR4K7Gz8XDkJRJ2fuSHmX07Hi',
				'description' => $faker->paragraph,
				'subscribe' => 0,
				'remember_token' => '',
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-07-22 19:43:11',
				'updated_at' => '2015-02-22 19:43:50',
			),			
		));
		
		$data = array();
		foreach(range(4, 15) as $index)
		{
			$data[$index] = [
							'id' => $index,
							'first_name' => $faker->name,
							'last_name' => '.',
							'email' => $faker->email,
							'image' => NULL,
							'password' => '$2y$10$J9qgAyyZl.Gq.0tmeJA0OuMHUV5Z4fBcSrDWPDimxR8qQtMtwQpq.',
							'description' => $faker->paragraph,
							'subscribe' => 0,
							'remember_token' => '',
							'active' => 1,
							'created_by' => 0,
							'updated_by' => 0,
							'created_at' => date("Y-m-d H:i:s"),
							'updated_at' => date("Y-m-d H:i:s")			
							];
		}
		\DB::table('users')->insert($data);
	}

}
