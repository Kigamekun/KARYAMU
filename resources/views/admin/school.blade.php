@extends('layouts.base')



@section('css')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"
        integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    @php
        $breadcrumbs = [
            ['title' => 'Home', 'link' => '/', 'active' => false],
            ['title' => 'Data Sekolah', 'link' => '/antrian/periksa', 'active' => true],
        ];
    @endphp

    <style>
        select {
            width: inherit !important;
        }

        .chosen-container {
            width: 100% !important;
        }

        .chosen-select {
            width: 100%;
        }

        .chosen-select-deselect {
            width: 100%;
        }

        .chosen-container {
            display: inline-block;
            font-size: 14px;
            position: relative;
            vertical-align: middle;
        }

        .chosen-container .chosen-drop {
            background: #ffffff;
            border: 1px solid #cccccc;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
            -webkit-box-shadow: 0 8px 8px rgba(0, 0, 0, .25);
            box-shadow: 0 8px 8px rgba(0, 0, 0, .25);
            margin-top: -1px;
            position: absolute;
            top: 100%;
            left: -9000px;
            z-index: 1060;
        }

        .chosen-container.chosen-with-drop .chosen-drop {
            left: 0;
            right: 0;
        }

        .chosen-container .chosen-results {
            color: #555555;
            margin: 0 4px 4px 0;
            max-height: 240px;
            padding: 0 0 0 4px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .chosen-container .chosen-results li {
            display: none;
            line-height: 1.42857143;
            list-style: none;
            margin: 0;
            padding: 5px 6px;
        }

        .chosen-container .chosen-results li em {
            background: #feffde;
            font-style: normal;
        }

        .chosen-container .chosen-results li.group-result {
            display: list-item;
            cursor: default;
            color: #999;
            font-weight: bold;
        }

        .chosen-container .chosen-results li.group-option {
            padding-left: 15px;
        }

        .chosen-container .chosen-results li.active-result {
            cursor: pointer;
            display: list-item;
        }

        .chosen-container .chosen-results li.highlighted {
            background-color: #428bca;
            background-image: none;
            color: white;
        }

        .chosen-container .chosen-results li.highlighted em {
            background: transparent;
        }

        .chosen-container .chosen-results li.disabled-result {
            display: list-item;
            color: #777777;
        }

        .chosen-container .chosen-results .no-results {
            background: #eeeeee;
            display: list-item;
        }

        .chosen-container .chosen-results-scroll {
            background: white;
            margin: 0 4px;
            position: absolute;
            text-align: center;
            width: 321px;
            z-index: 1;
        }

        .chosen-container .chosen-results-scroll span {
            display: inline-block;
            height: 1.42857143;
            text-indent: -5000px;
            width: 9px;
        }

        .chosen-container .chosen-results-scroll-down {
            bottom: 0;
        }

        .chosen-container .chosen-results-scroll-down span {
            background: url("chosen-sprite.png") no-repeat -4px -3px;
        }

        .chosen-container .chosen-results-scroll-up span {
            background: url("chosen-sprite.png") no-repeat -22px -3px;
        }

        .chosen-single {
            background: white !important;
        }

        .chosen-container-single .chosen-single {
            background-color: #ffffff;
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            background-clip: padding-box;
            border: 1px solid #cccccc;
            border-top-right-radius: 4px;
            border-top-left-radius: 4px;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            color: #555555;
            display: block;
            height: 34px;
            overflow: hidden;
            line-height: 34px;
            padding: 0 0 0 8px;
            position: relative;
            text-decoration: none;
            white-space: nowrap;
        }

        .chosen-container-single .chosen-single span {
            display: block;
            margin-right: 26px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .chosen-container-single .chosen-single abbr {
            background: url("chosen-sprite.png") right top no-repeat;
            display: block;
            font-size: 1px;
            height: 10px;
            position: absolute;
            right: 26px;
            top: 12px;
            width: 12px;
        }

        .chosen-container-single .chosen-single abbr:hover {
            background-position: right -11px;
        }

        .chosen-container-single .chosen-single.chosen-disabled .chosen-single abbr:hover {
            background-position: right 2px;
        }

        .chosen-container-single .chosen-single div {
            display: block;
            height: 100%;
            position: absolute;
            top: 0;
            right: 0;
            width: 18px;
        }

        .chosen-container-single .chosen-single div b {
            background: url("chosen-sprite.png") no-repeat 0 7px;
            display: block;
            height: 100%;
            width: 100%;
        }

        .chosen-container-single .chosen-default {
            color: #777777;
        }

        .chosen-container-single .chosen-search {
            margin: 0;
            padding: 3px 4px;
            position: relative;
            white-space: nowrap;
            z-index: 1000;
        }

        .chosen-container-single .chosen-search input[type="text"] {
            background: url("chosen-sprite.png") no-repeat 100% -20px, #ffffff;
            border: 1px solid #cccccc;
            border-top-right-radius: 4px;
            border-top-left-radius: 4px;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            margin: 1px 0;
            padding: 4px 20px 4px 4px;
            width: 100%;
        }

        .chosen-container-single .chosen-drop {
            margin-top: -1px;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            background-clip: padding-box;
        }

        .chosen-container-single-nosearch .chosen-search input {
            position: absolute;
            left: -9000px;
        }

        .chosen-container-multi .chosen-choices {
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-top-right-radius: 4px;
            border-top-left-radius: 4px;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            cursor: text;
            height: auto !important;
            height: 1%;
            margin: 0;
            overflow: hidden;
            padding: 0;
            position: relative;
        }

        .chosen-choices {
            padding: 4px !important;
        }

        .chosen-container-multi .chosen-choices li {
            float: left;
            list-style: none;
        }

        .chosen-container-multi .chosen-choices .search-field {
            margin: 0;
            padding: 0;
            white-space: nowrap;
        }

        .chosen-container-multi .chosen-choices .search-field input[type="text"] {
            background: transparent !important;
            border: 0 !important;
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #555555;
            height: 32px;
            margin: 0;
            padding: 4px;
            outline: 0;
        }

        .chosen-container-multi .chosen-choices .search-field .default {
            color: #999;
        }

        .chosen-container-multi .chosen-choices .search-choice {
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            background-clip: padding-box;
            background-color: #eeeeee;
            border: 1px solid #cccccc;
            border-top-right-radius: 4px;
            border-top-left-radius: 4px;
            border-bottom-right-radius: 4px;
            border-bottom-left-radius: 4px;
            background-image: -webkit-linear-gradient(top, #ffffff 0%, #eeeeee 100%);
            background-image: -o-linear-gradient(top, #ffffff 0%, #eeeeee 100%);
            background-image: linear-gradient(to bottom, #ffffff 0%, #eeeeee 100%);
            background-repeat: repeat-x;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffeeeeee', GradientType=0);
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            color: #333333;
            cursor: default;
            line-height: 13px;
            margin: 6px 0 3px 5px;
            padding: 3px 20px 3px 5px;
            position: relative;
        }

        .chosen-container-multi .chosen-choices .search-choice .search-choice-close {
            background: url("chosen-sprite.png") right top no-repeat;
            display: block;
            font-size: 1px;
            height: 10px;
            position: absolute;
            right: 4px;
            top: 5px;
            width: 12px;
            cursor: pointer;
        }

        .chosen-container-multi .chosen-choices .search-choice .search-choice-close:hover {
            background-position: right -11px;
        }

        .chosen-container-multi .chosen-choices .search-choice-focus {
            background: #d4d4d4;
        }

        .chosen-container-multi .chosen-choices .search-choice-focus .search-choice-close {
            background-position: right -11px;
        }

        .chosen-container-multi .chosen-results {
            margin: 0 0 0 0;
            padding: 0;
        }

        .chosen-container-multi .chosen-drop .result-selected {
            display: none;
        }

        .chosen-container-active .chosen-single {
            border: 1px solid #66afe9;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .075) inset, 0 0 8px rgba(82, 168, 236, .6);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .075) inset, 0 0 8px rgba(82, 168, 236, .6);
            -webkit-transition: border linear .2s, box-shadow linear .2s;
            -o-transition: border linear .2s, box-shadow linear .2s;
            transition: border linear .2s, box-shadow linear .2s;
        }

        .chosen-container-active.chosen-with-drop .chosen-single {
            background-color: #ffffff;
            border: 1px solid #66afe9;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .075) inset, 0 0 8px rgba(82, 168, 236, .6);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .075) inset, 0 0 8px rgba(82, 168, 236, .6);
            -webkit-transition: border linear .2s, box-shadow linear .2s;
            -o-transition: border linear .2s, box-shadow linear .2s;
            transition: border linear .2s, box-shadow linear .2s;
        }

        .chosen-container-active.chosen-with-drop .chosen-single div {
            background: transparent;
            border-left: none;
        }

        .chosen-container-active.chosen-with-drop .chosen-single div b {
            background-position: -18px 7px;
        }

        .chosen-container-active .chosen-choices {
            border: 1px solid #66afe9;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .075) inset, 0 0 8px rgba(82, 168, 236, .6);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .075) inset, 0 0 8px rgba(82, 168, 236, .6);
            -webkit-transition: border linear .2s, box-shadow linear .2s;
            -o-transition: border linear .2s, box-shadow linear .2s;
            transition: border linear .2s, box-shadow linear .2s;
        }

        .chosen-container-active .chosen-choices .search-field input[type="text"] {
            color: #111 !important;
        }

        .chosen-container-active.chosen-with-drop .chosen-choices {
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .chosen-disabled {
            cursor: default;
            opacity: 0.5 !important;
        }

        .chosen-disabled .chosen-single {
            cursor: default;
        }

        .chosen-disabled .chosen-choices .search-choice .search-choice-close {
            cursor: default;
        }

        .chosen-rtl {
            text-align: right;
        }

        .chosen-rtl .chosen-single {
            padding: 0 8px 0 0;
            overflow: visible;
        }

        .chosen-rtl .chosen-single span {
            margin-left: 26px;
            margin-right: 0;
            direction: rtl;
        }

        .chosen-rtl .chosen-single div {
            left: 7px;
            right: auto;
        }

        .chosen-rtl .chosen-single abbr {
            left: 26px;
            right: auto;
        }

        .chosen-rtl .chosen-choices .search-field input[type="text"] {
            direction: rtl;
        }

        .chosen-rtl .chosen-choices li {
            float: right;
        }

        .chosen-rtl .chosen-choices .search-choice {
            margin: 6px 5px 3px 0;
            padding: 3px 5px 3px 19px;
        }

        .chosen-rtl .chosen-choices .search-choice .search-choice-close {
            background-position: right top;
            left: 4px;
            right: auto;
        }

        .chosen-rtl.chosen-container-single .chosen-results {
            margin: 0 0 4px 4px;
            padding: 0 4px 0 0;
        }

        .chosen-rtl .chosen-results .group-option {
            padding-left: 0;
            padding-right: 15px;
        }

        .chosen-rtl.chosen-container-active.chosen-with-drop .chosen-single div {
            border-right: none;
        }

        .chosen-rtl .chosen-search input[type="text"] {
            background: url("chosen-sprite.png") no-repeat -28px -20px, #ffffff;
            direction: rtl;
            padding: 4px 5px 4px 20px;
        }

        @media only screen and (-webkit-min-device-pixel-ratio: 2),
        only screen and (min-resolution: 144dpi) {

            .chosen-rtl .chosen-search input[type="text"],
            .chosen-container-single .chosen-single abbr,
            .chosen-container-single .chosen-single div b,
            .chosen-container-single .chosen-search input[type="text"],
            .chosen-container-multi .chosen-choices .search-choice .search-choice-close,
            .chosen-container .chosen-results-scroll-down span,
            .chosen-container .chosen-results-scroll-up span {
                background-image: url("chosen-sprite@2x.png") !important;
                background-size: 52px 37px !important;
                background-repeat: no-repeat !important;
            }
        }
    </style>



    <div class="container-fluid">
        <x-jumbotroon :title="'Data Sekolah'" :breadcrumbs="$breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Data Sekolah</h3>
                    <div>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#createData">
                            Tambah Data
                        </button>
                    </div>
                </div>
                <br>

                <div class="table-responsive">
                    <table id="datatable-table" class="mt-3 mb-3 rounded-sm table table-bordered table-md">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama </th>
                                <th>Alamat</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="updateData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="updateDataLabel" aria-hidden="true">
        <div class="modal-dialog" id="updateDialog">
            <div id="modal-content" class="modal-content">
                <div class="modal-body">
                    Loading..
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="createData" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="createDataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div id="modal-content" class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="staticBackdropLabel">Buat Sekolah</h5>
                        <small id="emailHelp" class="form-text text-muted">Field dengan tanda <span class="text-danger">*</span> wajib diisi.</small>
                    </div>
                </div>
                <form action="{{ route('sekolah.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="fw-semibold">Nama Sekolah<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Masukan Nama Sekolah" required>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>


                        <div class="mb-3">
                            <label for="address" class="fw-semibold">Alamat</label>
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="Masukan Alamat" required>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="fw-semibold">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                placeholder="Masukan Phone" required>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="fw-semibold">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Masukan Email" required>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="dp_provinsi" class="fw-semibold">Provinsi<span class="ml-1 text-danger">*</span></label>
                            <select class="form-select form-control" id="dp_provinsi" name="dp_provinsi" required>
                            </select>
                            <x-input-error :messages="$errors->get('dp_provinsi')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="dp_kota" class="fw-semibold">Kota<span class="ml-1 text-danger">*</span></label>
                            <select class="form-select w-100 form-control" id="dp_kota" name="dp_kota" required>
                            </select>
                            <x-input-error :messages="$errors->get('dp_kota')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="dp_kecamatan" class="fw-semibold">Kecamatan<span class="ml-1 text-danger">*</span></label>
                            <select class="form-select form-control" id="dp_kecamatan" name="dp_kecamatan" required>
                            </select>
                            <x-input-error :messages="$errors->get('dp_kecamatan')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="dp_kelurahan" class="fw-semibold">Kelurahan<span class="ml-1 text-danger">*</span></label>
                            <select class="form-select form-control" id="dp_kelurahan" name="dp_kelurahan" required>
                            </select>
                            <x-input-error :messages="$errors->get('dp_kelurahan')" class="mt-2" />
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.7.0/chosen.jquery.min.js"></script>


    <script>
        $(function() {
            var table = $('#datatable-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sekolah.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'desc']
                ],
            });
        });

        $(document).ready(function() {
            $.ajax({
                url: '/master/provinsi',
                type: 'GET',
                dataType: 'json',
                success: function(result) {
                    $('#dp_provinsi').append($('<option>', {
                        value: '',
                        text: 'Silahkan pilih provinsi'
                    }));
                    $.each(result, function(i, item) {
                        $('#dp_provinsi').append($('<option>', {
                            value: item.code,
                            text: item.name
                        }));
                    });
                    $(".form-select").chosen({
                        no_results_text: "Oops, nothing found!",
                        search_contains: true
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

            $('#dp_provinsi').change(function() {
                var code = $(this).val();
                $('#dp_kota').find('option').remove();
                $('#dp_kecamatan').find('option').remove();
                $('#dp_kelurahan').find('option').remove();
                $.ajax({
                    url: '/master/kota/' + code,
                    type: 'GET',
                    success: function(result) {
                        $('#dp_kota').append($('<option>', {
                            value: '',
                            text: 'Silahkan pilih kota'
                        }));

                        $.each(result, function(i, item) {
                            $('#dp_kota').append($('<option>', {
                                value: item.code,
                                text: item.name
                            }));
                        });
                        $(".form-select").trigger("chosen:updated");
                    }
                });
            });

            $('#dp_kota').change(function() {
                var code = $(this).val();
                $('#dp_kecamatan').find('option').remove();
                $('#dp_kelurahan').find('option').remove();
                $.ajax({
                    url: '/master/kecamatan/' + code,
                    type: 'GET',
                    success: function(result) {
                        $('#dp_kecamatan').append($('<option>', {
                            value: '',
                            text: 'Silahkan pilih kecamatan'
                        }));

                        $.each(result, function(i, item) {
                            $('#dp_kecamatan').append($('<option>', {
                                value: item.code,
                                text: item.name
                            }));
                        });
                        $(".form-select").trigger("chosen:updated");
                    }
                });
            });

            $('#dp_kecamatan').change(function() {
                var code = $(this).val();
                $('#dp_kelurahan').find('option').remove();
                $.ajax({
                    url: '/master/kelurahan/' + code,
                    type: 'GET',
                    success: function(result) {
                        $('#dp_kelurahan').append($('<option>', {
                            value: '',
                            text: 'Silahkan pilih kelurahan'
                        }));

                        $.each(result, function(i, item) {
                            $('#dp_kelurahan').append($('<option>', {
                                value: item.code,
                                text: item.name
                            }));
                        });
                        $(".form-select").trigger("chosen:updated");
                    }
                });
            });
        });

        $('#updateData').on('shown.bs.modal', function(e) {
            var url = $(e.relatedTarget).data('url');
            var name = $(e.relatedTarget).data('name');
            var address = $(e.relatedTarget).data('address');
            var phone = $(e.relatedTarget).data('phone');
            var email = $(e.relatedTarget).data('email');
            var provinsi = $(e.relatedTarget).data('provinsi');

            $.ajax({
                url: '/sekolah/' + $(e.relatedTarget).data('id'),
                type: 'GET',
                success: function(response) {

                    var html = `
                        <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Sekolah</h5>
                        <small id="emailHelp" class="form-text text-muted">Field dengan tanda <span class="text-danger">*</span> wajib diisi.</small>
                    </div>
                </div>
                        <form action="${url}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name" class="fw-semibold">Nama Sekolah<span class="ml-1 text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Masukan Nama Sekolah" value="${response.name}" required>
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="fw-semibold">Alamat</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="Masukan Alamat" value="${response.address}" required>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="fw-semibold">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Masukan Phone" value="${response.phone}" required>
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="fw-semibold">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Masukan Email" value="${response.email}" required>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label for="dp_provinsi" class="fw-semibold">Provinsi<span class="ml-1 text-danger">*</span></label>
                                    <select class="form-select form-control" id="dp_provinsi_edit" name="dp_provinsi" required>
                                        <!-- Options will be appended here -->
                                    </select>
                                    <x-input-error :messages="$errors->get('dp_provinsi')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label for="dp_kota" class="fw-semibold">Kota<span class="ml-1 text-danger">*</span></label>
                                    <select class="form-select form-control" id="dp_kota_edit" name="dp_kota" required>
                                        <!-- Options will be appended here -->
                                    </select>
                                    <x-input-error :messages="$errors->get('dp_kota')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label for="dp_kecamatan" class="fw-semibold">Kecamatan<span class="ml-1 text-danger">*</span></label>
                                    <select class="form-select form-control" id="dp_kecamatan_edit" name="dp_kecamatan" required>
                                        <!-- Options will be appended here -->
                                    </select>
                                    <x-input-error :messages="$errors->get('dp_kecamatan')" class="mt-2" />
                                </div>
                                <div class="mb-3">
                                    <label for="dp_kelurahan" class="fw-semibold">Kelurahan<span class="ml-1 text-danger">*</span></label>
                                    <select class="form-select form-control" id="dp_kelurahan_edit" name="dp_kelurahan" required>
                                        <!-- Options will be appended here -->
                                    </select>
                                    <x-input-error :messages="$errors->get('dp_kelurahan')" class="mt-2" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                        `;

                    $('#modal-content').html(html);

                    $.ajax({
                        url: '/master/provinsi',
                        type: 'GET',
                        dataType: 'json',
                        success: function(result) {
                            var provinsiOptions =
                                `<option value="">Silahkan pilih provinsi</option>`;
                            $.each(result, function(i, item) {
                                provinsiOptions +=
                                    `<option value="${item.code}" ${response.provinsi.code == item.code ? 'selected' : ''}>${item.name}</option>`;
                            });
                            $('#dp_provinsi_edit').html(provinsiOptions);
                            $('#dp_provinsi_edit').trigger(
                                "chosen:updated"); // Trigger change to load kota
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });

                    // Load Kota based on selected Provinsi
                    $.ajax({
                        url: '/master/kota/' + response.provinsi.code,
                        type: 'GET',
                        success: function(result) {
                            console.log('ini res');
                            console.log(result);
                            var kotaOptions =
                                `<option value="">Silahkan pilih kota</option>`;
                            $.each(result, function(i, item) {
                                kotaOptions +=
                                    `<option value="${item.code}" ${response.kabupaten.code == item.code ? 'selected' : ''}>${item.name}</option>`;
                            });
                            $('#dp_kota_edit').html(kotaOptions);
                            $('#dp_kota_edit').trigger(
                                "chosen:updated"
                            ); // Trigger change to load kecamatan
                        }
                    });

                    // Load Kecamatan based on selected Kota
                    $.ajax({
                        url: '/master/kecamatan/' + response.kabupaten.code,
                        type: 'GET',
                        success: function(result) {
                            var kecamatanOptions =
                                `<option value="">Silahkan pilih kecamatan</option>`;
                            $.each(result, function(i, item) {
                                kecamatanOptions +=
                                    `<option value="${item.code}" ${response.kecamatan.code == item.code ? 'selected' : ''}>${item.name}</option>`;
                            });
                            $('#dp_kecamatan_edit').html(kecamatanOptions);
                            $('#dp_kecamatan_edit').trigger("chosen:updated");
                        }
                    });
                    // Load Kelurahan based on selected Kecamatan
                    $.ajax({
                        url: '/master/kelurahan/' + response.kecamatan.code,
                        type: 'GET',
                        success: function(result) {
                            console.log('ada res');
                            console.log(result);
                            console.log(response.kelurahan.code);
                            var kelurahanOptions =
                                `<option value="">Silahkan pilih kelurahan</option>`;
                            $.each(result, function(i, item) {
                                kelurahanOptions +=
                                    `<option value="${item.code}" ${response.kelurahan.code == item.code ? 'selected' : ''}>${item.name}</option>`;
                            });
                            $('#dp_kelurahan_edit').html(kelurahanOptions);
                            $('#dp_kelurahan_edit').trigger("chosen:updated");
                        }
                    });

                    $(".form-select").chosen({
                        no_results_text: "Oops, nothing found!",
                        search_contains: true
                    });

                }
            });
        });
    </script>
@endsection
