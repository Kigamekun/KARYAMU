@extends('layouts.landing-base')

@section('css')
    <script src="https://unpkg.com/slim-select@latest/dist/slimselect.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/slim-select@latest/dist/slimselect.css" rel="stylesheet">
    </link>
@endsection

@section('content')
    <div style="width: 80%;margin:auto">
        <div class="card shadow border-none" style="border: none !important;border-radius:30px;">
            <center class="my-3" style="font-size: 30px">
                Eksplor karya dari seluruh <span style="color: #0097FF">Indonesia</span>!
            </center>
            <div class="card-body py-3">
                <center>
                    @include('components.map')
                </center>
            </div>
        </div>
    </div>
    <div class="mt-5" style="width: 80%;margin:auto">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card" style="border-radius:15px;">
                    <div class="card-body">
                        <h5 class="card-title
                        ">Filter</h5>
                        <br>
                        <form action="{{ route('karya-home.filter') }}" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="search" placeholder="Search"
                                    aria-label="Search" aria-describedby="button-addon2">
                            </div>
                            <div class="accordion w-100" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Filter by
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <h6>Sekolah</h6>
                                            <select id="selectElement" class="w-100 mb-2 " name="schools[]" multiple>
                                                {{-- @foreach (DB::table('schools')->get() as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach --}}

                                            </select>
                                            <br>
                                            <br>
                                            <h6>Tipe</h6>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="type[]"
                                                    value="image" id="flexCheckDefault">
                                                <label class="form-check-label" for="flexCheckDefault">Gambar</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="type[]"
                                                    value="video" id="flexCheckChecked" checked>
                                                <label class="form-check-label" for="flexCheckChecked">Video</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3 w-100">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-12">
                @isset($_GET['provinsi'])
                    <h1 class="mb-4">Semua Karya Provinsi {{ $_GET['provinsi'] }}</h1>
                @else
                    <h1 class="mb-4">Semua Karya</h1>
                @endisset
                <div class="row ">
                    @if ($data->isEmpty())
                        <div class="col-12 text-center mt-4">
                            <div class="empty-state">
                                <img src="{{ asset('assets/img/empty.svg') }}" alt="">
                                <h3 class="mt-5">Tidak ada data yang tersedia</h3>
                                <p>Silakan tambahkan beberapa item untuk ditampilkan di sini.</p>
                            </div>
                        </div>
                    @else
                        @foreach ($data as $item)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <x-card :item="$item" :height="'200px'" />
                            </div>
                        @endforeach
                        <div class="mt-5">
                            {{ $data->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('#selectElement').select2({
        ajax: {
            url: '/get-schools', // URL endpoint Anda untuk mengambil data sekolah
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
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

@endsection
