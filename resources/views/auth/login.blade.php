<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gencerling - Generasi Cerdas Lingkungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/f87eaab4e6.js" crossorigin="anonymous"></script>

</head>

<body>
    <style>
        .main-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/img/background-auth.svg');
            background-size: cover;

            background-position: top;
            opacity: 0.7;
            z-index: -1;
        }

        /* For mobile devices */
        @media only screen and (max-width: 768px) {
            #card-content {
                width: 100% !important;
                height: 100vh !important;
                border-radius: 0;
            }

            .card-body {
                padding: 50px 40px 50px !important;
            }

            #main-content {
                height: 100vh;
                overflow: hidden;
            }

            body {
                overflow: hidden;
            }
        }

        /* For larger screens (desktops) */
        @media only screen and (min-width: 769px) {
            #card-content {
                width: 40%;
                border-radius: 30px;
            }

            .card-body {
                padding: 50px 100px 50px !important;
            }
        }
    </style>

    <div class="main-content" id="main-content"
        style="width: 100%; height: 100vh; display: flex; justify-content: center; align-items: center;">
        <div class="card shadow" id="card-content" style="border: none;">

            <div class="card-body ">
                <div class="d-flex justify-content-center align-items-center" style="flex-direction: column">
                    <h1>Masuk</h1>
                    <p>Hallo!, Masukan detail untuk melakukan
                        login ke akun anda</p>
                </div>
                <br>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="login" class="fw-semibold">Username</label>
                        <input
                            style="margin-top:10px; border-radius:15px; height:50px; border: 1px solid rgba(15,97,255,.5)"
                            type="login" class="form-control" id="login" name="login"
                            placeholder="Masukan email atau username" required>
                        <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="fw-semibold">Password</label>
                        <input
                            style="margin-top:10px; border-radius:15px; height:50px; border: 1px solid rgba(15,97,255,.5); padding-right: 40px;"
                            type="password" class="form-control" id="password" name="password"
                            placeholder="Masukan Password" required>
                        <span class="position-absolute"
                            style="top: 70%; right: 20px; transform: translateY(-50%); cursor: pointer; height: 100%; display: flex; align-items: center;"
                            onclick="togglePassword()">
                            <i class="fa fa-eye" id="toggle-eye"></i>
                        </span>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-3 mt-3 d-flex justify-content-between">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Ingat Saya
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="text-decoration:none">
                                <p style="color:#0097FF;">Lupa Kata Sandi?</p>
                            </a>
                        @endif
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" style="border-radius:15px;background:#0097FF;border:none"
                            class="p-3 btn btn-primary">Masuk</button>
                    </div>

                    <center>
                        <p class="mt-4">Belum memiliki Akun ? <a style="color:#0097ff;text-decoration:none;"
                                href="/register">Registrasi</a></p>
                    </center>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

        <script>
            function togglePassword() {
                var passwordField = document.getElementById("password");
                var eyeIcon = document.getElementById("toggle-eye");
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    eyeIcon.classList.remove("fa-eye");
                    eyeIcon.classList.add("fa-eye-slash");
                } else {
                    passwordField.type = "password";
                    eyeIcon.classList.remove("fa-eye-slash");
                    eyeIcon.classList.add("fa-eye");
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
    </div>
</body>
