@extends('layouts.auth')
@section('content')
    <h3 class="text-white  fw-normal text-center pb-2">Create Account</h3>
    <div class="col-12 text-center pb-3">
        <span class="text-muted text-center subtitle-auth">Already Registered? <a href="{{ route('login') }}"
                class="text-light d-inline link-light link-underline link-underline-opacity-0 link-underline-opacity-75-hover">Sign
                In</a></span>
    </div>
    <form action="{{ route('doRegis') }}" method="POST">
        @csrf
        <div class="form-floating mb-3">
            <input type="text" class="form-control border-0 border-bottom rounded-0" id="email" name="email"
                placeholder="email">
            <label for="email">Email</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control border-0 border-bottom rounded-0" id="username" name="name"
                placeholder="username">
            <label for="username">Username</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control border-0 border-bottom rounded-0" id="password" name="password"
                placeholder="password">
            <label for="password">Password</label>
        </div>
        <button class="btn btn-light fw-bold col-12 mb-3">Sign Up</button>
    </form>
@endsection
