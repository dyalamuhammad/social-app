<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    {{-- bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- g-font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Edu+AU+VIC+WA+NT+Hand:wght@400..700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    {{-- custom css --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- sweetalert --}}
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
</head>

<body data-bs-theme="dark">
    @include('alert')
    <div class="container">
        <div class="d-flex flex-column justify-content-center align-items-center vh-100">
            <div class="col-12 mb-0">
                <h1 class="text-center title-brand fw-semibold text-white">SocialApp</h1>
            </div>
            <div class="card card-primary col-12 col-lg-4 py-5 mx-auto mt-0">
                <div class="card-body">

                    <h3 class="text-white fw-normal text-center pb-2">Welcome Back</h3>
                    <div class="col-12 text-center pb-3">
                        <span class="text-muted text-center subtitle-auth ">Don't have an account yet? <a
                                href="{{ route('register') }}"
                                class="text-center text-light d-inline link-light link-underline link-underline-opacity-0 link-underline-opacity-75-hover">Sign
                                Up</a></span>
                    </div>
                    <form action="{{ route('doLogin') }}" method="post">
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0 border-bottom rounded-0" id="username"
                                name="name" placeholder="Username">
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control border-0 border-bottom rounded-0" id="password"
                                name="password" placeholder="password">
                            <label for="password">Password</label>
                        </div>
                        <button class="btn btn-light col-12 mt-3 fw-bold" type="submit">Log in</button>
                    </form>

                </div>
            </div>

        </div>
    </div>

    {{-- script --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
</body>

</html>
