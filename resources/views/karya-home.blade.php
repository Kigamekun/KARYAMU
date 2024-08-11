@extends('layouts.landing-base')

@section('css')
    <script src="https://unpkg.com/slim-select@latest/dist/slimselect.min.js"></script>
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
                        <div class="input-group mb-3">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search" aria-label="Search"
                                    aria-describedby="button-addon2">
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
                                            <h6>
                                                Sekolah
                                            </h6>
                                            <select id="selectElement" multiple>
                                                <option>Option 1</option>
                                                <option>Option 2</option>
                                                <option>Option 3</option>
                                            </select>
                                            <br>
                                            <h6>
                                                Tipe
                                            </h6>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="flexCheckDefault">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    Gambar
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="flexCheckChecked" checked>
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    Video
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-12">
                <h3 class="lg-mt-5 mt-3">Semua Karya</h3>
                <div class="row ">
                    @foreach ($data as $item)
                        <div class="col-md-6 col-lg-4 mb-4">
                        <x-card :item="$item" :height="'200px'"/>

                        </div>
                    @endforeach
                    <div class="mt-5">
                        {{ $data->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        new SlimSelect({
            select: '#selectElement'
        })
    </script>
@endsection
