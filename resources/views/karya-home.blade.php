@extends('layouts.landing-base')

@section('css')
    <script src="https://unpkg.com/slim-select@latest/dist/slimselect.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/slim-select@latest/dist/slimselect.css" rel="stylesheet">
    </link>
@endsection

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
        }

        .legend-box {
            width: 20px;
            height: 20px;
            border-radius: 3px;
        }

        .high {
            background-color: #006400;
        }

        .medium {
            background-color: #32CD32;
        }

        .low {
            background-color: #90EE90;
        }

        .label {
            font-size: 14px;
            color: black;
        }
    </style>
    <div style="width: 80%;margin:auto">
                <div class="py-4">
            <img src="{{asset('assets/img/header-atass.png')}}" style="border-radius: 15px;width:100%;width: 100%; height: 280px; object-fit: cover; object-position: center;" alt="">
        </div>

        <div class="card shadow border-none" style="border: none !important;border-radius:30px;">
            <center class="my-3 logo" style="font-weight:600; font-size: 20px">
                Eksplorasi karya dari seluruh <span style="color: #19459D">Indonesia</span>!
            </center>
            <div class="card-body py-3" style="position: relative;">
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
                <img src="{{ asset('assets/img/dodo.png') }}" alt="Image" class="corner-image">
            </div>
        </div>
    </div>
    <div class="mt-5" style="width: 80%;margin:auto">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card" style="border-radius:15px;">
                    <div class="card-body">
                        <h5 class="card-title
                        ">Penyaring</h5>
                        <br>
                        <form action="{{ route('karya-home.filter') }}" method="GET">
                            <div class="accordion w-100" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Penyaring Berdasarkan
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show"
            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <h6>Sekolah</h6>
                
                @php
                    if(isset($_GET['schools'])){
                    $schools = $schools = DB::table('schools')
            ->whereIn('id', $_GET['schools'])
            ->get();
           
                    } else {
                    $schools = [];
                    }
                @endphp
              
                <select id="selectElement" class="w-100 mb-2" name="schools[]" multiple>
                </select>

                <br><br>

                <h6>Category</h6>
                @foreach(DB::table('categories')->get() as $ct)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="categories[]"
                        value="{{ $ct->id }}" id="flexCheckDefault_{{ $ct->id }}"
                        {{ in_array($ct->id, old('categories', request()->categories ?? [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexCheckDefault_{{ $ct->id }}">
                        {{$ct->name}}
                    </label>
                </div>
                @endforeach
                <br>

                <h6>Tipe</h6>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="type[]"
                        value="image" id="flexCheckDefaultImage"
                        {{ in_array('image', old('type', request()->type ?? [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexCheckDefaultImage">Gambar</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="type[]"
                        value="video" id="flexCheckDefaultVideo"
                        {{ in_array('video', old('type', request()->type ?? [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexCheckDefaultVideo">Video</label>
                </div>
            </div>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-primary mt-3 w-100">Saring</button>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-12">
                @isset($_GET['provinsi'])
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="mb-4">Karya Provinsi {{ $_GET['provinsi'] }}</h1>
                        <div>
                            @if (!isset($_GET['sort']) or $_GET['sort'] == 'desc')
                                <button onclick="sortAddParams('asc')" class="btn btn-primary">
                                    <i class="fa-solid fa-arrow-down-wide-short"></i> Urutkan Like Secara Menaik
                                </button>
                            @else
                                <button onclick="sortAddParams('desc')" class="btn btn-primary">
                                    <i class="fa-solid fa-arrow-down-short-wide"></i> Urutkan Like Secara Menurun
                                </button>
                            @endif
                            <button onclick="resetParams()" class="btn btn-primary">
                                Atur Ulang Penyaring
                            </button>
                        </div>
                    </div>
                @else
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h2 class="" style="margin: 0px !important">Karya Terbaru</h2>
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
                <div class="row ">
                    @if ($data->isEmpty())
                        <div class="col-12 text-center mt-5">
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
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#selectElement').select2({
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
        
        var selectedSchools = @json($schools);
        
         if (selectedSchools.length > 0) {
        selectedSchools.forEach(function(school) {
            var option = new Option(school.name, school.id, true, true);
            $('#selectElement').append(option).trigger('change');
        });
    }
        

        function sortAddParams(sort) {
            let url = new URL(window.location.href);
            let search_params = url.searchParams;
            search_params.set('sort', sort);
            url.search = search_params.toString();
            window.location.href = url.toString();
        }

        function resetParams() {
            let url = new URL(window.location.href);
            url.search = ''; // Menghapus semua parameter
            window.location.href = url.toString(); // Redirect ke URL tanpa parameter
        }
    </script>
@endsection
