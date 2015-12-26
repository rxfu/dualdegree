<?php

namespace App\Extensions;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Str;

class StudentUserProvider implements UserProvider {

	/**
	 * The Eloquent user model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Create a new database user provider.
	 *
	 * @param  string  $model
	 * @return void
	 */
	public function __construct($model) {
		$this->model = $model;
	}

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed  $identifier
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveById($identifier) {
		return $this->createModel()->newQuery()->find($identifier);
	}

	/**
	 * Retrieve a user by their unique identifier and "remember me" token.
	 *
	 * @param  mixed  $identifier
	 * @param  string  $token
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByToken($identifier, $token) {
		$model = $this->createModel();

		return $model->newQuery()
			->where($model->getAuthIdentifierName(), $identifier)
			->where($model->getRememberTokenName(), $token)
			->first();
	}

	/**
	 * Update the "remember me" token for the given user in storage.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
	 * @param  string  $token
	 * @return void
	 */
	public function updateRememberToken(UserContract $user, $token) {
		$user->setRememberToken($token);

		$user->save();
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array  $credentials
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByCredentials(array $credentials) {
		// First we will add each credential element to the query as a where clause.
		// Then we can execute the query and, if we found a user, return it in a
		// Eloquent User "model" that will be utilized by the Guard instances.
		$query = $this->createModel()->newQuery();

		foreach ($credentials as $key => $value) {
			if (!Str::contains($key, 'password')) {
				$query->where($key, $value);
			}
		}

		return $query->first();
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
	 * @param  array  $credentials
	 * @return bool
	 */
	public function validateCredentials(UserContract $user, array $credentials) {
		$plain = $credentials['mm'];

		return $plain == $user->getAuthPassword();
	}

	/**
	 * Create a new instance of the model.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function createModel() {
		$class = '\\' . ltrim($this->model, '\\');

		return new $class;
	}

	/**
	 * Gets the name of the Eloquent user model.
	 *
	 * @return string
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * Sets the name of the Eloquent user model.
	 *
	 * @param  string  $model
	 * @return $this
	 */
	public function setModel($model) {
		$this->model = $model;

		return $this;
	}
}