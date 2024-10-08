@extends('layouts.landing-base')


@section('content')
    <div style="width: 80%;margin:auto">
        <div class="row">
            <div class="col-md-6 p-5 d-flex align-items-center">
                <div>
                    <h1 style="font-size: 64px">Sebarkan inspirasi lewat Karyamu bersama <span
                            style="font-weight: bold;color:#19459D">Gencerling.</span></h1>
                    <p>Sebarkan inspirasi lewat Karyamu bersama Gencerling</p>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg" style="background: #19459D">
                        Mulai Sekarang
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('assets/img/abouts.png') }}" alt="" class="w-100">
            </div>

        </div>
    </div>
@endsection
