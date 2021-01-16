<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
	integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="{{asset('css/breedme.css')}}">
</head>
<body>
    <div id="app">
	<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
			<div class="container">
				<a class="navbar-brand" href="{{ url('/') }}">
					Breed Me
				</a>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<!-- Left Side Of Navbar -->
					<ul class="navbar-nav mr-auto">
						<li class="nav-item active">
							<a class="nav-link" href="{{url('/pets')}}">Pets </a>
						</li>
						<li class="nav-item active">
							<a class="nav-link" href="#">Articles </a>
						</li>
						<li class="nav-item active">
							<a class="nav-link" href="#">Consultation </a>
						</li>
						<li class="nav-item active">
							<a class="nav-link" href="#">Places </a>
						</li>
						<li class="nav-item active">
							<a class="nav-link" href="#">Add Adoption Post</a>
						</li>
					</ul>
			   
					<!-- Right Side Of Navbar -->
					<ul class="navbar-nav ml-auto">
            
					</ul>
				</div>

			</div>
		</nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
