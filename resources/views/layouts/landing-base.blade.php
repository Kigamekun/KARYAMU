<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gencerling - Generasi Cerdas Lingkungan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/f87eaab4e6.js" crossorigin="anonymous"></script>
    @yield('css')
</head>

<body>
    <style>
        .active {
            color: #19459D !important;
        }

        .btn-primary {
            background-color: #19459D !important;
            border-color: #19459D !important;
        }

        .corner-image {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 280px;
        }

        /* Hide on mobile */
        @media (max-width: 768px) {
            .corner-image {
                display: none;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .corner-image {
                display: none;
            }
        }

        @import url('https://fonts.googleapis.com/css2?family=Cerebri+Sans:wght@700&family=Gordita:wght@700&family=Open+Sans:wght@400&display=swap');

        /* Base Styles */
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        /* Logo Styles */
        .logo,
        .nav-link {
            font-family: 'Cerebri Sans', sans-serif;
        }

        /* Header Styles */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Gordita', sans-serif;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        /* Subheader and Paragraph Styles */
        .subheader {
            font-family: 'Open Sans', sans-serif;
            font-weight: normal;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        p {
            font-family: 'Open Sans', sans-serif;
            font-weight: normal;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
    </style>


    <header class="p-3 mb-3 border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/"
                    style="color:#19459D !important; border-right: 2px solid black;padding-right:20px;font-size: 20px !important"
                    class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="logo" style="width: 120px">
                </a>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0"
                    style="padding-left:20px">
                    <li><a href="/" style="font-size: 20px !important"
                            class="nav-link px-2 link-secondary {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                    </li>
                    <li><a href="/karya-home" style="font-size: 20px !important"
                            class="nav-link px-2 link-secondary {{ request()->routeIs('karya-home.*') ? 'active' : '' }}">Karya</a>
                    </li>
                    <li><a href="/about" style="font-size: 20px !important"
                            class="nav-link px-2 link-secondary {{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a>
                    </li>
                </ul>
                <div class="dropdown text-end">
                    <a href="{{ route('login') }}" style="font-size: 20px !important" class="btn btn-primary">
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </header>
    @yield('content')
    <br>
    <br>
    <br>
    <br>
    <br>
    <div style="width: 80%;margin:auto">
        <footer class="py-5">
            <div class="row">
                <div class="col-6 col-md-2 mb-3">
                    <h5>Kontak dan Alamat</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#"
                                class="nav-link p-0 text-body-secondary">Karya@mail.com</a>
                        </li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">+62 871
                                2313 01213</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Kota Bogor,
                                Bogor, Jawa Barat</a>
                        </li>

                    </ul>
                </div>
                <div class="col-md-5 offset-md-1 mb-3">

                </div>
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
                <p>© 2024 Gencerling, Inc. Semua hak dilindungi.</p>
                <ul class="list-unstyled d-flex">
                    <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24"
                                height="24">
                                <use xlink:href="#twitter"></use>
                            </svg></a></li>
                    <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24"
                                height="24">
                                <use xlink:href="#instagram"></use>
                            </svg></a></li>
                    <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24"
                                height="24">
                                <use xlink:href="#facebook"></use>
                            </svg></a></li>
                </ul>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    @yield('js')
</body>

</html>
