@extends('app')

@section('content')
<div class="text-right">
	欢迎{{ $profile->xh . $profile->xm }}同学使用系统，<a href="{{ url('user/logout') }}">登出系统</a>
</div>
<div class="panel panel-primary">
	<div class="panel-heading">
		<div class="panel-title text-center">
			<h2>
			@if (empty($student))
				欢迎{{ $profile->xm }}同学报名双学士学位
			@else
				欢迎{{ $student->c_xh . iconv('GBK', 'UTF-8', $student->c_xm) }}同学报名{{ iconv('GBK', 'UTF-8', $student->yxmc) }}{{ iconv('GBK', 'UTF-8', $student->zymc) }}专业<br>
				联系电话：{{ $student->c_lxdh }}&nbsp;&nbsp;身份证号码：{{ $student->c_sfzh }}
			@endif
			</h2>
		</div>
	</div>
	<div class="panel-body">
		<p>1.<a href="http://www.dean.gxnu.edu.cn/wp-content/uploads/2014/12/%E9%99%84%E4%BB%B62%E5%B9%BF%E8%A5%BF%E5%B8%88%E8%8C%83%E5%A4%A7%E5%AD%A6%E5%85%A8%E6%97%A5%E5%88%B6%E6%99%AE%E9%80%9A%E6%9C%AC%E7%A7%91%E2%80%9C%E5%8F%8C%E5%AD%A6%E5%A3%AB%E5%AD%A6%E4%BD%8D%E2%80%9D%E6%95%99%E8%82%B2%E7%94%B3%E8%AF%B7%E8%A1%A8.doc" target="_blank">&nbsp;广西师范大学全日制普通本科“双学士学位”教育申请表</a></p>
		<p>2.<a href="http://www.dean.gxnu.edu.cn/wp-content/uploads/2014/12/%E9%99%84%E4%BB%B61%E5%B9%BF%E8%A5%BF%E5%B8%88%E8%8C%83%E5%A4%A7%E5%AD%A62015%E5%B9%B4%E2%80%9C%E5%8F%8C%E5%AD%A6%E5%A3%AB%E5%AD%A6%E4%BD%8D%E2%80%9D%E6%95%99%E8%82%B2%E6%8B%9B%E7%94%9F%E7%AE%80%E7%AB%A01.doc" target="_blank">《广西师范大学2015年“双学士学位”教育招生简章》</a></p>
		<form action="{{ empty($student) ? url('user/store') : url('user/update', $profile->xh) }}" method="POST" class="form-horizontal" role="form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label for="phone" class="col-sm-2 control-label">电话号码（必须填写）</label>
				<div class="col-sm-10">
				 	<input type="text" name="phone" id="phone" class="form-control" placeholder="电话号码"
				 	@unless (empty($student))
				 		value="{{ $student->c_lxdh }}"
				 	@endunless
				 	>
				</div>
			</div>
			<div class="form-group">
				<label for="major" class="col-sm-2 control-label">报名双学位专业</label>
				<div class="col-sm-10">
					<select name="major" id="major" class="form-control">
						@foreach ($majors as $major)
							<option value="{{ $major->c_zy }}"
							@unless (empty($student))
								{{ $student->c_zyh == $major->c_zy ? ' selected' : '' }}
							@endunless
							>{{ iconv('GBK', 'UTF-8', $major->c_mc) }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10 text-center">
					@if (empty($student))
						<button type="submit" class="btn btn-primary">报名</button>
					@else
						<button type="submit" class="btn btn-primary">修改</button>
					@endif
				</div>
			</div>
		</form>
	</div>
</div>
@stop