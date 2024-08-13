@extends('layouts.auth')
@section('content')
    <h3 class="text-white fw-normal text-center pb-2">Welcome Back</h3>
    <div class="col-12 text-center pb-3">
        <span class="text-muted text-center subtitle-auth ">Don't have an account yet? <a href="{{ route('register') }}"
                class="text-center text-light d-inline link-light link-underline link-underline-opacity-0 link-underline-opacity-75-hover">Sign
                Up</a></span>
    </div>
    <form action="{{ route('doLogin') }}" method="post">
        @csrf

        <div class="form-floating mb-3">
            <input type="text" class="form-control border-0 border-bottom rounded-0" id="username" name="name"
                placeholder="Username">
            <label for="username">Username</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control border-0 border-bottom rounded-0" id="password" name="password"
                placeholder="password">
            <label for="password">Password</label>
        </div>
        <button class="btn btn-light col-12 my-3 fw-bold" type="submit">Log in</button>
        <a href="{{ route('resetPassword') }}" class="nav-link text-muted text-center">Forgot Password?</a>
    </form>
@endsection
