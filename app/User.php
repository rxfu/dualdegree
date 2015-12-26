<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'xh', 'mm',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [
		'mm',
	];

	protected $connection = 'pgsql';

	protected $table = "xk_xsmm";

	protected $primaryKey = 'xh';

	public $incrementing = false;

	public $timestamps = false;

	public function getRememberToken() {
		return null; // not supported
	}

	public function setRememberToken($value) {
		// not supported
	}

	public function getRememberTokenName() {
		return null; // not supported
	}

	public function getAuthPassword() {
		return $this->mm;
	}

	/**
	 * Override the method to ignore the remember token
	 */
	public function setAttribute($key, $value) {
		$isRememberTokenAttribute = $key == $this->getRememberTokenName();

		if (!$isRememberTokenAttribute) {
			parent::setAttribute($key, $value);
		}
	}
}
