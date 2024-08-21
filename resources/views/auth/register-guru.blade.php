{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
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
                    <h1>Register</h1>
                    <p>Hallo!, Masukan detail untuk melakukan
                        registrasi akun</p>
                </div>
                <br>
                <form method="POST" action="{{ route('register.create-guru') }}">
                    @csrf
                    <div class="col mb-3">
                        <label for="name" class="form-label">Name<span
                            class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="username" class="form-label">Username<span
                                class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="col mb-3">
                            <label for="email" class="form-label">Email<span
                                class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="password" class="form-label">Password<span
                                class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3 col">
                            <label for="password_confirmation" class="form-label">Confirm Password<span
                                class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="nip" class="form-label">NIP<span
                                class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nip" name="nip" required>
                        </div>
                        <div class="mb-3 col">
                            <label for="school_id" class="form-label
                                ">Sekolah <span
                                class="text-danger">*</span></label>
                            <select class="form-select" id="school_id" name="school_id" required>
                                <option value="" selected>Pilih Sekolah</option>
                                @foreach (DB::table('schools')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address<span
                            class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone<span
                            class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
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
                            class="p-3 btn btn-primary">Register</button>
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
