<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sosmed App | Dashboard</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- g-font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Edu+AU+VIC+WA+NT+Hand:wght@400..700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    {{-- custom css --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- sweetalert --}}
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    {{-- fontawesome --}}
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">


</head>

<body data-bs-theme="dark">

    @include('alert')
    <div class="container-fluid ">
        <div class="row d-md-none fixed-top">
            <div class="col-12 py-2 bg-dark">
                <a class="navbar-brand ms-3 title-brand fs-3 fw-semibold text-white" href="#"><i
                        class="bi {{ Route::is('post') ? 'bi-arrow-left me-3' : '' }}"
                        onclick="window.history.go(-1)"></i>{{ $title }}</a>

            </div>
        </div>
        <div class="row min-vh-100 my-5 my-md-0">
            <div
                class="col-1 col-lg-2 border-end border-secondary vh-100 sticky-top py-4 d-none d-md-flex flex-column justify-content-between align-items-center align-items-lg-start">
                <div class="d-flex flex-column gap-4">
                    <a class="navbar-brand title-brand fs-3 fw-semibold d-none d-lg-block text-white"
                        href="#">SocialApp</a>
                    <a class="navbar-brand title-brand fs-3 fw-semibold d-block d-lg-none text-center"
                        href="#"><img src="{{ asset('logo.png') }}" alt="SocialApp" class="col-7"></a>

                    <ul class="navbar-nav text-center text-lg-start">
                        <li class="nav-item"><a href="{{ route('home') }}"
                                class="nav-link {{ Route::is('home') ? 'active' : '' }}"><i
                                    class="bi {{ Route::is('home') ? 'bi-house-door-fill' : 'bi-house-door' }} align-middle fs-4 me-lg-3"></i>
                                <span class="d-none d-lg-inline">Home</span></a></li>
                        <li class="nav-item"><a href="{{ route('explore') }}"
                                class="nav-link {{ request()->is('explore') ? 'active' : '' }}"><i
                                    class="bi bi-search {{ request()->is('explore') ? 'text-white' : '' }} align-middle fs-4 me-lg-3"></i>
                                <span class="d-none d-lg-inline">Search</span></a>
                        </li>
                        <li class="nav-item"><a href="{{ route('notification') }}"
                                class="nav-link {{ Route::is('notification') ? 'active' : '' }}"><i
                                    class="bi {{ Route::is('notification') ? 'bi-heart-fill' : 'bi-heart' }} align-middle fs-4 me-lg-3"></i>
                                <span class="d-none d-lg-inline">Notification</span></a>
                        </li>
                        <li class="nav-item"><a href="{{ route('profile', ['id' => $user->id]) }}"
                                class="nav-link d-flex gap-2 {{ request()->is('profile/' . $user->id) ? 'active' : '' }}">
                                <div class="col-12 col-md-10 col-lg-3">
                                    <div class="ratio ratio-1x1">
                                        @if ($user->img)
                                            <img src="{{ asset($user->img) }}" alt=""
                                                class="rounded-circle img-fluid" style="object-fit: cover">
                                        @else
                                            <img src="{{ asset('blank-profile.jpg') }}" alt=""
                                                class="rounded-circle img-fluid" style="object-fit: cover">
                                        @endif
                                    </div>
                                </div>
                                <span class="d-none d-lg-inline align-self-center">Profile</span>
                            </a></li>
                    </ul>
                </div>
                <ul class="navbar-nav text-center">
                    <li>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-list fs-3"></i>
                            </button>
                            <ul class="dropdown-menu">
                                {{-- <li><a href="{{ route('doLogout') }}" class="nav-link "><i
                                            class="bi bi-box-arrow-right align-middle"></i> <span
                                            class="d-none d-lg-inline">Log Out</span></a></li> --}}
                                <li><button class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#privacy">Account Privacy</button></li>
                                <li><button class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#editPassword">Change Password</button></li>
                                <li><a class="dropdown-item" href="{{ route('doLogout') }}">Log Out</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item"></li>
                </ul>
            </div>
            <div class="col-12 col-md-9 col-xl-10 py-2 py-lg-4 px-0 mx-auto">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Modal Change Password-->
    <div class="modal fade" id="editPassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Change Password</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('edit-password') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $user->id }}" name="id" class="form-control">
                        <div class="form-group mb-3">
                            <label for="">Old Password</label>
                            <input type="text" class="form-control" rows="5" name="current_password">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">New Password</label>
                            <input type="text" class="form-control" rows="5" name="new_password">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-light col-12">Change Password</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
    <!-- Modal Account Privacy-->
    <div class="modal fade" id="privacy" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Account Privacy</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <p class="align-self-center align-middle mb-0">Private Account</p>
                        <div class="form-check form-switch fs-5">
                            <input class="form-check-input" type="checkbox" role="switch"
                                id="flexSwitchCheckChecked" {{ Auth::user()->private ? 'checked' : '' }}>
                        </div>

                    </div>
                    <p class="text-muted fs-7">When your account is public, your profile and posts can be seen by
                        anyone.
                        When your account is private, only the followers you approve can see what you share.</p>


                </div>
            </div>
        </div>
    </div>
    {{-- footer --}}
    <div class="fixed-bottom d-block d-md-none border-top border-secondary py-2 bg-dark m-0">
        <div class="d-flex justify-content-evenly">
            <a href="{{ route('home') }}" class="nav-link align-self-center"><i
                    class=" fs-4 bi bi-house-door{{ Route::is('home') ? '-fill' : '' }}"></i></a>
            <a href="{{ route('explore') }}" class="nav-link align-self-center"><i
                    class=" fs-4 bi bi-search{{ Route::is('explore') ? '-heart' : '' }}"></i></a>
            <a href="{{ route('notification') }}" class="nav-link align-self-center"><i
                    class=" fs-4 bi bi-heart{{ Route::is('notification') ? '-fill' : '' }}"></i></a>
            <a href="{{ route('profile', ['id' => $user->id]) }}" class="nav-link align-self-center">
                @if ($user->img)
                    <img src="{{ asset($user->img) }}" alt="" class="rounded-circle"
                        style="object-fit: cover" width="35px" height="35px">
                @else
                    <img src="{{ asset('blank-profile.jpg') }}" alt="" class="rounded-circle"
                        width="35px" height="35px" style="object-fit: cover">
                @endif

            </a>

            <div class="dropdown">
                <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-list fs-3"></i>
                </button>
                <ul class="dropdown-menu">
                    {{-- <li><a href="{{ route('doLogout') }}" class="nav-link "><i
                                            class="bi bi-box-arrow-right align-middle"></i> <span
                                            class="d-none d-lg-inline">Log Out</span></a></li> --}}
                    <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#privacy">Account
                            Privacy</button></li>
                    <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editPassword">Change
                            Password</button></li>
                    <li><a class="dropdown-item" href="{{ route('doLogout') }}">Log Out</a></li>
                </ul>
            </div>
        </div>

    </div>
    @yield('script')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript" src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSwitch = document.getElementById('flexSwitchCheckChecked');

            toggleSwitch.addEventListener('change', function() {
                const isPrivate = toggleSwitch.checked ? 1 : 0;

                fetch('{{ route('toggle.private') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            private: isPrivate
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Status akun privat berhasil diubah.');
                        } else {
                            console.error('Gagal mengubah status akun privat.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>



</body>

</html>
