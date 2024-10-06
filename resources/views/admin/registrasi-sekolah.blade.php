<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gencerling - Registrasi Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/f87eaab4e6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"
        integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <style>
        .main-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('/assets/img/background-auth.svg');
            background-size: cover;
            background-position: top;
            opacity: 0.7;
            z-index: -1;
        }

        /* For mobile devices */
        @media only screen and (max-width: 768px) {
            #card-content {
                width: 100% !important;
                border-radius: 0;
            }

            .card-body {
                padding: 200px 40px 50px !important;
            }
        }

        /* For larger screens (desktops) */
        @media only screen and (min-width: 769px) {
            #card-content {
                width: 40%;
                border-radius: 30px;
            }

            .card-body {
                padding: 50px 100px 50px !important;
            }
        }

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
    <div class="main-content" id="main-content"
        style="width: 100%; height: 100vh; display: flex; justify-content: center; align-items: center;">
        <div class="card shadow" id="card-content" style="border: none;">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-start" style="flex-direction: column">
                    <h1>Registrasi Sekolah</h1>
                    <span>Data sekolah anda tidak ada, buat request untuk menambahkan data sekolah.</span>
                    <small id="emailHelp" class="form-text text-muted">Field dengan tanda <span
                            class="text-danger">*</span> wajib diisi.</small>
                </div>
                <br>
                <form method="POST" action="{{ route('register.sekolah') }}">
                    @csrf

                    <div class="row">
                        <!-- NPSN -->
                        <div class="col-md-6 mb-3">
                            <label for="npsn" class="form-label">NPSN<span class="ms-1 text-danger">*</span></label>
                            <input type="text" class="form-control" id="npsn" name="npsn" value=""
                                required>
                        </div>

                        <!-- Nama Sekolah -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Sekolah<span
                                    class="ms-1 text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value=""
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Bentuk Sekolah -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Sekolah<span
                                    class="ms-1 text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value=""
                                required>
                        </div>
                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status Sekolah<span
                                    class="ms-1 text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="N">Negeri</option>
                                <option value="S">Swasta</option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <!-- Alamat Sekolah -->
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Alamat Sekolah<span
                                    class="ms-1 text-danger">*</span></label>
                            <textarea name="address" class="form-control" id="address" cols="30" rows="2" required></textarea>
                        </div>

                        <!-- Nomor Telepon Sekolah -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">No Telepon Sekolah<span
                                    class="ms-1 text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" value=""
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Email Sekolah -->


                        <!-- Province -->
                        <div class="col-md-6 mb-3">
                            <label for="dp_provinsi" class="">Provinsi<span
                                    class="ms-1 text-danger">*</span></label>
                            <select class="form-select form-control" id="dp_provinsi" name="dp_provinsi" required>

                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dp_kota" class="">Kota<span class="ms-1 text-danger">*</span></label>
                            <select class="form-select w-100 form-control" id="dp_kota" name="dp_kota"
                                required></select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Regency -->

                        <!-- District -->
                        <div class="col-md-6 mb-3">
                            <label for="dp_kecamatan" class="">Kecamatan<span
                                    class="ms-1 text-danger">*</span></label>
                            <select class="form-select form-control" id="dp_kecamatan" name="dp_kecamatan"
                                required></select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dp_kelurahan" class="">Kelurahan<span
                                    class="ms-1 text-danger">*</span></label>
                            <select class="form-select form-control" id="dp_kelurahan" name="dp_kelurahan"
                                required></select>
                        </div>
                    </div>


                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" style="border-radius:15px;background:#0097FF;border:none"
                            class="p-3 btn btn-primary">Registrasi Sekolah</button>
                    </div>

                </form>

                <center>
                    <p class="mt-4">Sudah memiliki Akun ? <a style="color:#0097ff;text-decoration:none;"
                            href="/login">Masuk</a></p>
                </center>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.7.0/chosen.jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                // Load Provinces
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
                    }
                });

                // Load Cities based on Province
                $('#dp_provinsi').change(function() {
                    var provCode = $(this).val();
                    $('#dp_kota').html('');
                    $.ajax({
                        url: '/master/kota/' + provCode,
                        type: 'GET',
                        dataType: 'json',
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

                // Load Districts based on City
                $('#dp_kota').change(function() {
                    var kotaCode = $(this).val();
                    $('#dp_kecamatan').html('');
                    $.ajax({
                        url: '/master/kecamatan/' + kotaCode,
                        type: 'GET',
                        dataType: 'json',
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

                // Load Subdistricts based on District
                $('#dp_kecamatan').change(function() {
                    var kecCode = $(this).val();
                    $('#dp_kelurahan').html('');
                    $.ajax({
                        url: '/master/kelurahan/' + kecCode,
                        type: 'GET',
                        dataType: 'json',
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


        </script>

@if (!is_null(Session::get('message')))
<script>
    Swal.fire({
        position: 'center',
        icon: @json(Session::get('status')),
        title: @json(Session::get('status')),
        html: @json(Session::get('message')),
        showConfirmButton: false,
        timer: 2000
    })
</script>
@endif

    </div>
</body>

</html>
