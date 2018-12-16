<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="manifest" href="/manifest.json">

    <meta name="apple-mobile-web-app-capable" content="yes">

    <meta name="apple-mobile-web-app-title" content="Timeder">

    <link rel="apple-touch-icon" href="/images/icons/icon-512x512.png" >
    <link rel="apple-touch-icon" sizes="72x72" href="/images/icons/icon-72x72.png">
    <link rel="apple-touch-icon" sizes="128x128" href="/images/icons/icon-128x128.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/icon-152x152.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Responsive task tracker">
    <meta property="og:url" content="https://timeder.kurraz-soft.com/" />
    <meta property="og:image" content="https://timeder.kurraz-soft.com/images/icons/icon-128x128.png" />
    <meta property="og:image:height" content="128" />
    <meta property="og:image:width" content="128" />
    <meta property="og:title" content="Timeder" />
    <meta property="og:description" content="Responsive task tracker" />
    <meta property="og:site_name" content="Timeder" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @auth
    <script>
        var API_URLS = {
            projects: {
                list: '{{ route('projects.index') }}',
                store: '{{ route('projects.store') }}',
                detail: '{{ route('projects.show', '#id#') }}',
            },
            tasks: {
                list: '{{ route('tasks.index') }}',
                store: '{{ route('tasks.store') }}',
                detail: '{{ route('tasks.show', '#id#') }}',
                delete: '{{ route('tasks.destroy', '#id#') }}',
            },
            users: {
                list: '{{ route('users.index') }}',
            },
            files: {
                delete: '{{ route('file.destroy', '#id#') }}',
            }
        };
        var USER = @json(Auth::user());
        var TASK_STATUSES = @json(\App\Task::statusLabelsList());
        var TASK_STATUS_CLOSED = {{ \App\Task::STATUS_CLOSED }};
    </script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @endauth
    @stack('scripts')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                @auth
                <ul class="d-flex list-unstyled align-items-center m-0">
                    <li class="">
                        <project-select></project-select>
                    </li>
                </ul>
                @endauth

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @auth
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item ml-sm-2 mt-2 mt-sm-0">
                            <project-new-form></project-new-form>
                        </li>
                    </ul>
                    @endauth

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">
            @yield('content')
        </main>
    </div>
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js');
    }
</script>
</body>
</html>
