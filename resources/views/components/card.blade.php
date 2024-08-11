<style>
    .bookmark {
        position: absolute;
        top: 0;
        right: 5%;
        height: 2em;
        /* Ubah tinggi menjadi lebih kecil */
        width: 1.5em;
        /* Ubah lebar menjadi lebih kecil */
        background-color: #0097FF;
        background-image:
            linear-gradient(hsla(0, 0%, 100%, 0),
                hsla(0, 0%, 100%, 0.5) 5px,
                hsla(0, 0%, 100%, 0) 25px);
        -webkit-filter: drop-shadow(0 2px 6px hsla(0, 0%, 0%, 0.75));
        box-sizing: border-box;
        padding-top: 30px;
        /* Sesuaikan padding */
        font-size: 1.2em;
        /* Kecilkan ukuran font */
        text-shadow: 0 -1px 0 hsla(0, 0%, 0%, 0.5);
    }

    .bookmark:before,
    .bookmark:after {
        content: '';
        display: block;
        position: absolute;
    }

    .bookmark:before {
        bottom: -50%;
        height: 100%;
        width: 100%;
        background-image:
            linear-gradient(-45deg,
                transparent 50%,
                #0097FF 50%),
            linear-gradient(45deg,
                transparent 50%,
                #0097FF 50%);
        z-index: -1;
    }

    .bookmark:after {
        height: 136.5%;
        width: 88%;
        left: 6%;
        top: 0;
        pointer-events: none;
    }


    <style>.accordion-button {
        background: #0097FF !important;
        color: white !important;
    }

    .accordion-button:not(.collapsed) {
        background: #0097FF !important;
        color: white !important;
    }



    .play-button {
        position: relative;
        /* Untuk mengatur posisi relatif */
        display: flex;
        /* Menggunakan flexbox */
        justify-content: center;
        /* Menengahkan secara horizontal */
        align-items: center;
        /* Menengahkan secara vertikal */
        width: 100%;

        /* Mengatur lebar maksimal agar responsif */
    }

    .play-button a {
        display: block;
        width: 100%;
        /* Membuat anchor sebagai block level */
    }

    .play-button img {
        width: 100%;
        /* Mengatur lebar gambar responsif */
        height: auto;
        /* Mengatur tinggi otomatis untuk mempertahankan rasio aspek */
        /* Border radius untuk gambar */
    }

    /* Tambahan untuk tombol play */
    .play-button::after {
        content: "";
        /* Menggunakan pseudo-element */
        position: absolute;
        /* Posisi absolut untuk overlay */
        top: 50%;
        /* Pusat vertikal */
        left: 50%;
        /* Pusat horizontal */
        width: 50px;
        /* Lebar tombol play */
        height: 50px;
        /* Tinggi tombol play */
        background: rgba(255, 255, 255, 0.8);
        /* Warna latar belakang tombol play */
        border-radius: 50%;
        /* Membuat tombol play menjadi bulat */
        transform: translate(-50%, -50%);
        /* Menempatkan tombol di tengah */
        display: flex;
        /* Flexbox untuk menengahkan ikon */
        justify-content: center;
        /* Menengahkan secara horizontal */
        align-items: center;
        /* Menengahkan secara vertikal */
        opacity: 0.8;
        /* Opasitas untuk efek */
        transition: opacity 0.3s;
        /* Transisi saat hover */
    }

    .play-button:hover::after {
        opacity: 1;
        /* Menampilkan tombol saat hover */
    }

    /* Tambahkan ikon play */
    .play-button::before {
        content: "▶";
        /* Menggunakan karakter play */
        font-size: 24px;
        /* Ukuran font ikon play */
        color: #000;
        /* Warna ikon play */
        position: absolute;
        /* Posisi absolut untuk ikon */
    }

    .btn-primary {
        background-color: #0097FF !important;
        border-color: #0097FF !important;
    }

    .trun {
        overflow: hidden;
        text-overflow: ellipsis;
        -webkit-line-clamp: 2;
        display: -webkit-box;
        -webkit-box-orient: vertical;
    }

    .active {
        color: #0097FF !important;
    }
</style>

<a href="{{ route('karya-home.detail', ['id' => $item->id]) }}" style="text-decoration: none;">
    <div class="card h-100" style="border-radius:15px;">
        <div class="position-relative">
            @if ($item->type == 'image')
                <img src="{{ asset('storage/artwork/' . $item->file_path) }}"
                    style="border-top-left-radius:15px;border-top-right-radius:15px;height:{{ $height }};object-fit:cover;"
                    class="card-img-top" alt="...">
                <div class="design-div bookmark">
                    <center style="margin-top: -22px;color: white;">
                        <i class="fa-regular fa-image"></i>

                    </center>
                </div>
            @elseif ($item->type == 'video')
                <div class="play-button">
                    <img src="https://img.youtube.com/vi/{{ $item->video_id }}/hqdefault.jpg"
                        style="border-top-left-radius:15px;border-top-right-radius:15px;height:{{ $height }};object-fit:cover;"
                        class="card-img-top" alt="YouTube Thumbnail">
                </div>
                <div class="design-div bookmark">
                    <center style="margin-top: -22px;color: white;">

                        <i class="fa-solid fa-video"></i>
                    </center>
                </div>
            @endif

        </div>
        <div class="card-body">
            <div style="height: 80px">
                <h5 class="card-title">{{ $item->title }}</h5>
                <div style="display: flex;gap:10px;align-items:center;" class="text-muted">
                    <div style="width: 10px;height:10px;background:#0097FF;border-radius:50%"></div>
                    {{ DB::table('schools')->where('id', $item->school_id)->first()->name }}
                </div>
            </div>
            <p class="card-text trun mt-3" style="height: 50px">{{ $item->description }}</p>
            <div class="d-flex mt-3" style="justify-content: space-between">
                @if ($item->students->count() > 1)
                    <span style="background: #0097FF !important" class="p-2 badge text-bg-primary">Team</span>
                @else
                    <span class="p-2 badge text-bg-warning text-white">Solo</span>
                @endif
                <div>
                    <i class="fa-regular fa-heart fa-xl"></i>
                    670
                </div>
            </div>
        </div>
    </div>
</a>
