<?php namespace App\Models;

use Jenssegers\Mongodb\Model as MongoModel;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class User extends MongoModel implements \Illuminate\Contracts\Auth\Authenticatable  {

	use AuthenticatableTrait;

	protected $connection = 'mongodb';

	protected $collection = 'users';

	protected $primaryKey = 'user_id';

	protected $fillable = ['user_id'];

	public function images() {
		return $this->hasMany(Image::class);
	}

}
