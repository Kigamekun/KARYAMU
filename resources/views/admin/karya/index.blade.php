@php
    $breadcrumbs = [
        ['title' => 'Home', 'link' => '/', 'active' => false],
        ['title' => 'Data Bencana', 'link' => '/antrian/periksa', 'active' => true],
    ];
@endphp


<div class="container-fluid">
    <x-jumbotroon :title="'Data Bencana'" :breadcrumbs="$breadcrumbs" />

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Data Bencana</h3>
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
                            <th>Thumb</th>
                            <th>Nama Bencana</th>
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
                <h5 class="modal-title" id="staticBackdropLabel">Buat Bencana</h5>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="fw-semibold">Nama</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Masukan Judul" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="mb-3">
                        <label for="image" class="fw-semibold">Image<span class="text-danger">*</span></label>
                        <input type="file" class="form-control dropify" id="image" name="image"
                            placeholder="Isi file" data-allowed-file-extensions="pdf" required>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
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
