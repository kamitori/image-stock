<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersBackgroundsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('banners_backgrounds', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 150)->nullable();
			$table->string('image',250);
			$table->string('type',20);
			$table->integer('order_no')->default(1);
			$table->boolean('active')->default(1);
			$table->integer('created_by')->default(0);
			$table->integer('updated_by')->default(0);			
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('banners_backgrounds');
	}

}
