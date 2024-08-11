@extends('layouts.base')

@section('content')
    <style>
        #chart-container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .chart {
            width: 100%;
            height: 100%;
            /* Menambahkan border hitam 5px */
            box-sizing: border-box;
            /* Menghitung border dalam ukuran elemen */
        }
    </style>

    <div style="width: 90%;margin:auto">
        <center>
            <h5></h5>
        </center>
        <div class="card">
            <div class="card-body">
                <center>
                    @include('components.map')
                </center>
            </div>
        </div>
        <br>
        <div class="row">
            @foreach ($pelatihan as $index => $item)
                <div class="col-md-4">
                    <div style="border-radius:15px;" class="card">
                        <div class="card-body">
                            <h5 class="card-title
                        ">{{ $item['title'] }}</h5>
                            <div class="row mt-5">

                                <div class="col-6">
                                    <h4>{{ $item['participant'] }}</h4>
                                    <p>Participants</p>

                                    <h4>{{ $item['total'] }}</h4>
                                    <p>Total</p>
                                </div>
                                <div class="col-6">
                                    <canvas class="chart" id="myChart-{{ $index }}"></canvas>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <br>
        <div class="search-element d-flex justify-content-between">
            <div class=" d-flex">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </div>
                    </div>
                    <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Username">
                </div>
            </div>
            <div>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createData">Tambah Data</button>
            </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="row ">
                @foreach ($data as $item)
                    <div class="col-md-6 col-lg-4 mb-4">

                        <div class="card h-100" style="border-radius:15px;">
                            <div class="position-relative">
                                @if ($item->type == 'image')
                                    <img src="{{ asset('storage/artwork/' . $item->file_path) }}"
                                        style="border-top-left-radius:15px;border-top-right-radius:15px;height:300px;object-fit:cover;"
                                        class="card-img-top" alt="...">
                                @elseif ($item->type == 'video')
                                    <div class="play-button">
                                        <img src="https://img.youtube.com/vi/{{ $item->video_id }}/hqdefault.jpg"
                                            style="border-top-left-radius:15px;border-top-right-radius:15px;height:300px;object-fit:cover;"
                                            class="card-img-top" alt="YouTube Thumbnail">
                                    </div>
                                @endif

                                <!-- Div untuk desain di ujung kanan gambar -->
                                <div class="design-div bookmark">
                                    <center style="margin-top: -22px;color: white;">
                                        <i class="fa-regular fa-image"></i>

                                    </center>
                                    <!-- Konten desain di sini -->
                                </div>
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
                                        <span style="background: #0097FF !important"
                                            class="p-2 badge text-bg-primary">Team</span>
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
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection


@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const pelatihan = @json($pelatihan);

        pelatihan.forEach((item, index) => {
            console.log(item, index);
            const ctx = document.getElementById(`myChart-${index}`).getContext('2d');

            const percentageSelesai = ((item.participant / item.total) * 100).toFixed(2);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Participant', 'Not Participant'],
                    datasets: [{
                        label: 'Teachers',
                        data: [item.participant, item.total - item.participant],
                        backgroundColor: [
                            '#0F61FF',
                            '#EF255F'
                        ],
                        borderColor: '#1D1D1D',
                        borderWidth: 5,
                        hoverOffset: 4
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        centerText: {
                            display: true,
                            text: `${percentageSelesai}%\nselesai`
                        }
                    }
                }
            });
        });
    </script>
@endsection
