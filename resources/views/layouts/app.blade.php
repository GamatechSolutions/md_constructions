<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>ZavarivacKG</title>

	<link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon"/>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>
	<script src="{{ asset('js/vendor/adminlte.min.js') }}" defer></script>
	<script src="{{ asset('js/vendor/toastr.min.js') }}" defer></script>

	<!-- CDNS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" rel="stylesheet">

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/vendor/adminlte.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/vendor/toastr.css') }}" rel="stylesheet">

	@stack('script')
	@stack('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">

		@yield('layout')
		
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', () => {
			let type = `{{ session('message.type') }}`;
			let text = `{{ session('message.text') }}`;

			if (text.length) {
				type = type.length ? type : 'success';

				toastr[type](text);
			}
		});
	</script>
</body>

</html>