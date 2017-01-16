<?php

use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImagesMongoCollection extends Migration {

	protected $connection = 'mongodb';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('images', function($collection)
		{
			$collection->unique('image_name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('images');
	}

	/**
	 * Allows the use of unsupported schema methods.
	 *
	 * @return Blueprint
	 */
	public function __call($method, $args)
	{
		return $this;
	}
}
