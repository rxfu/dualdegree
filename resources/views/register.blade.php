@extends('app')

@section('content')
<div class="text-right">
	欢迎{{ Auth::user()->xh }}使用系统，<a href="{{ url('auth/logout') }}">登出系统</a>
</div>
<form action="{{ url('user/store') }}" method="POST" class="form-horizontal" role="form">
	{!! csrf_field() !!}
	<div class="form-group">
		<label for="phone" class="col-sm-2 control-label">电话号码</label>
		<div class="col-sm-10">
		 	<input type="text" name="phone" id="phone" class="form-control" placeholder="电话号码">
		</div>
	</div>
	<div class="form-group">
		<label for="major" class="col-sm-2 control-label">报名双学位专业</label>
		<div class="col-sm-10">
			<select name="major" id="major" class="form-control">
				<option value="major">major</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10 text-center">
			<button type="submit" class="btn btn-primary">提交</button>
			<button type="reset" class="btn btn-default">重置</button>
		</div>
	</div>
</form>
@stop