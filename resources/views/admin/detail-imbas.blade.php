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

    <link rel="stylesheet" href="/assets/js/dist/photoswipe.css">
@endsection

@section('content')
    @php
        $breadcrumbs = [
            ['title' => 'Home', 'link' => '/', 'active' => false],
            ['title' => 'Detail Imbas', 'link' => '/antrian/periksa', 'active' => true],
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
        <x-jumbotroon :title="'Detail Imbas'" :breadcrumbs="$breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Detail Imbas</h3>

                </div>
                <br>
                <div>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex" style="gap: 10px">
                            <div class="pswp-gallery" id="my-gallery">
                                <a href="/storage/activity_photo/{{ $data['activity_photo'] }}" data-pswp-width="4000"
                                    data-pswp-height="2500" target="_blank">
                                    <img src="/storage/activity_photo/{{ $data['activity_photo'] }}"
                                        style="width:100px;object-fit:cover;height:100px;border-radius:10px" alt="">
                                </a>
                            </div>
                            <div>
                                <h6>Deskripsi Pelatihan  {{ $data['date'] }}</h6>
                                <p>
                                    {{ $data['description'] }}
                                </p>

                                @php
                                    $teach = DB::table('teachers')->select('name')->where('id', $data['trainer_teacher_id'])->first();
                                @endphp
                                <p>
                                <div class="badge badge-primary">{{ $teach->name }}</div>
                                </p>
                                <p>

                                </p>

                            </div>
                        </div>
                        <h1 style="font-size: 50px">
                            {{ count($impactedTeachers) }} <span style="font-size: 10px">Guru Terimbas</span>
                        </h1>
                    </div>
                </div>
                <br>
                <br>

                <div class="table-responsive">
                    <table class="table table-bordered" id="impactTable">
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Keterangan Pelatihan</th>
                                <th>Tingkatan</th>
                                <th>Detail Imbas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($impactedTeachers as $impact)
                                @php
                                    $indentation = ($impact['level'] - 1) * 20; // Mengatur padding kiri berdasarkan level
                                    $isLastLevel = $impact['level'] == $maxLevel; // Cek apakah ini level terakhir
                                    $parentId = $impact['level'] == 1 ? 0 : $impact['influenced_by']['teacher_id'];
                                @endphp
                                <tr style="border:2px solid #dee2e6; padding-left: {{ $indentation }}px;"
                                    class="parent-row" data-id="{{ $impact['teacher_id'] }}"
                                    data-parent="{{ $parentId }}" data-level="{{ $impact['level'] }}">
                                    <td>
                                        <div
                                            style="margin-left: {{ $indentation }}px; padding-left:10px; border-left: 2px solid #dee2e6;">
                                            {{ $impact['teacher_name'] }}
                                        </div>
                                    </td>
                                    <td>{{ $impact['training_description'] }}</td>
                                    <td>{{ $impact['level'] }}</td>
                                    <td>
                                        @if (!$isLastLevel)
                                            <button class="btn btn-sm btn-primary toggle-detail">
                                                <i class="fa-solid fa-chevron-down"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
        $(document).ready(function() {
            var table = $('#impactTable').DataTable({
                "paginate": false,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "autoWidth": false
            });

            // Hide all detail rows initially
            $('#impactTable tbody tr').each(function() {
                var $row = $(this);
                if ($row.data('level') > 1) {
                    $row.hide(); // Hide non-top level rows
                }
            });

            // Toggle detail rows
            $('#impactTable').on('click', '.toggle-detail', function() {
                var $row = $(this).closest('tr');
                var id = $row.data('id');

                // Find all child rows of the current row
                $('#impactTable tbody tr').each(function() {
                    var $childRow = $(this);
                    if ($childRow.data('parent') === id) {
                        $childRow.toggle(); // Show/hide child rows
                    }
                });

                // Change icon of the toggle button
                var icon = $(this).find('i');
                if (icon.hasClass('fa-chevron-down')) {
                    icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                } else {
                    icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                }
            });
        });
    </script>
    <script type="module">
        import PhotoSwipeLightbox from '/assets/js/dist/photoswipe-lightbox.esm.js';
        const lightbox = new PhotoSwipeLightbox({
            gallery: '#my-gallery',
            children: 'a',
            pswpModule: () => import('/assets/js/dist/photoswipe.esm.js')
        });
        lightbox.init();
    </script>
@endsection