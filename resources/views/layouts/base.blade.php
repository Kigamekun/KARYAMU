<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>KARYAMU - Aplikasi Management Karya</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('node_modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/owl.carousel/dist/assets/owl.theme.default.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <script src="https://kit.fontawesome.com/f87eaab4e6.js" crossorigin="anonymous"></script>

    @yield('css')
</head>

<body>
    <style>
        .dropify-message p {
            font-size: 14px;
        }

        .trun {
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-line-clamp: 2;
            display: -webkit-box;
            -webkit-box-orient: vertical;
        }

        ..select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0097FF;

        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff
        }
    </style>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                                    class="fas fa-search"></i></a></li>
                    </ul>
                </form>
                @php

                    $notif = DB::table('notifications')
                        ->where('user_id', Auth::id())
                        ->where('is_read', 0)
                        ->orderBy('created_at', 'DESC')
                        ->limit(5)
                        ->get();
                @endphp
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                            class="nav-link notification-toggle nav-link-lg {{ $notif->count() != 0 ? 'beep' : '' }}"><i
                                class="far fa-bell"></i></a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right">
                            <div class="dropdown-header">Notifications
                                <div class="float-right">
                                    <a href="{{ route('notification.mark-as-read') }}">Mark All As Read</a>


                                </div>
                            </div>
                            <div class="dropdown-list-content dropdown-list-icons">
                                @foreach ($notif as $item)
                                    <a href="/karya" class="dropdown-item">
                                        <div class="dropdown-item-icon bg-info text-white">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                        <div class="dropdown-item-desc">
                                            {{ $item->message }}
                                            @php
                                                // Ambil tanggal sekarang
                                                $now = Carbon\Carbon::now();

                                                // Format tanggal dibuat
                                                $createdAt = Carbon\Carbon::parse($item->created_at);
                                                $diffInDays = $now->diffInDays($createdAt);
                                            @endphp

                                            <div class="time">
                                                @if ($diffInDays === 1)
                                                    Yesterday
                                                @elseif ($diffInDays === 2)
                                                    2 days ago
                                                @elseif ($diffInDays > 7)
                                                    {{ $createdAt->format('F j, Y') }}
                                                @else
                                                    {{ $createdAt->diffForHumans() }}
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </li>
                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                                class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" id="log" class="d-none">
                                @csrf
                            </form>
                            <a onclick="document.getElementById('log').submit()"
                                class="dropdown-item has-icon text-danger" style="cursor:pointer">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2 shadow"
                style="border-top-right-radius: 20px;border-bottom-right-radius:20px;">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <div class=" d-flex justify-center w-100">
                            <div class="my-4"
                                style="justify-content:center;align-items:center;width:100%;flex-direction: column;display:flex;">
                                <a href="/" style="font-size: 25px;color:#0097FF">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    KaryaMu</a>
                                <span style="font-size: 10px">Aplikasi Management Karya</span>
                            </div>
                        </div>
                        <div class=" d-flex justify-center w-100">
                            <div class="mt-1 mb-4"
                                style="justify-content:center;align-items:center;width:100%;flex-direction: column;display:flex;">
                                <button class="btn shadow-sm d-flex"
                                    style="border:1px solid rgba(0,0,0,.3); width: 80%;gap:20px;align-items:center">
                                    <i class="fa-solid fa-book-open fa-xl " style="color: #0097FF"></i>
                                    <div style="text-align:left">
                                        Sekolah Dasar
                                        <h6 style="font-size: 15px">
                                            {{ Auth::user()->teacher != null ? Auth::user()->teacher->school->name : '' }}
                                            {{ Auth::user()->student != null ? Auth::user()->student->school->name : '' }}
                                        </h6>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="/" style="color: #0097FF"> <i class="fa-solid fa-graduation-cap"></i>
                        </a>
                    </div>
                    <div class="d-flex align-center justify-between"
                        style="flex-direction: column;justify-content:space-between;height:70vh;">
                        <div class="w-100">
                            <ul class="sidebar-menu">
                                <li class="menu-header">Menu</li>
                                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard') }}" class="nav-link ">
                                        <i class="fa-solid fa-grip ml-3 mr-3"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                @switch(Auth::user()->role)
                                    @case('admin')
                                        <li class="nav-item {{ request()->routeIs('karya.*') ? 'active' : '' }}">
                                            <a href="{{ route('karya.index') }}" class="nav-link ">
                                                <i class="ml-3 mr-3 fa-solid fa-image"></i>
                                                <span>Karya</span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                                            <a href="{{ route('user.index') }}" class="nav-link ">
                                                <i class="ml-3 mr-3 fa-solid fa-user"></i>
                                                <span>User</span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('sekolah.*') ? 'active' : '' }}">
                                            <a href="{{ route('sekolah.index') }}" class="nav-link ">
                                                <i class="ml-3 mr-3 fa-solid fa-school"></i>
                                                <span>Sekolah</span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('pelatihan.*') ? 'active' : '' }}">
                                            <a href="{{ route('pelatihan.index') }}" class="nav-link ">
                                                <i class="ml-3 mr-3 fa-solid fa-file"></i>
                                                <span>Pelatihan</span>
                                            </a>
                                        </li>
                                    @break

                                    @case('teacher')
                                        <li class="nav-item {{ request()->routeIs('karya.*') ? 'active' : '' }}">
                                            <a href="{{ route('karya.index') }}" class="nav-link ">
                                                <i class="ml-3 mr-3 fa-solid fa-image"></i>
                                                <span>Karya</span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                                            <a href="{{ route('user.index') }}" class="nav-link ">
                                                <i class="ml-3 mr-3 fa-solid fa-user"></i>
                                                <span>Siswa</span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ request()->routeIs('pelatihan.*') ? 'active' : '' }}">
                                            <a href="{{ route('pelatihan.index') }}" class="nav-link ">
                                                <i class="ml-3 mr-3 fa-solid fa-file"></i>
                                                <span>Pelatihan</span>
                                            </a>
                                        </li>
                                    @break

                                    @case('student')
                                        <li class="nav-item {{ request()->routeIs('karya.*') ? 'active' : '' }}">
                                            <a href="{{ route('karya.index') }}" class="nav-link ">
                                                <i class="ml-3 mr-3 fa-solid fa-image"></i>
                                                <span>Karya</span>
                                            </a>
                                        </li>
                                    @break

                                    @default
                                @endswitch
                        </div>
                        <div class="w-100">
                            <ul class="sidebar-menu">
                                <li class="nav-item">
                                    <form id="logout-form" action="logout" method="post" style="display: none;">
                                        @csrf
                                    </form>
                                    <a href="#" onclick="document.getElementById('logout-form').submit();"
                                        class="nav-link">
                                        <i class="ml-3 mr-3 fa-solid fa-right-from-bracket"></i>
                                        <span>Logout</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('node_modules/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('node_modules/summernote/dist/summernote-bs4.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <!-- Page Specific JS File -->
    {{-- <script src="{{ asset('assets/js/page/index.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target.closest('form');

            if (form !== null) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus item ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(form.action);
                        // If the form action exists, proceed with submitting the form
                        form.submit();
                    }
                });
            } else {
                console.log("Form tidak ditemukan");
            }
        }
    </script>

    <script>
        function confirmLogout(event) {
            event.preventDefault();
            const form = event.target.closest('form');
            if (form !== null) {
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Apakah Anda yakin ingin keluar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(form.action);
                        form.submit();
                    }
                });
            } else {
                console.log("Form tidak ditemukan");
            }
        }
    </script>

    <script>
        function confirmApprove(event) {
            event.preventDefault();
            const form = event.target.closest('form');
            if (form !== null) {
                Swal.fire({
                    title: 'Konfirmasi Approve',
                    text: 'Apakah Anda yakin approve karya ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log(form.action);
                        form.submit();
                    }
                });
            } else {
                console.log("Form tidak ditemukan");
            }
        }
    </script>

    @if (!is_null(Session::get('message')))
        <script>
            Swal.fire({
                position: 'center',
                icon: @json(Session::get('status')),
                title: @json(Session::get('status')),
                html: @json(Session::get('message')),
                showConfirmButton: false,
                timer: 2000
            })
        </script>
    @endif

    @if (!empty($errors->all()))
        <script>
            var err = @json($errors->all());
            var txt = '';
            Object.keys(err).forEach(element => {
                txt += err[element] + '<br>';
            });
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Error',
                html: txt,
                showConfirmButton: false,
                timer: 4000
            })
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#logoutForm').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Anda yakin ingin keluar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Logout',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).off('submit').submit();
                    }
                });
            });
        });
    </script>

    @yield('js')
</body>

</html>
