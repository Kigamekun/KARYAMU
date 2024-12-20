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
            ['title' => 'Data Karya', 'link' => '/antrian/periksa', 'active' => true],
        ];
    @endphp

    <div class="container-fluid">
        <x-jumbotroon :title="'Data Karya'" :breadcrumbs="$breadcrumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Data Karya</h3>
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
                                <th>Title</th>
                                <th>Type</th>
                                <th>Status</th>
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
                        <h5 class="modal-title" id="staticBackdropLabel">Buat Karya</h5>
                        <small id="emailHelp" class="form-text text-muted">Field dengan tanda <span
                                class="text-danger">*</span> wajib diisi.</small>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('karya.store') }}" id="buatKarya" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="fw-semibold">Judul<span class="ml-1 text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Masukan Judul" required>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="description" class="fw-semibold">Deskripsi<span
                                    class="ml-1 text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" placeholder="Masukan Deskripsi" required></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="fw-semibold">Category<span
                                    class="ml-1 text-danger">*</span></label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori Karya</option>
                                @foreach (DB::table('categories')->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        @if (Auth::user()->role == 'admin')
                            <div class="mb-3">
                                <label for="creator" class="fw-semibold">Pilih Pembuat Karya<span
                                        class="ml-1 text-danger">*</span></label>
                                <select class="js-example-basic-single select2" id="creator" name="creator" required>
                                </select>
                                <x-input-error :messages="$errors->get('creator')" class="mt-2" />
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="type" class="fw-semibold">Tipe<span class="ml-1 text-danger">*</span></label>
                            <select class="form-control" onchange="selectType()" id="type" name="type" required>
                                <option value="image">Gambar</option>
                                <option value="video">Video</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        <div class="mb-3" id="image-upload">
                            <label for="image" class="fw-semibold">Image<span
                                    class="ml-1 text-danger">*</span></label>
                            <input type="file" class=" dropify" id="image" name="image" placeholder="Isi file"
                                data-allowed-file-extensions='["png", "jpeg","jpg"]' data-max-file-size="2M">
                            <span class="text-sm text-danger mt-2" style="font-size: 12px;">File harus berformat png,
                                jpeg, jpg dan berukuran
                                maksimal 1MB.</span>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                        <div class="mb-3 d-none" id="video-upload">
                            <label for="video" class="fw-semibold">Video<span
                                    class="ml-1 text-danger">*</span></label>
                            <input type="text" class="form-control" id="video" name="video"
                                placeholder="Masukan URL Video">
                            <span class="text-sm text-danger mt-2" style="font-size: 12px;">Gunakan link dari tombol share
                                di video youtube dengan link diawali dengan https://youtu.be.</span>
                            <x-input-error :messages="$errors->get('video')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="students" class="fw-semibold">Pilih Siswa<span
                                    class="ml-1 text-danger">*</span></label>
                            <select class="select2" id="students" name="students[]" multiple="multiple" required>
                            </select>
                            <x-input-error :messages="$errors->get('students')" class="mt-2" />
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
        let students = @json($students);

        $(function() {
            var table = $('#datatable-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('karya.index') }}",
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
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

        $('#updateData').on('shown.bs.modal', function(e) {
            $.ajax({
                url: '/karya/' + $(e.relatedTarget).data('id') + '/edit',
                type: 'GET',
                success: function(response) {
                    let memberOption = '';
                    response.students.forEach(student => {
                        memberOption +=
                            `<option value="${student.id}" selected>${student.name}</option>`;
                    });

                    let categoryOption = '';
                    response.categories.forEach(category => {
                        categoryOption +=
                            `<option value="${category.id}" ${category.id == response.category_id ? 'selected' : ''}>${category.name}</option>`;
                    });

                    var html = `
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="staticBackdropLabel">Edit Karya</h5>
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
                            <label for="title" class="fw-semibold">Judul <span class="ml-1 text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Masukan Judul" value="${$(e.relatedTarget).data('title')}" required>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="description" class="fw-semibold">Deskripsi <span class="ml-1 text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" placeholder="Masukan Deskripsi" required>${$(e.relatedTarget).data('description')}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="fw-semibold">Category<span
                                    class="ml-1 text-danger">*</span></label>
                            <select class="form-control"  id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori Karya</option>
                                ${categoryOption}
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="type" class="fw-semibold">Tipe<span class="ml-1 text-danger">*</span></label>
                            <select class="form-control" onchange="selectTypeEdit()" id="type-edit" name="type" required>
                                <option value="image" ${$(e.relatedTarget).data('type') == 'image' ? 'selected' : ''}>Gambar</option>
                                <option value="video" ${$(e.relatedTarget).data('type') == 'video' ? 'selected' : ''}>Video</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        <div class="mb-3 ${$(e.relatedTarget).data('type') == 'video' ? 'd-none' : ''}" id="image-upload-edit">
                            <label for="image" class="fw-semibold">Image<span class="ml-1 text-danger">*</span></label>
                            <input type="file" class=" dropify" id="image" name="image" placeholder="Isi file"
                                data-allowed-file-extensions='["png", "jpeg","jpg"]' data-max-file-size="2M" data-default-file="${$(e.relatedTarget).data('file_path')}">
                                    <span class="text-sm text-danger mt-2" style="font-size: 12px;">File harus berformat png,
                                jpeg, jpg dan berukuran
                                maksimal 1MB.</span>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                        <div class="mb-3 ${$(e.relatedTarget).data('type') == 'image' ? 'd-none' : ''}" id="video-upload-edit">
                            <label for="video" class="fw-semibold">Video<span class="ml-1 text-danger">*</span></label>
                            <input type="text" class="form-control" id="video" name="video"
                                placeholder="Masukan URL Video" value="${$(e.relatedTarget).data('video_link')}">
                            <x-input-error :messages="$errors->get('video')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="students" class="fw-semibold">Pilih Siswa <span class="ml-1 text-danger">*</span></label>
                            <select class="form-control select4" id="students" name="students[]" multiple="multiple" required>
                                ${memberOption}
                            </select>
                            <x-input-error :messages="$errors->get('students')" class="mt-2" />
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
                            url: '/get-students',
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
                }
            });
        });
    </script>

    <script>
        $('#detailData').on('shown.bs.modal', function(e) {
            $.ajax({
                url: '/karya/' + $(e.relatedTarget).data('id'),
                type: 'GET',
                success: function(response) {
                    var html = `
            <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Detail karya</h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="title" class="fw-semibold">Judul</label>
                    <p>${response.title}</p>
                </div>
                <div class="mb-3">
                    <label for="description" class="fw-semibold">Deskripsi</label>
                    <p>${response.description}</p>
                </div>
                <div class="mb-3">
                    <label for="type" class="fw-semibold">Tipe</label>
                    <p>
                        <span class="badge badge-pill badge-primary p-2">${response.type}</span>
                        </p>
                </div>
                ${response.type == 'image' ? ` <div class="mb-3"><label for="video_link" class="fw-semibold">Video Link</label><p><img src="storage/artwork/${response.file_path}" alt="${response.title}" style="width: 100%;border-radius:15px"></p></div>` : ''}
                ${response.type == 'video' ? `
                                <div class="mb-3">
                                    <label for="video_link" class="fw-semibold">Video Link</label>
                                    <p> <a href="https://www.youtube.com/watch?v=${response.video_id}" target="_blank">
                                            <img src="https://img.youtube.com/vi/${response.video_id}/hqdefault.jpg"
                                                style="border-top-left-radius:15px;border-top-right-radius:15px;height:300px;object-fit:cover;"
                                                class="card-img-top" alt="YouTube Thumbnail">
                                        </a></p>
                                </div>
                                ` : ''}
                <div class="mb-3">
                    <label for="status" class="fw-semibold">Status</label>
                    <p>${response.is_approved == 1 ? 'Published' : 'Draft'}</p>
                </div>
                <div class="mb-3">
                    <label for="students" class="fw-semibold">Peserta</label>
                    <p>${response.students}</p>
                </div>
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

    <script>
        function selectType() {
            var type = $('#type').val();
            if (type == 'image') {
                $('#image-upload').removeClass('d-none');
                $('#video-upload').addClass('d-none');
            } else {
                $('#image-upload').addClass('d-none');
                $('#video-upload').removeClass('d-none');
            }
        }

        function selectTypeEdit() {
            var type = $('#type-edit').val();
            if (type == 'image') {
                $('#image-upload-edit').removeClass('d-none');
                $('#video-upload-edit').addClass('d-none');
            } else {
                $('#image-upload-edit').addClass('d-none');
                $('#video-upload-edit').removeClass('d-none');
            }
        }
    </script>

    <script>
        $('#buatKarya').submit(function(e) {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false, // Mencegah klik di luar untuk menutup
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @if (Auth::user()->role == 'student')
        <script>
            $(document).ready(function() {
                // Inisialisasi Select2
                var s2 = $('#students').select2({
                    ajax: {
                        url: '/get-students', // URL endpoint Anda untuk mengambil data siswa
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
                    placeholder: 'Pilih siswa',
                    allowClear: true
                });

                var id = {{ Auth::user()->student->id }};
                var text = '{{ Auth::user()->student->name }}';

                var option = new Option(text, id, true, true);

                s2.append(option).trigger('change');

                s2.val(vals).trigger("change");

            });
        </script>
    @else
        <script>
            $(document).ready(function() {
                // Inisialisasi Select2
                var s2 = $('#students').select2({
                    ajax: {
                        url: '/get-students', // URL endpoint Anda untuk mengambil data siswa
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
                    placeholder: 'Pilih siswa',
                    allowClear: true
                });

            });
        </script>
    @endif

    <script>
        $('#creator').select2({
            ajax: {
                url: '/get-students', // URL endpoint Anda untuk mengambil data sekolah
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
            placeholder: 'Pilih siswanya',
            allowClear: true
        });
    </script>

    <script>
        $('#createData').on('shown.bs.modal', function(e) {
            $('.main-content .select2').select2({
                ajax: {
                    url: '/get-students', // URL endpoint Anda untuk mengambil data sekolah
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
                dropdownParent: $("#createData .modal-content"),
                minimumInputLength: 3, // Pengguna harus mengetik minimal 3 karakter sebelum data dimuat
                placeholder: 'Pilih siswa',
                allowClear: true
            });
            $('.main-content .select1').select2({
                ajax: {
                    url: '/get-students', // URL endpoint Anda untuk mengambil data siswa
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
                placeholder: 'Pilih siswa',
                dropdownParent: $("#createData .modal-content"),
                allowClear: true
            });
        });
    </script>
@endsection
