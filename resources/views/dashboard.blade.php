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
            box-sizing: border-box;
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
        <div>
            <h3 class="mb-4">Pelatihan Terbaru</h3>
        </div>
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
            <div>
                <h3 class="mb-4">Karya Terbaru</h3>
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
                                <div class="design-div bookmark">
                                    <center style="margin-top: -22px;color: white;">
                                        <i class="fa-regular fa-image"></i>
                                    </center>
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
