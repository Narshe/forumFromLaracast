<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Forum') }}</title>


    <script>

            window.App = {!! json_encode([

                'signedIn' =>  Auth::check(),
                'user' => Auth::user()

            ]) !!}

    </script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.2/trix.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/at.js/1.5.4/css/jquery.atwho.css">
    <style>
        [v-cloak] { display: none; }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/threads') }}">
                    {{ config('app.name', 'Forum') }}
                </a>
                <div class="dropdown">

                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Browse
                    </button>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/threads">All threads</a>
                        @auth
                            <a class="dropdown-item" href="/threads?by={{ auth()->user()->name}}">My thread</a>
                        @endauth
                        <a class="dropdown-item" href="/threads?popularity=1">Popular threads</a>
                        <a class="dropdown-item" href="/threads?unanswered=1">Unanswered threads</a>
                    </div>
                </div>

                <a class="nav-link" href="/threads">
                    All Threads
                </a>

                @auth
                    <a class="nav-link" href="/threads/create">
                        Create a thread
                    </a>
                @endauth
                <div class="dropdown">

                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Channels
                  </button>

                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @foreach ($channels as $channel)
                        <a class="dropdown-item" href="/threads/{{$channel->slug}}">{{$channel->name }}</a>
                    @endforeach
                  </div>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>

                        @else
                            <user-notifications></user-notifications>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile', Auth::user())}}">Profile</a>
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

        <main id="app" class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <flash message="{{ session('flash') }}"></flash>
                    </div>
                </div>
                @yield('content')
            </div>
        </main>
    </div>
    @yield('script')
</body>
</html>
