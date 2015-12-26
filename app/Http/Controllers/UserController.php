<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class UserController extends Controller {

	protected $table = 'xs_xsb';

	public function __construct() {
		$this->middleware('auth');
	}

	public function getRegister() {

		return view('register', ['title' => '广西师范大学双学位报名系统']);
	}
}
