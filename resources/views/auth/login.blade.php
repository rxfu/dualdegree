@extends('app')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">登录系统</div>
	<div class="panel-body">
		<form action="{{ url('auth/login') }}" method="POST" class="form-horizontal" role="form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label for="username" class="col-sm-2 control-label">用户名</label>
				<div class="col-sm-10">
				 	<input type="text" name="username" id="username" class="form-control" placeholder="用户名">
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">密码</label>
				<div class="col-sm-10">
				 	<input type="password" name="password" id="password" class="form-control" placeholder="密码">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10 text-center">
					<button type="submit" class="btn btn-primary">登录</button>
				</div>
			</div>
		</form>
	</div>
</div>
@stop