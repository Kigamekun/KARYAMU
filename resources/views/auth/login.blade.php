<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KARYAMU - Aplikasi Management Karya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
    </style>
    <div class="main-content" style="width: 100%;height:100vh;display:flex;justify-content:center;align-items:center; ">
        <div class="card shadow" style="border:none;width:40%;border-radius:30px">
            <div class="card-body " style="padding: 50px 100px 50px">
                <div class="d-flex justify-content-center align-items-center" style="flex-direction: column">
                    <h1>Login</h1>
                    <p>Hallo!, Masukan detail untuk melakukan
                        login ke akun anda</p>
                </div>
                <br>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="login" class="fw-semibold">login/Username</label>
                        <input
                            style="margin-top:10px; border-radius:15px; height:50px; border: 1px solid rgba(15,97,255,.5)"
                            type="login" class="form-control" id="login" name="login"
                            placeholder="Masukan email atau username" required>
                        <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="fw-semibold">Password</label>
                        <input
                            style="margin-top:10px; border-radius:15px; height:50px; border: 1px solid rgba(15,97,255,.5)"
                            type="password" class="form-control" id="password" name="password"
                            placeholder="Masukan Password" required>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="mb-3 mt-3 d-flex justify-content-between">
                        <div class="form-check
                        ">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Remember me
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="text-decoration:none">
                                <p style="color:#0097FF;">Forgot Password?</p>
                            </a>
                        @endif
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" style="border-radius:15px;background:#0097FF;border:none"
                            class="p-3 btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
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
</body>

</html>
