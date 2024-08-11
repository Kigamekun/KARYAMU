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
            <div class="search-element d-flex justify-content-between">
                <div class=" d-flex">
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </span>
                        <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                            aria-describedby="addon-wrapping">
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createData"><i
                            class="fa-solid fa-filter me-2"></i>Filter</button>
                </div>
            </div>
            <br>
            <br>
            <br>
            <div class="row ">
                @foreach ($data as $item)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <x-card :item="$item" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
