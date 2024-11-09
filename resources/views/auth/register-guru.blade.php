<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gencerling - Generasi Cerdas Lingkungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/f87eaab4e6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                border-radius: 0;
            }

            .card-body {
                padding: 200px 40px 50px !important;
            }
        }

        /* For larger screens (desktops) */
        @media only screen and (min-width: 769px) {
            #card-content {
                width: 40%;
                border-radius: 30px;
            }

            .card-body {
                padding: 25px 50px 25px !important;
            }
        }
    </style>

    <div class="main-content" id="main-content"
        style="width: 100%; height: 100vh; display: flex; justify-content: center; align-items: center;">
        <div class="card shadow" id="card-content" style="border: none;">
            <div class="card-body ">
                <div class="d-flex justify-content-center align-items-start" style="flex-direction: column">
                    <h1>Registrasi</h1>
                    <span>Masukan data untuk melakukan registrasi akun.</span>
                    <small id="emailHelp" class="form-text text-muted">Field dengan tanda <span
                            class="text-danger">*</span> wajib diisi.</small>

                </div>
                <br>
                <form method="POST" action="{{ route('register.create-guru') }}">
                    @csrf
                    @if (isset($_GET['fr']))
                        <input type="hidden" name="fr" value="{{ $_GET['fr'] }}">
                    @endif

                    <div class="col mb-3">
                        <label for="name" class="form-label">Nama<span class="ms-1 text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="username" class="form-label">Username<span
                                    class="ms-1 text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="{{ old('username') }}" required>
                        </div>
                        <div class="col mb-3">
                            <label for="email" class="form-label">Email<span
                                    class="ms-1 text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="password" class="form-label">Password<span
                                    class="ms-1 text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password"
                                    value="{{ old('password') }}" required>
                                <span class="input-group-text" onclick="togglePassword()" id="toggle-password"
                                    style="cursor: pointer;">
                                    <i class="fa fa-eye" id="toggle-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3 col">
                            <label for="password" class="form-label">Konfirmasi Password<span
                                    class="ms-1 text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" value="{{ old('password_confirmation') }}" required>
                                <span class="input-group-text" onclick="togglePasswordConfirmation()"
                                    id="toggle-password-confirmation" style="cursor: pointer;">
                                    <i class="fa fa-eye" id="toggle-eye-confirmation"></i>
                                </span>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="nip" class="form-label">NIK<span class="ms-1 text-danger">*</span></label>
                            <input type="text" class="form-control" id="nip" name="nip"
                                value="{{ old('nip') }}" required>
                        </div>
                        <div class="mb-3 col">
                            <label for="school_id" class="form-label">
                                Sekolah <span class="ms-1 text-danger">*</span>
                            </label>
                            <select class="form-select" id="school_id" name="school_id" required>
                                <option value="" {{ old('school_id') == '' ? 'selected' : '' }}>Pilih Sekolah
                                </option>
                                {{-- @foreach (DB::table('schools')->get() as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('school_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach --}}
                            </select>
                        </div>

                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat<span
                                class="ms-1 text-danger">*</span></label>
                        <textarea name="address" class="form-control" id="address" cols="30" rows="2">{{ old('address') }}</textarea>

                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">No Telephone<span
                                class="ms-1 text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" value="{{ old('phone') }}"
                            name="phone">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" style="border-radius:15px;background:#19459D;border:none"
                            class="p-3 btn btn-primary">Registrasi</button>
                    </div>

                    <center>
                        <p class="mt-4">Sudah memiliki Akun ? <a style="color:#19459D;text-decoration:none;"
                                href="/login">Masuk</a></p>
                        <p class="mt-1">Data Sekolah belum terdaftar ? <a
                                style="color:#19459D;text-decoration:none;" href="/register-sekolah">Registrasi
                                Sekolah</a></p>
                    </center>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

        <script>
            function togglePasswordConfirmation() {
                var passwordField = document.getElementById("password_confirmation");
                var eyeIcon = document.getElementById("toggle-eye-confirmation");
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

        <script>
            $('#school_id').select2({
                ajax: {
                    url: '/get-schools', // URL endpoint Anda untuk mengambil data sekolah
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.name
                            }))
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3, // Pengguna harus mengetik minimal 3 karakter sebelum data dimuat
                placeholder: 'Pilih sekolah',
                allowClear: true
            });
        </script>
</body>

</html>
