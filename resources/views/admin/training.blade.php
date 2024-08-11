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
                                <th>Title</th>
                                <th>School</th>
                                <th>Member</th>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Buat Pelatihan</h5>
                </div>
                <form action="{{ route('pelatihan.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="fw-semibold">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Masukan Judul" required>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="description" class="fw-semibold">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Masukan Deskripsi" required></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="mb-3" id="image-upload">
                            <label for="activity_photo" class="fw-semibold">Activity Photo<span
                                    class="text-danger">*</span></label>
                            <input type="file" class=" dropify" id="activity_photo" name="activity_photo"
                                placeholder="Isi file" data-allowed-file-extensions='["png", "jpeg","jpg"]'>
                            <x-input-error :messages="$errors->get('activity_photo')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="members" class="fw-semibold">Pilih Peserta</label>
                            <select class="form-control select2" id="members" name="members[]" multiple="multiple">
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
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

    <!-- Tambahkan di dalam footer, sebelum tag penutup body -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.main-content .select2').select2({
                tags: true,
                placeholder: "Pilih peserta yang berpartisipasi",
                allowClear: true
            });
        });
    </script>

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
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'trainer_school',
                        name: 'trainer_school',
                    },
                    {
                        data: 'total_participants',
                        name: 'total_participants',
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
                    members.forEach(member => {
                        if (response.members.includes(member.id)) {
                            memberOption +=
                                `<option value="${member.id}" selected>${member.name}</option>`;
                        } else {
                            memberOption +=
                                `<option value="${member.id}">${member.name}</option>`;
                        }
                    });
                    var html = `
                        <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Edit User</h5>
                        </div>
                        <form action="${$(e.relatedTarget).data('url')}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="title" class="fw-semibold">Title</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            placeholder="Masukan Judul" value="${$(e.relatedTarget).data('title')}" required>
                                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="fw-semibold">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Masukan Deskripsi" required>${$(e.relatedTarget).data('description')}</textarea>
                                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                    </div>
                                    <div class="mb-3" id="image-upload">
                                        <label for="activity_photo" class="fw-semibold">Activity Photo<span
                                                class="text-danger">*</span></label>
                                        <input type="file" class=" dropify" id="activity_photo" name="activity_photo"
                                            placeholder="Isi file" data-allowed-file-extensions='["png", "jpeg","jpg"]' data-default-file="${$(e.relatedTarget).data('activity_photo')}">
                                        <x-input-error :messages="$errors->get('activity_photo')" class="mt-2" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="members" class="fw-semibold">Pilih Peserta</label>
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
                        tags: true,
                        placeholder: "Pilih peserta yang berpartisipasi",
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
@endsection
