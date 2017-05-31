<?php

class BannersBackgroundsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('banners_backgrounds')->delete();
        
		\DB::table('banners_backgrounds')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'Banner 1',
				'image' => 'assets/images/banners/3f3c6d.21-05-15.jpg',
				'type' => 'banner',
				'order_no' => 1,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:00:50',
				'updated_at' => '2015-05-21 22:00:50',
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'Banner 2',
				'image' => 'assets/images/banners/3fc3dc.21-05-15.jpg',
				'type' => 'banner',
				'order_no' => 1,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:02',
				'updated_at' => '2015-05-21 22:01:02',
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'Banner 3',
				'image' => 'assets/images/banners/502e05.21-05-15.jpg',
				'type' => 'banner',
				'order_no' => 1,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:14',
				'updated_at' => '2015-05-21 22:01:14',
			),
			3 => 
			array (
				'id' => 4,
				'name' => 'Banner 4',
				'image' => 'assets/images/banners/033863.21-05-15.jpg',
				'type' => 'banner',
				'order_no' => 1,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:25',
				'updated_at' => '2015-05-21 22:01:25',
			),
			4 => 
			array (
				'id' => 5,
				'name' => 'Banner 5',
				'image' => 'assets/images/banners/a48976.21-05-15.jpg',
				'type' => 'banner',
				'order_no' => 1,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:37',
				'updated_at' => '2015-05-21 22:01:37',
			),
			5 => 
			array (
				'id' => 6,
				'name' => 'Banner 6',
				'image' => 'assets/images/banners/d0eae2.21-05-15.jpg',
				'type' => 'banner',
				'order_no' => 1,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:49',
				'updated_at' => '2015-05-21 22:01:49',
			),
			6 => 
			array (
				'id' => 7,
				'name' => 'Background on wall 1',
				'image' => 'assets/images/background/cream-living-room.jpg',
				'type' => 'background',
				'order_no' => 1,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:49',
				'updated_at' => '2015-05-21 22:01:49',
			),
			7 => 
			array (
				'id' => 8,
				'name' => 'Background on wall 2',
				'image' => 'assets/images/background/bg_wall5.jpg',
				'type' => 'background',
				'order_no' => 2,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:49',
				'updated_at' => '2015-05-21 22:01:49',
			),
			8 => 
			array (
				'id' => 9,
				'name' => 'Background on wall 3',
				'image' => 'assets/images/background/interior-wall1.jpg',
				'type' => 'background',
				'order_no' => 3,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:49',
				'updated_at' => '2015-05-21 22:01:49',
			),
			9 => 
			array (
				'id' => 10,
				'name' => 'Background design 1',
				'image' => 'assets/images/background/wall021.jpg',
				'type' => 'background',
				'order_no' => 1,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:49',
				'updated_at' => '2015-05-21 22:01:49',
			),
			10 => 
			array (
				'id' => 11,
				'name' => 'Background design 2',
				'image' => 'assets/images/background/wall031.jpg',
				'type' => 'background',
				'order_no' => 2,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:49',
				'updated_at' => '2015-05-21 22:01:49',
			),
			11 => 
			array (
				'id' => 12,
				'name' => 'Background design 3',
				'image' => 'assets/images/background/gray1.jpg',
				'type' => 'background',
				'order_no' => 3,
				'active' => 1,
				'created_by' => 0,
				'updated_by' => 0,
				'created_at' => '2015-05-21 22:01:49',
				'updated_at' => '2015-05-21 22:01:49',
			),
			
		));
	}

}
