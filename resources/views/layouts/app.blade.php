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
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <style>
        .float_right{
            float: right;
        }

        .modal-header{
            height: 45px;
            background-color: aliceblue;
        }

        .modal-body .mb-3{
            margin-top: -10px!important;
        }

        .card-header{
            background-color: aliceblue;
        }

        .error_msg{
            border: 1px solid red;
        }

        .card_btn_xs{
            padding-top: 1px;
            padding-bottom: 0px;
        }

        .table thead{
            background-color: darkgray;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ URL::to('/category') }}">{{ __('Category') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ URL::to('/blog') }}">{{ __('Blog Post') }}</a>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ URL::to('/permission-group') }}">{{ __('Permission Group') }}</a>
                                    <a class="dropdown-item" href="{{ URL::to('/permission') }}">{{ __('Permission') }}</a>
                                    <a class="dropdown-item" href="{{ URL::to('/role') }}">{{ __('Role') }}</a>
                                    <a class="dropdown-item" href="{{ URL::to('/user') }}">{{ __('User') }}</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function formValidation(formId){
            event.preventDefault();
			var fail = false;
			$('#' + formId).find('select, textarea, input').removeClass('error_msg');
			$('#' + formId).find('select, textarea, input').each(function () {
				if ($(this).prop('required')) {
					if (!$(this).val()) {
						fail = true;
						name = $(this).attr('id');
						$('#' + name).addClass('error_msg');
					}
				}
			});
            return fail;
        }

        function successAlert(msg){
            Swal.fire({
			  position: 'top-end',
			  icon: 'success',
			  title: msg,
			  showConfirmButton: false,
			  timer: 1500
			})
        }

        function errorAlert(msg){
            Swal.fire({
			  position: 'top-end',
			  icon: 'error',
			  title: msg,
			  showConfirmButton: false,
			  timer: 1500
			})
        }
    </script>
    @stack('scripts')
</body>
</html>
