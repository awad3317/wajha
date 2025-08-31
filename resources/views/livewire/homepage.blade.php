<div class="">
    <style>
        .small-box {
            border-radius: 0.7rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
        }

        .small-box .icon {
            font-size: 65px;
            opacity: 0.3;
        }

        .card {
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
            margin-bottom: 1.5rem;
            border-radius: 0.6rem;
        }

        .card-header {
            font-weight: bold;
            background: #195a9b;
        }

        .table th {
            border-top: none;
            font-weight: 600;
        }

        .badge {
            font-size: 0.85em;
        }

        .chart-container {
            position: relative;
            height: 280px;
            margin-bottom: 20px;
        }
    </style>

    <section class="content">
        <div class="container-fluid pt-4">

            <!-- ✅ الكروت الرئيسية -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info text-white">
                        <div class="inner">
                            <h3>{{ $usersCount }}</h3>
                            <p>المستخدمين</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success text-white">
                        <div class="inner">
                            <h3>{{ $ownersCount }}</h3>
                            <p>المالكين</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-tie"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning text-white">
                        <div class="inner">
                            <h3>{{ $adminsCount }}</h3>
                            <p>المدراء</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-shield"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger text-white">
                        <div class="inner">
                            <h3>{{ $establishmentsCount }}</h3>
                            <p>المنشآت</p>
                        </div>
                        <div class="icon"><i class="fas fa-store"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-primary text-white">
                        <div class="inner">
                            <h3>{{ $bookingsCount }}</h3>
                            <p>الحجوزات</p>
                        </div>
                        <div class="icon"><i class="fas fa-calendar-check"></i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-secondary text-white">
                        <div class="inner">
                            <h3>{{ $activeCoupons }}</h3>
                            <p>الكوبونات الفعالة</p>
                        </div>
                        <div class="icon"><i class="fas fa-ticket-alt"></i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-dark text-white">
                        <div class="inner">
                            <h3>{{ $activeAds }}</h3>
                            <p>الإعلانات النشطة</p>
                        </div>
                        <div class="icon"><i class="fas fa-bullhorn"></i></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline card-primary">
                        <div class="card-header"><i class="fas fa-chart-line"></i> الحجوزات آخر 10 أيام</div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="bookingsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-outline card-success">
                        <div class="card-header"><i class="fas fa-building"></i> أنواع المنشآت</div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="typesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline card-primary">
                        <div class="card-header"><i class="fas fa-table"></i> الحجوزات آخر 10 أيام</div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>عدد الحجوزات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookingsChart as $row)
                                        <tr>
                                            <td><i class="fas fa-calendar-day text-primary"></i> {{ $row->date }}
                                            </td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-outline card-success">
                        <div class="card-header"><i class="fas fa-table"></i> أنواع المنشآت</div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>النوع</th>
                                        <th>عدد المنشآت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($typesChart as $row)
                                        <tr>
                                            <td><i class="fas fa-store text-success"></i> {{ $row->name }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-info">
                        <div class="card-header"><i class="fas fa-user-friends"></i> توزيع المستخدمين</div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="usersChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card card-outline card-primary">
                        <div class="card-header"><i class="fas fa-calendar-check"></i> أحدث الحجوزات</div>
                        <div class="card-body table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>المستخدم</th>
                                        <th>المنشأة</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestBookings as $b)
                                        @php
                                            $statusLabels = [
                                                'pending' => 'قيد الانتظار',
                                                'paid' => 'مدفوع',
                                                'confirmed' => 'مؤكد',
                                                'completed' => 'مكتمل',
                                                'cancelled' => 'ملغي',
                                            ];

                                            $statusColors = [
                                                'pending' => 'warning',
                                                'paid' => 'primary',
                                                'confirmed' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                        @endphp
                                        <tr>
                                            <td>{{ $b->id }}</td>
                                            <td>{{ $b->user->name ?? '-' }}</td>
                                            <td>{{ $b->establishment->name ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $statusColors[$b->status] ?? 'secondary' }}">
                                                    {{ $statusLabels[$b->status] ?? $b->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-outline card-success">
                        <div class="card-header"><i class="fas fa-store"></i> أحدث المنشآت</div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach ($latestEstablishments as $e)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $e->name }}
                                        <span class="badge bg-success">جديد</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-outline card-warning">
                        <div class="card-header"><i class="fas fa-bullhorn"></i> أحدث الإعلانات</div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach ($latestAds as $a)
                                    <li class="list-group-item"><i class="fas fa-ad text-warning"></i>
                                        {{ $a->title }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ آخر التقييمات -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-danger">
                        <div class="card-header"><i class="fas fa-star"></i> آخر التقييمات</div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>المستخدم</th>
                                        <th>المنشأة</th>
                                        <th>التقييم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestReviews as $r)
                                        <tr>
                                            <td>{{ $r->user->name ?? '-' }}</td>
                                            <td>{{ $r->establishment->name ?? '-' }}</td>
                                            <td><span class="badge bg-success">{{ $r->rating }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

    </section>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart: Bookings
        new Chart(document.getElementById('bookingsChart'), {
            type: 'line',
            data: {
                labels: @json($bookingsChart->pluck('date')),
                datasets: [{
                    label: 'عدد الحجوزات',
                    data: @json($bookingsChart->pluck('total')),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0,123,255,0.3)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: "إحصائية الحجوزات",
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        rtl: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart: Establishments Types
        new Chart(document.getElementById('typesChart'), {
            type: 'bar',
            data: {
                labels: @json($typesChart->pluck('name')),
                datasets: [{
                    label: 'عدد المنشآت',
                    data: @json($typesChart->pluck('total')),
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1',
                        '#e83e8c', '#fd7e14'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: "أنواع المنشآت",
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        rtl: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart: Users
        new Chart(document.getElementById('usersChart'), {
            type: 'pie',
            data: {
                labels: @json($usersChart->pluck('name')),
                datasets: [{
                    data: @json($usersChart->pluck('total')),
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: "توزيع المستخدمين",
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        rtl: true
                    }
                }
            }
        });
    });
</script>
</div>
