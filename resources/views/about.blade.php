@extends('layouts.landing-base')

@section('content')
    <div style="width: 80%; margin: auto">
        <div class="row">
            <div class="col-md-6 p-5 d-flex align-items-center">
                <div>
                    <h1 style="font-size: 48px">Generasi Cerdas Lingkungan <span
                            style="font-weight: bold; color:#19459D">(Gencerling)</span></h1>
                    <p>Gencerling adalah generasi pemerhati lingkungan dan laskar ketahanan air masa depan. Dikawal oleh guru-guru terpilih dari seluruh Indonesia, para laskar air melakukan pengamatan, penelitian, dan pencatatan atas kondisi air di lingkungan terdekat. Hasilnya diunggah pada situs ini agar kita bisa melihat bagaimana kondisi air pada berbagai daerah tersebar di Indonesia dari sudut pandang anak-anak.</p>
                    <p>Mari belajar bersama pentingnya peran air dan mendukung usaha kita menjaga kelangsungan air untuk semua dan selamanya.</p>
                    <p>Sebarkan inspirasi lewat karyamu bersama Gencerling.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="background: #19459D">
                        Mulai Sekarang
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('assets/img/abouts.png') }}" alt="Tentang Gencerling" class="w-100">
            </div>
        </div>
    </div>
@endsection
