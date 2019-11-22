<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                Sistem Penunjang Keputusan
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-navbar-collapse"
                aria-controls="app-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @if (Auth::check())
                    <li
                        class="nav-item
                        {{ request()->is('/') || request()->is('alternatif') || request()->is('alternatif/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('alternatif.index') }}">Alternatif</a></li>
                    <li class="nav-item {{ request()->is('ranking') || request()->is('ranking/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('ranking.index') }}">Hasil</a></li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-expanded="false" aria-haspopup="true">
                            {{ Auth::user()->nama }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="text-muted mt-4 bg-light py-4">
        <div class="container">
            <p class="float-right">
                <a class="btn btn-small btn-primary rounded-pill" href="#">&#94; Back to top</a>
            </p>
            <p>
                Sistem Penunjang Keputsan <br>
                Pengangkatan karyawan honorer menjadi PNS
            </p>
            <small>
                Oleh: <br>
                Muhammad Refy Bahar Gussali <span class="text-danger">&#x2764;</span> 2016141478 <br>
                2019
            </small>
        </div>
    </footer>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    <script src="{{ asset('js/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    @stack('scripts')
</body>

</html>