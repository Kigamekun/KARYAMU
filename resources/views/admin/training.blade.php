@extends('layouts.base')

@section('css')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    @php
        $breadcrumbs = [
            ['title' => 'Home', 'link' => '/', 'active' => false],
            ['title' => 'Data Pelatihan', 'link' => '/antrian/periksa', 'active' => true],
        ];
    @endphp


    <div class="container-fluid">
        <x-jumbotroon :title="'Data Pelatihan'" :breadcrumbs="$breadcrumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Data Pelatihan</h3>
                    <div>
                        @if (Auth::user()->role == 'admin')
                            <button class="btn btn-primary" data-toggle="modal" data-target="#createData">
                                Tambah Data
                            </button>
                        @elseif (Auth::user()->role == 'teacher' &&
                                DB::table('teacher_trainings')->where('teacher_id', Auth::user()->teacher->id)->count() == 0)
                            <button class="btn btn-primary" data-toggle="modal" data-target="#createData" disabled>
                                Tambah Data
                            </button>
                        @else
                            <button class="btn btn-primary" data-toggle="modal" data-target="#createData">
                                Tambah Data
                            </button>
                        @endif
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table id="datatable-table" class="mt-3 mb-3 rounded-sm table table-bordered table-md">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Instruktor</th>
                                <th>Keterangan</th>
                                <th>Peserta</th>
                                <th>Jumlah Imbas</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>

                    <button class="btn btn-info">Total Peserta : <span id="total_peserta"></span></button>
                    <button class="btn btn-info">Total Imbas : <span id="total_imbas"></span></button>

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


    <div class="modal fade" id="detailData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="detailDataLabel" aria-hidden="true">
        <div class="modal-dialog" id="detailDialog">
            <div id="modal-dialog" class="modal-content">
                <div class="modal-body">
                    Loading..
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createData" data-backdrop="static" data-keyboard="false" aria-labelledby="createDataLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div id="modal-content" class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="staticBackdropLabel">Buat Pelatihan</h5>
                        <small id="emailHelp" class="form-text text-muted">Field dengan tanda <span
                                class="text-danger">*</span> wajib diisi.</small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pelatihan.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="description" class="fw-semibold">Deskripsi<span
                                    class="ml-1 text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" placeholder="Masukan Deskripsi" required></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        @if (Auth::user()->role == 'admin')
                            <div class="mb-3">
                                <label for="creator" class="fw-semibold">Pilih Pembuat Pelatihan<span
                                        class="ml-1 text-danger">*</span></label>
                                <select class="js-example-basic-single" id="creator" name="creator" required>

                                </select>
                                <x-input-error :messages="$errors->get('creator')" class="mt-2" />
                            </div>
                        @endif
                        <div class="mb-3" id="image-upload">
                            <label for="activity_photo" class="fw-semibold">Activity Photo<span
                                    class="ml-1 text-danger">*</span></label>
                            <input type="file" class=" dropify" id="activity_photo" name="activity_photo"
                                placeholder="Isi file" data-allowed-file-extensions='["png", "jpeg","jpg"]'
                                data-max-file-size="500K">
                            <span class="text-sm text-danger mt-2" style="font-size: 12px;">File harus berformat png,
                                jpeg, jpg dan berukuran
                                maksimal 500Kb.</span>
                            <x-input-error :messages="$errors->get('activity_photo')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="members" class="fw-semibold">Pilih Peserta<span
                                    class="ml-1 text-danger">*</span></label>
                            <select class="form-control select2" id="members" name="members[]" multiple="multiple">

                            </select>
                            <x-input-error :messages="$errors->get('members')" class="mt-2" />
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


    <script>
        $('.dropify').dropify();
    </script>

    <script>
        let members = @json($members);
        $(function() {
            var table = $('#datatable-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pelatihan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                    },
                    {
                        data: 'trainer_teacher_name',
                        name: 'trainer_teacher_name'
                    },

                    {
                        data: 'description',
                        name: 'description',
                    },
                    {
                        data: 'total_participants',
                        name: 'total_participants',
                    },
                    {
                        data: 'jumlah_terimbas',
                        name: 'jumlah_terimbas',
                    },
                    {
                        data: 'role',
                        name: 'role',
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                ],
                order: [
                    [0, 'desc']
                ],
            });
        });

        $('#updateData').on('shown.bs.modal', function(e) {
            $.ajax({
                url: '/pelatihan/' + $(e.relatedTarget).data('id') + '/edit',
                type: 'GET',
                success: function(response) {
                    let memberOption = '';

                    response.members.forEach(member => {
                        memberOption +=
                            `<option value="${member.id}" selected>${member.name}</option>`;
                    });

                    var html = `
                        <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Pelatihan</h5>
                        <small id="emailHelp" class="form-text text-muted">Field dengan tanda <span class="text-danger">*</span> wajib diisi.</small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                        <form action="${$(e.relatedTarget).data('url')}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="description" class="fw-semibold">Deskripsi<span class="ml-1 text-danger">*</span></label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Masukan Deskripsi" required>${$(e.relatedTarget).data('description')}</textarea>
                                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                    </div>
                                    <div class="mb-3" id="image-upload">
                                        <label for="activity_photo" class="fw-semibold">Activity Photo<span class="ml-1 text-danger">*</span></label>
                                        <input type="file" class=" dropify" id="activity_photo" name="activity_photo"
                                            placeholder="Isi file" data-allowed-file-extensions='["png", "jpeg","jpg"]' data-max-file-size="500K" data-default-file="${$(e.relatedTarget).data('activity_photo')}">
                                        <span class="text-sm text-danger mt-2" style="font-size: 12px;">File harus berformat png,
                                            jpeg, jpg dan berukuran
                                            maksimal 500Kb.</span>
                                        <x-input-error :messages="$errors->get('activity_photo')" class="mt-2" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="members" class="fw-semibold">Pilih Peserta<span class="ml-1 text-danger">*</span></label>
                                        <select class="form-control select4" id="members" name="members[]" multiple="multiple">
                                        ${memberOption}
                                        </select>
                                        <x-input-error :messages="$errors->get('members')" class="mt-2" />
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                        `;
                    $('#modal-content').html(html);
                    $('.dropify').dropify();
                    $('.main-content .select4').select2({
                        ajax: {
                            url: '/get-teachers',
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
                        minimumInputLength: 3,
                        placeholder: 'Pilih siswa',
                        allowClear: true
                    });
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        });
    </script>

    <script>
        function selectRole() {
            var role = document.getElementById('role').value;
            if (role == 'student') {
                document.getElementById('teacher').classList.add('d-none');
                document.getElementById('student').classList.remove('d-none');
            } else if (role == 'teacher') {
                document.getElementById('teacher').classList.remove('d-none');
                document.getElementById('student').classList.add('d-none');
            } else {
                document.getElementById('teacher').classList.add('d-none');
                document.getElementById('student').classList.add('d-none');
            }
        }
    </script>

    <script>
        $('#detailData').on('shown.bs.modal', function(e) {
            $.ajax({
                url: '/pelatihan/' + $(e.relatedTarget).data('id'),
                type: 'GET',
                success: function(response) {
                    var detl = '';
                    response.members.forEach(member => {
                        detl += `
                            <tr>
                                <td>${member.name}</td>
                                <td>${member.school}</td>
                            </tr>
                        `;
                    });
                    var html = `
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="description" class="fw-bold">Bukti Pelatihan</label>
                            <img class="w-100 rounded" src="/storage/activity_photo/${response.data.activity_photo}"
                                alt="${response.data.activity_photo}" </div>
                            <div class="mb-3 mt-2">
                                <label for="description" class="fw-bold">Deskripsi</label>
                                <p>${response.data.description}</p>
                            </div>
                                <label for="description" class="fw-bold">Peserta Pelatihan</label>
                            <table class="table table-bordered">
                                <tbody>
                                    ${detl}
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                        `;
                    $('#modal-dialog').html(html);
                }
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $('#members').select2({
            ajax: {
                url: '/get-teachers', // URL endpoint Anda untuk mengambil data sekolah
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
            placeholder: 'Pilih peserta pelatihan',
            allowClear: true
        });
    </script>

    <script>
        $('#datatable-table').on('search.dt', function() {

            $.ajax({
                url: "/pelatihan/getTotals/" + $('#dt-search-0').val(),
                method: "GET",

                success: function(response) {
                    $('#total_peserta').text('Total Peserta: ' + response.total_participants);
                    $('#total_imbas').text('Total Jumlah Terimbas: ' + response.total_terimbas);
                }
            });
        });
    </script>

    <script>
        $('#creator').select2({
            ajax: {
                url: '/get-teachers', // URL endpoint Anda untuk mengambil data sekolah
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
            placeholder: 'Pilih pembuat pelatihan',
            allowClear: true
        });
    </script>
@endsection
