<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Profile;
use App\Student;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller {

	protected $table = 'xs_xsb';

	public function __construct() {
		$this->middleware('auth', ['except' => ['getLogin', 'postLogin']]);
	}

	public function getBan() {
		return view('ban', ['title' => '广西师范大学双学位报名系统']);
	}

	public function getRegister() {
		$switch = DB::table('xt')->where('c_id', '=', 'XS_DEXWBM')->first();
		if ($switch->c_value == 0) {
			return redirect('user/ban')->with('message', '现在还没有开放双学士学位教育网上报名，以便确认您的修读资格，谢谢。');
		}

		$profile = Profile::find(Auth::user()->xh);
		if (is_null($profile)) {
			return redirect('user/ban')->with('message', '双学士学位教育只限在校学生报名，其它学籍状态的同学如果需要报名，请直接联系相关学院，其联系方式见《广西师范大学2015年“双学士学位”教育招生简章》，以便确认您的修读资格，谢谢。');
		}
		if ($profile->nj != '2014') {
			return redirect('user/ban')->with('message', '2015年双学士学位教育只限2014级学生报名，其它年级的同学如果需要报名，请直接联系相关学院，其联系方式见《广西师范大学2015年“双学士学位”教育招生简章》，以便确认您的修读资格，谢谢。');
		}

		$majors = DB::table('xt_zybh')
			->join('jx_jxjh', 'jx_jxjh.c_zy', '=', 'xt_zybh.c_zy')
			->where('jx_jxjh.c_nj', '=', $profile->nj)
			->where('jx_jxjh.c_zsjj', '=', '0')
			->select('xt_zybh.c_zy', 'xt_zybh.c_mc')
			->orderBy('xt_zybh.c_zy')
			->distinct()
			->get();
		$student = DB::table('xs_xsb')
			->join('xt_zybh', 'xt_zybh.c_zy', '=', 'xs_xsb.c_zyh')
			->join('xt_yxzy', 'xt_yxzy.c_zy', '=', 'xt_zybh.c_zy')
			->join('xt_yxbh', 'xt_yxbh.c_xb', '=', 'xt_yxzy.c_yx')
			->select('c_xh', 'c_xm', 'c_zyh', 'c_lxdh', 'c_sfzh', 'xt_zybh.c_mc as zymc', 'xt_yxbh.c_mc as yxmc')
			->where('c_xh', '=', Auth::user()->xh)
			->first();

		return view('register', ['title' => '广西师范大学双学位报名系统', 'profile' => $profile, 'majors' => $majors, 'student' => $student]);
	}

	public function postStore(Request $request) {
		$inputs = $request->all();
		$rules  = [
			'phone' => 'required',
		];
		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			$profile = DB::connection('pgsql')
				->table('xs_zxs')
				->join('zd_xb', 'zd_xb.dm', '=', 'xs_zxs.xbdm')
				->join('zd_mz', 'zd_mz.dm', '=', 'xs_zxs.mzdm')
				->select('xh', 'xm', 'zd_xb.mc as xb', 'csny', 'zd_mz.mc as mz', 'sfzh', 'ksh', 'jg', 'zy')
				->where('xh', '=', Auth::user()->xh)
				->first();
			$special = DB::connection('pgsql')
				->table('jx_zy')
				->where('zy', '=', $profile->zy)
				->first();

			$student         = new Student;
			$student->c_xh   = $profile->xh;
			$student->c_xm   = iconv('UTF-8', 'GBK', $profile->xm);
			$student->c_xb   = iconv('UTF-8', 'GBK', $profile->xb);
			$student->c_csrq = $profile->csny;
			$student->c_mz   = iconv('UTF-8', 'GBK', $profile->mz);
			$student->c_sfzh = $profile->sfzh;
			$student->c_ksh  = $profile->ksh;
			$student->c_jg   = iconv('UTF-8', 'GBK', $profile->jg);
			$student->c_bz   = iconv('UTF-8', 'GBK', $special->mc);
			$student->c_lxdh = $inputs['phone'];
			$student->c_zyh  = $inputs['major'];

			if ($student->save()) {
				return back()->with('status', '双学位报名成功');
			} else {
				return back()->withErrors('双学位报名失败');
			}
		} else {
			return back()->withErrors($validator);
		}
	}

	public function postUpdate(Request $request, $id) {
		$inputs = $request->all();
		$rules  = [
			'phone' => 'required',
		];
		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			$student         = Student::find($id);
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

	/**
	 * Show the application login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogin() {
		return view('auth.login');
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postLogin(Request $request) {
		$this->validate($request, ['username' => 'required', 'password' => 'required']);

		$credentials = [
			'xh' => $request->get('username'),
			'mm' => $request->get('password'),
		];

		if (Auth::attempt($credentials, $request->has('remember'))) {
			return redirect()->intended('/');
		}

		return redirect('/')
			->withInput($request->only('username'))
			->withErrors(['username' => '用户名或密码不正确，请重新输入！']);
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogout() {
		Auth::logout();

		return redirect('/');
	}
}
