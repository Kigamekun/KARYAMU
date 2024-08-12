@extends('layouts.base')

@section('css')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    @php
        $breadcrumbs = [
            ['title' => 'Home', 'link' => '/', 'active' => false],
            ['title' => 'Data User', 'link' => '/antrian/periksa', 'active' => true],
        ];
    @endphp


    <div class="container-fluid">
        <x-jumbotroon :title="'Data User'" :breadcrumbs="$breadcrumbs" />

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Data User</h3>
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
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Buat User</h5>
                </div>
                <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="fw-semibold">Nama</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Masukan Nama" required>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="username" class="fw-semibold">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Masukan Username" required>
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="fw-semibold">Email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                placeholder="Masukan Email" required>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="fw-semibold">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Masukan Password" required>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="role" class="fw-semibold">Role</label>
                            <select name="role" id="role" class="form-control" onchange="selectRole()" required>
                                <option value="">Pilih Role</option>
                                @if (auth()->user()->role == 'teacher')
                                    <option value="student">Siswa</option>
                                @else
                                    <option value="admin">Admin</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="student">Siswa</option>
                                @endif
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                        <div id="teacher" class="d-none">
                            <div class="mb-3">
                                <label for="nip" class="fw-semibold">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip"
                                    placeholder="Masukan NIP" >
                                <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="fw-semibold">No Telp</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="Masukan No Telp" >
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="address" class="fw-semibold">Alamat</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Masukan Alamat" >
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="school_id" class="fw-semibold">Sekolah</label>
                                <select name="school_id" id="school_id" class="form-control" >
                                    <option value="">Pilih Sekolah</option>
                                    @foreach ($sekolah as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                            </div>
                        </div>
                        <div id="student" class="d-none">
                            <div class="mb-3">
                                <label for="nis" class="fw-semibold">NIS</label>
                                <input type="text" class="form-control" id="nis" name="nis"
                                    placeholder="Masukan NIS" >
                                <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="fw-semibold">No Telp</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="Masukan No Telp" >
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="address" class="fw-semibold">Alamat</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Masukan Alamat" >
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="school_id" class="fw-semibold">Sekolah</label>
                                <select name="school_id" id="school_id" class="form-control" >
                                    <option value="">Pilih Sekolah</option>
                                    @foreach ($sekolah as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                            </div>
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
        let schools = @json($sekolah);

        $(function() {
            var table = $('#datatable-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.index') }}",
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
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role'
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
            let schoolOption = '';
            schools.forEach(school => {
                if (school.id == $(e.relatedTarget).data('school_id')) {
                    schoolOption +=
                        `<option value="${school.id}" selected>${school.name}</option>`;
                } else {
                    schoolOption +=
                        `<option value="${school.id}">${school.name}</option>`;
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
                            <label for="name" class="fw-semibold">Nama</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Masukan Nama" value="${$(e.relatedTarget).data('name')}" required>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="username" class="fw-semibold">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Masukan Username" value="${$(e.relatedTarget).data('username')}" required>
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="fw-semibold">Email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                placeholder="Masukan Email" value="${$(e.relatedTarget).data('email')}" required>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="fw-semibold">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Masukan Password" >
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="mb-3">
                            <label for="role" class="fw-semibold">Role</label>
                            <select name="role" id="role" class="form-control" onchange="selectRole()" required>
                                <option value="">Pilih Role</option>
                                 @if (auth()->user()->role == 'teacher')
                                    <option value="student" ${$(e.relatedTarget).data('role') == 'student' ? 'selected' : ''}>Siswa</option>
                                @else
                                <option value="admin" ${$(e.relatedTarget).data('role') == 'admin' ? 'selected' : ''}>Admin</option>
                                <option value="student" ${$(e.relatedTarget).data('role') == 'student' ? 'selected' : ''}>Siswa</option>
                                <option value="teacher" ${$(e.relatedTarget).data('role') == 'teacher' ? 'selected' : ''}>Guru</option>
                               @endif
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                        <div id="teacher-edit" class="d-none">
                            <div class="mb-3">
                                <label for="nip" class="fw-semibold">NIP</label>
                                <input type="text" class="form-control" id="nip" name="nip"
                                    placeholder="Masukan NIP" value="${$(e.relatedTarget).data('nip')}" >
                                <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="fw-semibold">No Telp</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="Masukan No Telp" value="${$(e.relatedTarget).data('phone_number')}" >
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="address" class="fw-semibold">Alamat</label>
                                <input type="text" class="form-control" id="address" value="${$(e.relatedTarget).data('address')}" name="address"
                                    placeholder="Masukan Alamat" >
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="school_id" class="fw-semibold">Sekolah</label>
                                <select name="school_id" id="school_id" class="form-control" >
                                   ${schoolOption}
                                </select>
                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                            </div>
                        </div>
                        <div id="student-edit" class="d-none">
                            <div class="mb-3">
                                <label for="nis" class="fw-semibold">NIS</label>
                                <input type="text" class="form-control" id="nis" name="nis"
                                    placeholder="Masukan NIS" value="${$(e.relatedTarget).data('nis')}" >
                                <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="fw-semibold">No Telp</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="Masukan No Telp" value="${$(e.relatedTarget).data('phone_number')}" >
                                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="address" class="fw-semibold">Alamat</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Masukan Alamat" value="${$(e.relatedTarget).data('address')}" >
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                            <div class="mb-3">
                                <label for="school_id" class="fw-semibold">Sekolah</label>
                                <select name="school_id" id="school_id" class="form-control" >
                                     ${schoolOption}
                                </select>
                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                            </div>
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
            selectRoleEdit();
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

        function selectRoleEdit() {
            var role = document.getElementById('role').value;
            if (role == 'student') {
                document.getElementById('teacher-edit').classList.add('d-none');
                document.getElementById('student-edit').classList.remove('d-none');
            } else if (role == 'teacher') {
                document.getElementById('teacher-edit').classList.remove('d-none');
                document.getElementById('student-edit').classList.add('d-none');
            } else {
                document.getElementById('teacher-edit').classList.add('d-none');
                document.getElementById('student-edit').classList.add('d-none');
            }
        }
    </script>
@endsection
