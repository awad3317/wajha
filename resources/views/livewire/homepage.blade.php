<div class="container-fluid" style="direction: rtl; text-align: right;">

    <!-- الإحصائيات الرئيسية -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $usersCount }}</h3>
                    <p>المستخدمين</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $ownersCount }}</h3>
                    <p>المالكين</p>
                </div>
                <div class="icon"><i class="fas fa-user-tie"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $adminsCount }}</h3>
                    <p>المدراء</p>
                </div>
                <div class="icon"><i class="fas fa-user-shield"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $establishmentsCount }}</h3>
                    <p>المنشآت</p>
                </div>
                <div class="icon"><i class="fas fa-store"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- الحجوزات -->
        <div class="col-lg-4 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $bookingsCount }}</h3>
                    <p>الحجوزات</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>
        <!-- الكوبونات -->
        <div class="col-lg-4 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $activeCoupons }}</h3>
                    <p>الكوبونات الفعالة</p>
                </div>
                <div class="icon"><i class="fas fa-ticket-alt"></i></div>
            </div>
        </div>
        <!-- الإعلانات -->
        <div class="col-lg-4 col-6">
            <div class="small-box bg-dark">
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
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>التاريخ</th>
                        <th>عدد الحجوزات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookingsChart as $row)
                        <tr>
                            <td>{{ $row->date }}</td>
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
        <div class="card-header"><i class="fas fa-building"></i> أنواع المنشآت</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>النوع</th>
                        <th>عدد المنشآت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($typesChart as $row)
                        <tr>
                            <td>{{ $row->type_id }}</td>
                            <td>{{ $row->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    </div>

    <!-- توزيع المستخدمين -->
    <div class="row">
       <div class="col-md-12">
    <div class="card card-outline card-info">
        <div class="card-header"><i class="fas fa-user-friends"></i> توزيع المستخدمين</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th>نوع المستخدم</th>
                        <th>العدد</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usersChart as $row)
                        <tr>
                            <td>{{ $row->user_type }}</td>
                            <td>{{ $row->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    </div>

    <!-- أحدث البيانات -->
    <div class="row">
        <!-- الحجوزات -->
        <div class="col-lg-4">
            <div class="card card-outline card-primary">
                <div class="card-header"><i class="fas fa-calendar-check"></i> أحدث الحجوزات</div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr><th>#</th><th>المستخدم</th><th>المنشأة</th><th>الحالة</th></tr>
                        </thead>
                        <tbody>
                            @foreach($latestBookings as $b)
                                <tr>
                                    <td>{{ $b->id }}</td>
                                    <td>{{ $b->user->name ?? '-' }}</td>
                                    <td>{{ $b->establishment->name ?? '-' }}</td>
                                    <td><span class="badge badge-info">{{ $b->status }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- المنشآت -->
        <div class="col-lg-4">
            <div class="card card-outline card-success">
                <div class="card-header"><i class="fas fa-store"></i> أحدث المنشآت</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($latestEstablishments as $e)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $e->name }}
                                <span class="badge badge-success">جديد</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- الإعلانات -->
        <div class="col-lg-4">
            <div class="card card-outline card-warning">
                <div class="card-header"><i class="fas fa-bullhorn"></i> أحدث الإعلانات</div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($latestAds as $a)
                            <li class="list-group-item">{{ $a->title }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- التقييمات -->
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-danger">
                <div class="card-header"><i class="fas fa-star"></i> آخر التقييمات</div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr><th>المستخدم</th><th>المنشأة</th><th>التقييم</th></tr>
                        </thead>
                        <tbody>
                            @foreach($latestReviews as $r)
                                <tr>
                                    <td>{{ $r->user->name ?? '-' }}</td>
                                    <td>{{ $r->establishment->name ?? '-' }}</td>
                                    <td><span class="badge badge-success">{{ $r->rating }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- سكربت الرسوم -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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
                    fill: true
                }]
            }
        });

        // Chart: Establishments Types
        new Chart(document.getElementById('typesChart'), {
            type: 'bar',
            data: {
                labels: @json($typesChart->pluck('type_id')),
                datasets: [{
                    label: 'عدد المنشآت',
                    data: @json($typesChart->pluck('total')),
                    backgroundColor: ['#28a745','#ffc107','#dc3545','#17a2b8']
                }]
            }
        });

        // Chart: Users
        new Chart(document.getElementById('usersChart'), {
            type: 'pie',
            data: {
                labels: @json($usersChart->pluck('user_type')),
                datasets: [{
                    data: @json($usersChart->pluck('total')),
                    backgroundColor: ['#007bff','#28a745','#ffc107']
                }]
            }
        });
    </script>
    @endpush

</div>
