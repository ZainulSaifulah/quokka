<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Quokka</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

        <!-- Styles -->
        <style>
            body{
                background-color: #f4f6f9;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">


            <nav class="navbar navbar-expand-lg navbar-white">
                <a class="navbar-brand" href="">
                    <img src="{{url('assets/logo.png')}}" class="d-inline-block align-top" alt="quokka logo" width="30" height="30">
                    Quokka
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>

                @if (Route::has('login'))
                    <div class="top-right links">
                        @auth
                            <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            >
                                {{ __('adminlte::adminlte.log_out') }}
                            </a>
                            <form id="logout-form" action="logout" method="POST" style="display: none;">
                                @if(config('adminlte.logout_method'))
                                    {{ method_field(config('adminlte.logout_method')) }}
                                @endif
                                {{ csrf_field() }}
                            </form>
                        @else
                            <a href="{{ route('login') }}">Login</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
              </nav>
        </div>
    </body>
</html>
