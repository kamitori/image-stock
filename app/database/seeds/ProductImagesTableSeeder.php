<?php

class ProductImagesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('product_images')->delete();
        
		\DB::table('product_images')->insert(array (
			0 => 
			array (
				'id' => 1,
				'path' => 'assets/images/products/product-fp.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			1 => 
			array (
				'id' => 2,
				'path' => 'assets/images/products/product-fp.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			2 => 
			array (
				'id' => 3,
				'path' => 'assets/images/products/product-fp-back.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			3 => 
			array (
				'id' => 4,
				'path' => 'assets/images/products/product-fp-wall.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			4 => 
			array (
				'id' => 5,
				'path' => 'assets/images/products/product-po.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			5 => 
			array (
				'id' => 6,
				'path' => 'assets/images/products/product-po.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			6 => 
			array (
				'id' => 7,
				'path' => 'assets/images/products/product-po-ship.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			7 => 
			array (
				'id' => 8,
				'path' => 'assets/images/products/product-po-wall.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			8 => 
			array (
				'id' => 9,
				'path' => 'assets/images/products/product-s.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			9 => 
			array (
				'id' => 10,
				'path' => 'assets/images/products/product-s.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			10 => 
			array (
				'id' => 11,
				'path' => 'assets/images/products/product-s-wall.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),
			11 => 
			array (
				'id' => 12,
				'path' => 'assets/images/products/product-s-wire.01-07-15.jpg',
				'created_by' => 0,
				'updated_by' => 0,
			),

		));
	}

}
