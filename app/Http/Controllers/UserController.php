<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Profile;
use App\Student;
use Auth;
use DB;

class UserController extends Controller {

	protected $table = 'xs_xsb';

	public function __construct() {
		$this->middleware('auth');
	}

	public function getRegister() {
		$profile = Profile::find(Auth::user()->xh);
		$majors  = DB::connection('mysql')
			->table('xt_zybh')
			->join('jx_jxjh', 'jx_jxjh.c_zy', '=', 'xt_zybh.c_zy')
			->where('jx_jxjh.c_nj', '=', $profile->nj)
			->where('jx_jxjh.c_zsjj', '=', '0')
			->select('xt_zybh.c_zy', 'xt_zybh.c_mc')
			->orderBy('xt_zybh.c_zy')
			->distinct()
			->get();
		$student = Student::find(Auth::user()->xh);

		return view('register', ['title' => '广西师范大学双学位报名系统', 'profile' => $profile, 'majors' => $majors, 'student' => $student]);
	}

	public function postStore(Request $request) {
		$inputs = $request->all();
	}
}
