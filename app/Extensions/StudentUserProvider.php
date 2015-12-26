<?php

namespace App\Extensions;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class StudentUserProvider extends EloquentUserProvider {

	public function __construct($model) {
		$this->model = $model;
	}

	public function validateCredentials(UserContract $user, array $credentials) {
		return $user->getAuthPassword() === $credentials['password'];
	}
}