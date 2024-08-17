@extends('layouts.base')


@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

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
        <div class="d-flex justify-end" style="justify-content: end;width:100%;gap:10px">
            <button class="btn btn-primary" id="subscribeButton">Subscribe Webpush Notification</button>

            <!-- Tombol Unsubscribe -->
            <button class="btn btn-primary" id="unsubscribeButton">Unsubscribe Webpush Notification</button>

        </div>
        <br>
        {{-- @if (Auth::user()->role != 'student')
            <div>
                <h3 class="mb-4">Pelatihan Terbaru</h3>
            </div>

            <div class="row">
                @foreach ($pelatihan as $index => $item)
                    <div class="col-md-4">
                        <div style="border-radius:15px;" class="card">
                            <div class="card-body">
                                <span class="card-title
                        ">{{ $item['description'] }}</span>
                                <div class="row mt-5">
                                    <div class="col-6">
                                        <h4>{{ $item['participant'] }}</h4>
                                        <p>Jumlah Guru Terimbas</p>
                                        <h4>{{ $item['total'] }}</h4>
                                        <p>Total Guru</p>
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
        @endif
        <br> --}}



        <div>
            <h3 class="mb-4">Laporan Imbas Pelatihan Guru</h3>
        </div>
        <div class="container-fluid">
            <div style="border-radius:15px;" class="card">
                <div class="card-body">
                    <div class="container">
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

















        <div class="search-element d-flex justify-content-between">
            <div>
                <h3 class="mb-4">Karya Terbaru</h3>
            </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="row ">
                @if (!$data->isEmpty())
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
                @else
                    <div class="col-12 text-center mt-4">
                        <div class="empty-state">
                            <img src="{{ asset('assets/img/empty.svg') }}" alt="">
                            <h3 class="mt-5">Tidak ada data yang tersedia</h3>
                            <p>Silakan tambahkan beberapa item untuk ditampilkan di sini.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap4.js"></script>
    <script>
        const pelatihan = @json($pelatihan);
        pelatihan.forEach((item, index) => {
            console.log(item, index);
            const ctx = document.getElementById(`myChart-${index}`).getContext('2d');
            const percentageSelesai = ((item.participant / item.total) * 100).toFixed(2);
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Jumlah Guru Terimbas', 'Sisa Guru'],
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

    <script>
        const publicVapidKey = "{{ env('VAPID_PUBLIC_KEY') }}";

        // Utility function untuk mengonversi VAPID key ke Uint8Array
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/-/g, '+')
                .replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        // Subscribe ke push notifications
        document.getElementById('subscribeButton').addEventListener('click', function() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        return registration.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
                        });
                    })
                    .then(function(subscription) {
                        return fetch('/subscribe', {
                            method: 'POST',
                            body: JSON.stringify(subscription),
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                    })
                    .then(function(response) {
                        if (response.ok) {
                            alert('Subscribed successfully!');
                        } else {
                            alert('Failed to subscribe.');
                        }
                    })
                    .catch(function(error) {
                        console.error('Subscription error:', error);
                    });
            } else {
                alert('Service workers are not supported in this browser.');
            }
        });

        // Unsubscribe dari push notifications
        document.getElementById('unsubscribeButton').addEventListener('click', function() {
            navigator.serviceWorker.ready.then(function(registration) {
                return registration.pushManager.getSubscription();
            }).then(function(subscription) {
                if (subscription) {
                    return subscription.unsubscribe().then(function() {
                        return fetch('/unsubscribe', {
                            method: 'POST',
                            body: JSON.stringify({
                                endpoint: subscription.endpoint
                            }),
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                    });
                } else {
                    alert('No subscription found.');
                }
            }).then(function(response) {
                if (response && response.ok) {
                    alert('Unsubscribed successfully!');
                } else {
                    alert('Failed to unsubscribe.');
                }
            }).catch(function(error) {
                console.error('Unsubscription error:', error);
            });
        });
    </script>
    {{-- <script>
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
            });
        });
    </script> --}}

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
@endsection
