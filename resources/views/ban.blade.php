@extends('app')

@section('content')
<div class="panel panel-default">
	<div class="panel body">
		{{ session('message') }}
	</div>
</div>
@stop