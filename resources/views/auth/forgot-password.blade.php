{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gencerling - Generasi Cerdas Lingkungan</title>
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
                    <h1>Lupa Kata Sandi</h1>
                    <p class="mt-3" style="text-align: justify">Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu
                        kami alamat email Anda dan kami akan mengirimkan email berisi tautan pengaturan ulang kata sandi
                        yang memungkinkan Anda memilih yang baru.</p>
                </div>
                <br>
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="form-control" type="email" name="email"
                            :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="d-flex items-center w-100 justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            Kirim Email Pengaturan Ulang Kata Sandi
                        </button>
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
