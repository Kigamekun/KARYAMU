@extends('layouts.landing-base')

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
        <div class="mt-5">
            @isset($_GET['provinsi'])
                <h1 class="mb-4">Karya Provinsi {{ $_GET['provinsi'] }}</h1>
            @else
                <h1 class="mb-4">Karya Terbaru</h1>
            @endisset
            <br>
            <div class="row ">
                @if ($data->isEmpty())
                    <div class="col-12 text-center mt-4">
                        <div class="empty-state">
                            <img src="{{ asset('assets/img/empty.svg') }}" class="w-100" style="max-width: 300px" alt="">
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
                @endif
            </div>
        </div>
    </div>
@endsection
