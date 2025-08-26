<div class="container py-4" dir="rtl">
    <style>
        .rounded-4 {
            border-radius: 1rem !important;
        }

        .shadow-soft {
            box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
        }

        /* Timeline العمودي */
        .timeline-container {
            display: flex;
            flex-direction: column;
            position: relative;
            padding-left: 40px;
        }

        .timeline-container::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #ddd;
        }

        .timeline-card {
            position: relative;
            margin-bottom: 30px;
            padding: 10px 20px;
            border-radius: 10px;
            background: #f8f9fa;
            border-left: 6px solid #007bff;
        }

        .timeline-card.status-complete { border-left-color: #28a745; }
        .timeline-card.status-pending { border-left-color: #ffc107; }
        .timeline-card.status-cancel { border-left-color: #dc3545; }

        .timeline-icon {
            position: absolute;
            left: -38px;
            top: 10px;
            width: 30px;
            height: 30px;
            background: #007bff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .badge-status {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 20px;
        }

        .card-header-custom {
            background-color: var(--primary-color, #007bff);
            color: #fff;
        }
    </style>

    {{-- معلومات الحجز --}}
    <div class="card shadow-soft rounded-4 mb-4">
        <div class="card-header card-header-custom">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>تفاصيل الحجز</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">

                {{-- بيانات صاحب الحجز --}}
                <div class="col-md-4">
                    <h6 class="text-primary">بيانات صاحب الحجز</h6>
                    <p class="mb-1"><strong>الاسم:</strong> {{ $booking->user->name }}</p>
                    @if(isset($booking->user->email))
                        <p class="mb-1"><strong>البريد الإلكتروني:</strong> {{ $booking->user->email }}</p>
                    @endif
                    <p class="mb-1"><strong>رقم الهاتف:</strong> {{ $booking->user->phone }}</p>
                </div>

                {{-- بيانات المالك --}}
                <div class="col-md-4">
                    <h6 class="text-primary">بيانات المالك للمنشأة</h6>
                    <p class="mb-1"><strong>الاسم:</strong> {{ $booking->establishment->owner->name }}</p>
                    @if(isset($booking->establishment->owner->email))
                        <p class="mb-1"><strong>البريد الإلكتروني:</strong> {{ $booking->establishment->owner->email }}</p>
                    @endif
                    <p class="mb-1"><strong>رقم الهاتف:</strong> {{ $booking->establishment->owner->phone }}</p>
                </div>

                {{-- بيانات المنشأة --}}
                <div class="col-md-4">
                    <h6 class="text-primary">بيانات المنشأة</h6>
                    <p class="mb-1"><strong>اسم المنشأة:</strong> {{ $booking->establishment->name }}</p>
                    @if(isset($booking->establishment->type))
                        <p class="mb-1"><strong>نوع المنشأة:</strong> {{ $booking->establishment->type->name }}</p>
                    @endif
                    @if(isset($booking->establishment->address))
                        <p class="mb-1"><strong>العنوان:</strong> {{ $booking->establishment->address }}</p>
                    @endif
                    @if(isset($booking->establishment->image))
                        <p class="mb-1"><strong>صورة المنشأة:</strong></p>
                        <img src="{{ asset('storage/' . $booking->establishment->image) }}" alt="صورة المنشأة" class="img-fluid rounded mb-1" style="max-width:120px;">
                    @endif
                </div>

                {{-- معلومات الحجز --}}
                <div class="col-md-3">
                    <h6 class="text-primary">معلومات الحجز</h6>
                    <p class="mb-1"><strong>رقم الحجز:</strong> #{{ $booking->id }}</p>
                    <p class="mb-1"><strong>الحالة الحالية:</strong> <span class="badge-status bg-success">{{ $booking->status }}</span></p>
                    <p class="mb-1"><strong>تاريخ الحجز:</strong> {{ $booking->created_at->format('Y-m-d H:i') }}</p>
                    @if(isset($booking->due_date))
                        <p class="mb-1"><strong>الموعد النهائي:</strong> {{ \Carbon\Carbon::parse($booking->due_date)->format('Y-m-d H:i') }}</p>
                    @endif
                    @if(isset($booking->duration))
                        <p class="mb-1"><strong>مدة الحجز:</strong> {{ $booking->duration }}</p>
                    @endif
                </div>

                {{-- الملاحظات --}}
                <div class="col-md-12 mt-2">
                    <h6 class="text-primary">الملاحظات</h6>
                    <p class="text-muted">{{ $booking->notes ?? 'لا توجد ملاحظات' }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- سجل تتبع الحجز --}}
    <div class="card shadow-soft rounded-4 mb-4">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">تتبع حالة الحجز</h5>
        </div>
        <div class="card-body">
            <div class="timeline-container">
                @foreach ($bookingLogs as $log)
                <div class="timeline-card 
                    @if($log->to_status == 'مكتمل') status-complete 
                    @elseif($log->to_status == 'ملغى') status-cancel 
                    @else status-pending @endif">
                    <div class="timeline-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="timeline-content">
                        <h6>{{ $log->user->name ?? 'النظام' }}
                            <span class="text-muted">({{ $log->created_at->format('Y-m-d H:i') }})</span>
                        </h6>
                        <p>
                            <span class="badge-status bg-primary">{{ $log->from_status }}</span>
                            <i class="bi bi-arrow-right"></i>
                            <span class="badge-status bg-success">{{ $log->to_status }}</span>
                        </p>
                        <p><strong>الإجراء:</strong> {{ $log->action }}</p>
                        @if($log->notes)
                        <p class="text-secondary"><strong>ملاحظة:</strong> {{ $log->notes }}</p>
                        @endif
                        <small class="text-muted">IP: {{ $log->ip_address }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- مخطط بياني للحالات --}}
    <div class="card shadow-soft rounded-4">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">تمثيل الحالة بيانيًا</h5>
        </div>
        <div class="card-body">
            <canvas id="bookingTimelineChart" height="100"></canvas>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('bookingTimelineChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($bookingLogs->pluck('created_at')->map(fn($d) => $d->format('Y-m-d H:i'))),
                datasets: [{
                    label: 'حالة الحجز',
                    data: @json($bookingLogs->pluck('to_status_value')), // لكل حالة رقم (1=معلق, 2=جاري, 3=مكتمل)
                    borderColor: '#007bff',
                    fill: false,
                    tension: 0.3,
                    pointBackgroundColor: @json($bookingLogs->map(function($log) {
                        return match($log->to_status) {
                            'مكتمل' => '#28a745',
                            'ملغى' => '#dc3545',
                            default => '#ffc107'
                        };
                    })),
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.data[context.dataIndex];
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        ticks: { 
                            callback: function(value) {
                                const statuses = ['معلق','قيد التنفيذ','مكتمل'];
                                return statuses[value-1] ?? '';
                            } 
                        },
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    </script>
</div>
