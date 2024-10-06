@extends('layouts.landing-base')

@section('content')

    <style>
        .legend-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
            /* Jarak antara kotak dan label */
        }

        .legend-box {
            width: 20px;
            height: 20px;
            border-radius: 3px;
        }

        .high {
            background-color: #006400;
            /* Hijau tua */
        }

        .medium {
            background-color: #32CD32;
            /* Hijau sedang */
        }

        .low {
            background-color: #90EE90;
            /* Hijau muda */
        }

        .label {
            font-size: 14px;
            color: black;
        }
    </style>
    <div style="width: 80%;margin:auto">
        <div class="card shadow border-none" style="border: none !important;border-radius:30px;">
            <center class="my-3" style="font-size: 30px">
                Eksplorasi karya dari seluruh <span style="color: #0097FF">Indonesia</span>!
            </center>
            <div class="card-body py-3">
                <center>
                    @include('components.map')
                    <div class="legend-container">
                        <div class="legend-item">
                            <div class="legend-box high"></div>
                            <div class="label">Tinggi</div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box medium"></div>
                            <div class="label">Sedang</div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box low"></div>
                            <div class="label">Rendah</div>
                        </div>
                    </div>

                </center>
            </div>
        </div>
        <div class="mt-5">
            @isset($_GET['provinsi'])
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-4">Karya dari Provinsi {{ $_GET['provinsi'] }}</h1>
                    <div>
                        @if (!isset($_GET['sort']) or $_GET['sort'] == 'desc')
                            <button onclick="sortAddParams('asc')" class="btn btn-primary">
                                <i class="fa-solid fa-arrow-down-wide-short"></i> Urutkan Secara Menaik
                            </button>
                        @else
                            <button onclick="sortAddParams('desc')" class="btn btn-primary">
                                <i class="fa-solid fa-arrow-down-short-wide"></i> Urutkan Secara Menurun
                            </button>
                        @endif
                        <button onclick="resetParams()" class="btn btn-primary">
                            Atur Ulang Penyaring
                        </button>
                    </div>
                </div>
            @else
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="mb-4">Karya Terbaru</h1>
                    <div>
                        @if (!isset($_GET['sort']) or $_GET['sort'] == 'desc')
                            <button onclick="sortAddParams('asc')" class="btn btn-primary">
                                <i class="fa-solid fa-arrow-down-wide-short"></i> Urutkan Secara Menaik
                            </button>
                        @else
                            <button onclick="sortAddParams('desc')" class="btn btn-primary">
                                <i class="fa-solid fa-arrow-down-short-wide"></i> Urutkan Secara Menurun
                            </button>
                        @endif
                        <button onclick="resetParams()" class="btn btn-primary">
                            Atur Ulang Penyaring
                        </button>
                    </div>
                </div>
            @endisset
            <br>
            <div class="row">
                @if ($data->isEmpty())
                    <div class="col-12 text-center mt-4">
                        <div class="empty-state">
                            <img src="{{ asset('assets/img/empty.svg') }}" class="w-100" style="max-width: 300px"
                                alt="">
                            <h3 class="mt-5">Tidak ada data yang tersedia</h3>
                            <p>Silakan tambahkan beberapa item untuk ditampilkan di sini.</p>
                        </div>
                    </div>
                @else
                    @foreach ($data as $item)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <x-card :item="$item" />
                        </div>
                    @endforeach
                    <div class="mt-5">
                        {{ $data->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection


@section('js')
    <script>
        function sortAddParams(sort) {
            let url = new URL(window.location.href);
            let search_params = url.searchParams;
            search_params.set('sort', sort);
            url.search = search_params.toString();
            window.location.href = url.toString();
        }

        function resetParams() {
            let url = new URL(window.location.href);
            let search_params = url.searchParams;
            search_params.delete('sort'); // Removes only the 'sort' parameter
            url.search = search_params.toString();
            window.location.href = url.toString();
        }
    </script>
@endsection
