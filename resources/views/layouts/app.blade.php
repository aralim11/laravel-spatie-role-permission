<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}-@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">



    <!-- CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
                                    @if(Auth::User()->can('permission.group.view'))<a class="dropdown-item" href="{{ URL::to('/permission-group') }}">{{ __('Permission Group') }}</a>@endif
                                    <a class="dropdown-item" href="{{ URL::to('/permission') }}">{{ __('Permission') }}</a>
                                    <a class="dropdown-item" href="{{ URL::to('/role') }}">{{ __('Role') }}</a>
                                    <a class="dropdown-item" href="{{ URL::to('/users') }}">{{ __('User') }}</a>
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

    <div class="modal fade" id="mainModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="main_modal_content">

            </div>
        </div>
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

        function checkBoxNullValidation(){
            var permissionArray = [];
            $.each($("input[name='checkPermission']:checked"), function(){
                permissionArray.push($(this).val());
            });

            if (permissionArray.length === 0) {
                $('.form-check-input').addClass('error_msg');
                return false;
            } else {
                return permissionArray;
            }
        }

        function edit_checkBoxNullValidation(){
            var permissionArray = [];
            $.each($("input[name='edit_checkPermission']:checked"), function(){
                permissionArray.push($(this).val());
            });

            if (permissionArray.length === 0) {
                $('.edit-fom-check').addClass('error_msg');
                return false;
            } else {
                return permissionArray;
            }
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
