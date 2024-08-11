@extends('layouts.landing-base')



@section('content')
    <style>
        .accordion-button {
            background: #0097FF !important;
            color: white !important;
        }

        .accordion-button:not(.collapsed) {
            background: #0097FF !important;
            color: white !important;
        }



        .play-button {
            position: relative;
            /* Untuk mengatur posisi relatif */
            display: flex;
            /* Menggunakan flexbox */
            justify-content: center;
            /* Menengahkan secara horizontal */
            align-items: center;
            /* Menengahkan secara vertikal */
            width: 100%;

            /* Mengatur lebar maksimal agar responsif */
        }

        .play-button a {
            display: block;
            width: 100%;
            /* Membuat anchor sebagai block level */
        }

        .play-button img {
            width: 100%;
            /* Mengatur lebar gambar responsif */
            height: auto;
            /* Mengatur tinggi otomatis untuk mempertahankan rasio aspek */
            /* Border radius untuk gambar */
        }

        /* Tambahan untuk tombol play */
        .play-button::after {
            content: "";
            /* Menggunakan pseudo-element */
            position: absolute;
            /* Posisi absolut untuk overlay */
            top: 50%;
            /* Pusat vertikal */
            left: 50%;
            /* Pusat horizontal */
            width: 50px;
            /* Lebar tombol play */
            height: 50px;
            /* Tinggi tombol play */
            background: rgba(255, 255, 255, 0.8);
            /* Warna latar belakang tombol play */
            border-radius: 50%;
            /* Membuat tombol play menjadi bulat */
            transform: translate(-50%, -50%);
            /* Menempatkan tombol di tengah */
            display: flex;
            /* Flexbox untuk menengahkan ikon */
            justify-content: center;
            /* Menengahkan secara horizontal */
            align-items: center;
            /* Menengahkan secara vertikal */
            opacity: 0.8;
            /* Opasitas untuk efek */
            transition: opacity 0.3s;
            /* Transisi saat hover */
        }

        .play-button:hover::after {
            opacity: 1;
            /* Menampilkan tombol saat hover */
        }

        /* Tambahkan ikon play */
        .play-button::before {
            content: "▶";
            /* Menggunakan karakter play */
            font-size: 24px;
            /* Ukuran font ikon play */
            color: #000;
            /* Warna ikon play */
            position: absolute;
            /* Posisi absolut untuk ikon */
        }
    </style>
    <br>
    <br>
    <br>
    <div style="width: 80%;margin:auto">
        <div class="row">
            <div class="col-12 col-lg-6 col-md-12 col-sm-12">
                @if ($data->type == 'image')
                    <img src="{{ asset('storage/artwork/' . $data->file_path) }}" style="border-radius:10px;object-fit:cover;"
                        class="card-img-top" alt="...">
                @elseif ($data->type == 'video')
                    <div class="play-button">
                        <a href="https://www.youtube.com/watch?v={{ $data->video_id }}" target="_blank">
                            <img src="https://img.youtube.com/vi/{{ $data->video_id }}/hqdefault.jpg"
                                style="border-radius:10px;object-fit:cover;" class="card-img-top" alt="YouTube Thumbnail">
                        </a>
                    </div>
                @endif
            </div>
            <div class="col-12 col-lg-6 col-md-12 col-sm-12">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item" style="border-radius: 10px">
                        <h2 class="accordion-header" style="border-radius: 10px 10px  0px 0px">
                            <button class="accordion-button" style="border-radius: 10px 10px  0px 0px;height: 70px"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                aria-controls="collapseOne">
                                <div style="display: flex;gap:20px">
                                    <b style="">{{ $data->students[0]->name }}</b>
                                    <li>
                                        {{ $data->school->name }}
                                    </li>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <h1>
                                    {{ $data->title }}
                                </h1>
                                <p>
                                    {{ $data->description }}
                                </p>
                                <div class="mt-4">
                                    <i class="fa-regular fa-heart fa-xl"></i>
                                    670
                                </div>
                                <div class="mt-4">
                                    @if ($data->students->count() > 1)
                                        <h6 style="color: #0097FF" class="d-flex gap-4">Team
                                            <div>
                                                <span style="color: rgba(32,31,41,.6)">
                                                    <li>
                                                        ({{ $data->students->count() }} Orang)
                                                    </li>
                                                </span>
                                            </div>
                                        </h6>
                                    @else
                                        <h6 style="color: #0097FF">Solo</h6>
                                    @endif
                                </div>
                                <div class="d-flex gap-1 flex-wrap mt-3">
                                    @foreach ($data->students as $item)
                                        <span style="background: #0097FF !important"
                                            class="p-2 badge text-bg-primary">{{ $item->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <div class="row ">
            <center class="mb-3">
                <h2>
                    Karya Terkait
                </h2>
            </center>
            @foreach ($terkait as $item)
                <div class="col-md-6 col-lg-4 mb-4">
                    <x-card :item="$item" />
                </div>
            @endforeach
        </div>
    </div>
    </div>
@endsection
