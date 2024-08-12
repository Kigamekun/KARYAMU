{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}


@extends('layouts.base')

@section('content')
    <style>
        .profile-user-img {
            width: 200px;
            border: 5px solid #0097FF;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            border-radius: 50%;
            object-fit: cover;
            height: 200px;

        }

        .avatar-upload {
            position: relative;
            max-width: 200px;
            margin: auto;
            margin-bottom: 20px;
        }

        .avatar-upload .avatar-edit {
            position: absolute;
            right: 0px;
            z-index: 1;
            top: 150px;
        }

        .avatar-upload .avatar-edit input {
            display: none;
        }

        .avatar-upload .avatar-edit input+label {
            display: inline-block;
            width: 50px;
            height: 50px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid #d2d6de;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all 0.2s ease-in-out;
        }

        .avatar-upload .avatar-edit input+label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }

        .avatar-upload .avatar-edit input+label:after {
            content: "";
            font-family: "FontAwesome";
            color: #0097FF;
            position: absolute;
            left: 0;
            right: 0;
            text-align: center;
            line-height: 44px;
            margin: auto;
        }
    </style>

    @php
        $breadcrumbs = [
            ['title' => 'Home', 'link' => '/', 'active' => false],
            ['title' => 'Profile', 'link' => '/antrian/periksa', 'active' => true],
        ];
    @endphp

    <div class="container-fluid">
        <x-jumbotroon :title="'Profile'" :breadcrumbs="$breadcrumbs" />
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <br>
                    <br>
                    <div>
                        <div class="d-flex justify-start " style="gap: 40px">
                            <div class="box box-primary">
                                <div class="box-body box-profile">
                                    <div>
                                        <div class="avatar-upload">
                                            <div class="avatar-edit">
                                                <form action="" method="post" id="form-image">
                                                    <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload"></label>
                                                </form>
                                            </div>
                                            <div class="avatar-preview">
                                                <img class="profile-user-img img-responsive img-circle" id="imagePreview"
                                                    src="https://adminlte.io/themes/AdminLTE/dist/img/user3-128x128.jpg"
                                                    alt="User profile picture">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-start align-items-center">
                                <div>
                                    <h1>Jonathan</h1>
                                    <h6 class="text-muted">Jonathan</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form class="needs-validation" novalidate="" method="POST" href="{{ route('profile.update') }}">

                        @csrf
                        @method('PATCH')
                        <div class="col-md-7 col-lg-12 mt-5">
                            <h3 class="mb-3">Data Diri</h3>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder=""
                                        value="{{ Auth::user()->name }}" required="">
                                    <div class="invalid-feedback">
                                        Valid first name is required.
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder=""
                                        value="{{ Auth::user()->username }}" required="">
                                    <div class="invalid-feedback">
                                        Valid last name is required.
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder=""
                                        value="{{ Auth::user()->email }}" required="">
                                    <div class="invalid-feedback">
                                        Valid first name is required.
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="" value="">
                                    <div class="invalid-feedback">
                                        Valid last name is required.
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!is_null(Auth::user()->student))
                            <div class="col-md-7 mt-5 col-lg-12">
                                <h3 class="mb-3">Data Sekolah</h3>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                                        <input type="text" class="form-control" disabled id="nama_sekolah"
                                            value="{{ Auth::user()->student->school->name }}" value="" required="">
                                        <div class="invalid-feedback">
                                            Valid first name is required.
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="alamat_sekolah" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" disabled id="alamat_sekolah"
                                            value="{{ Auth::user()->student->school->address }}" value=""
                                            required="">
                                        <div class="invalid-feedback">
                                            Valid last name is required.
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <label for="email_sekolah" class="form-label">Email</label>
                                        <input type="text" class="form-control" disabled id="email_sekolah"
                                            value="{{ Auth::user()->student->school->email }}" value=""
                                            required="">
                                        <div class="invalid-feedback">
                                            Valid first name is required.
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <label for="notelp_sekolah" class="form-label">No Telp</label>
                                        <input type="text" class="form-control" disabled id="notelp_sekolah"
                                            value="{{ Auth::user()->student->school->phone }}" value=""
                                            required="">
                                        <div class="invalid-feedback">
                                            Valid last name is required.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (!is_null(Auth::user()->teacher))
                            <div class="col-md-7 mt-5 col-lg-12">
                                <h3 class="mb-3">Data Sekolah</h3>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                                        <input type="text" class="form-control" disabled id="nama_sekolah"
                                            value="{{ Auth::user()->teacher->school->name }}" value=""
                                            required="">
                                        <div class="invalid-feedback">
                                            Valid first name is required.
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="alamat_sekolah" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" disabled id="alamat_sekolah"
                                            value="{{ Auth::user()->teacher->school->address }}" value=""
                                            required="">
                                        <div class="invalid-feedback">
                                            Valid last name is required.
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <label for="email_sekolah" class="form-label">Email</label>
                                        <input type="text" class="form-control" disabled id="email_sekolah"
                                            value="{{ Auth::user()->teacher->school->email }}" value=""
                                            required="">
                                        <div class="invalid-feedback">
                                            Valid first name is required.
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mt-3">
                                        <label for="notelp_sekolah" class="form-label">No Telp</label>
                                        <input type="text" class="form-control" disabled id="notelp_sekolah"
                                            value="{{ Auth::user()->teacher->school->phone }}" value="">
                                        <div class="invalid-feedback">
                                            Valid last name is required.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary w-100 mt-5">
                            Simpan
                        </button>
                        <br>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            $("#imageUpload").change(function(data) {
                var imageFile = data.target.files[0];
                var reader = new FileReader();
                reader.readAsDataURL(imageFile);
                reader.onload = function(evt) {
                    $('#imagePreview').attr('src', evt.target.result);
                    $('#imagePreview').hide();
                    $('#imagePreview').fadeIn(650);
                }
            });
        });
    </script>
@endsection
