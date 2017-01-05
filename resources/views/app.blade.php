<!DOCTYPE html>
<html lang="zh">
	<head>
		<meta charset="gbk">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>广西师范大学辅修专业学士学位报名系统</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
		<!-- Optional theme -->
		<link rel="stylesheet" href="{{ asset('css/bootstrap-theme.min.css') }}">
	</head>
	<body>
		<header id="header" class="page-header">
			<h1 class="text-center">{{ $title or '广西师范大学' }}</h1>
		</header><!-- /header -->

		<main class="container">
            @if (session('status'))
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>注意：</strong>出错啦！
                    <<ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
			@yield('content')
		</main>

		<footer id="footer" class="page-footer">
			<address>
				&copy 2015{{ '2015' == date('Y') ? '' : '-' . date('Y') }} 广西师范大学教务处.版权所有.
			</address>
		</footer>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="{{ asset('js/jquery.min.js') }}"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<!-- Latest compiled and minified JavaScript -->
		<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	</body>
</html>
