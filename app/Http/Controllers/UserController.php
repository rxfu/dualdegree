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
		$majors  = DB::table('xt_zybh')
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
		$rules  = [
			'phone' => 'required',
		];
		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			$profile = Profile::find(Auth::user()->xh);
			$special = DB::connection('pgsql')
				->table('jx_zy')
				->where('zy', '=', $profile->zyh)
				->first();

			$student         = new Student;
			$student->c_xh   = $profile->xh;
			$student->c_xm   = $profile->xm;
			$student->c_xb   = $profile->xb;
			$student->c_csrq = $profile->csrq;
			$student->c_mz   = $profile->mz;
			$student->c_sfzh = $profile->sfzh;
			$student->c_ksh  = $profile->ksh;
			$student->c_jg   = $profile->jg;
			$student->c_yx   = $profile->yx;
			$student->c_bz   = $special->mc;
			$student->c_lxdh = $inputs['phone'];
			$student->c_zyh  = $inputs['major'];

			if ($student->save()) {
				return redirect('/')->with('status', '双学位报名成功');
			} else {
				return back()->withErrors('双学位报名失败');
			}
		} else {
			return back()->withErrors($validator);
		}
	}
}
