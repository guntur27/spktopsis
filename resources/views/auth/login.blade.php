@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row d-lg-flex justify-content-lg-center">
        <div class="col-lg-6">
            <div class="alert alert-primary" role="alert">
                Default username: <strong>refy</strong> <br>
                Default password: <strong>123456</strong>
            </div>
        </div>
    </div>
    <div class="row d-lg-flex justify-content-lg-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Login</div>

                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input id="username" type="text"
                                class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" name="username"
                                value="{{ old('username') }}" autofocus>

                            @if ($errors->has('username'))
                            <div class="invalid-feedback">
                                {{ $errors->first('username') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                            @if ($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                            @endif
                        </div>

                        {{-- <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>
                        </div> --}}
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection