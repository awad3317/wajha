<div class="container py-4 ">
    {{-- معلومات الحجز --}}
    <div class="card shadow-soft rounded-4 mb-4 ">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> تفاصيل الحجز</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">

                {{-- بيانات صاحب الحجز --}}
                <div class="col-12 col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-primary mb-3"><i class="fas fa-user me-1"></i> صاحب الحجز</h6>
                        <p class="mb-2"><strong>الاسم:</strong> {{ $booking->user->name }}</p>
                        @if (isset($booking->user->email))
                            <p class="mb-2"><strong>البريد الإلكتروني:</strong> {{ $booking->user->email }}</p>
                        @endif
                        <p class="mb-0"><strong>رقم الهاتف:</strong> {{ $booking->user->phone }}</p>
                    </div>
                </div>

                {{-- بيانات المالك --}}
                <div class="col-12 col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-primary mb-3"><i class="fas fa-user-tie me-1"></i> مالك المنشأة</h6>
                        <p class="mb-2"><strong>الاسم:</strong> {{ $booking->establishment->owner->name }}</p>
                        @if (isset($booking->establishment->owner->email))
                            <p class="mb-2"><strong>البريد الإلكتروني:</strong>
                                {{ $booking->establishment->owner->email }}</p>
                        @endif
                        <p class="mb-0"><strong>رقم الهاتف:</strong> {{ $booking->establishment->owner->phone }}</p>
                    </div>
                </div>

                {{-- بيانات المنشأة --}}
                <div class="col-12 col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-primary mb-3"><i class="fas fa-building me-1"></i> المنشأة</h6>
                        <p class="mb-2"><strong>اسم المنشأة:</strong> {{ $booking->establishment->name }}</p>
                        @if (isset($booking->establishment->type))
                            <p class="mb-2"><strong>النوع:</strong> {{ $booking->establishment->type->name }}</p>
                        @endif
                        @if (isset($booking->establishment->address))
                            <p class="mb-2"><strong>العنوان:</strong> {{ $booking->establishment->address }}</p>
                        @endif
                        @if (isset($booking->establishment->image))
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $booking->establishment->image) }}" alt="صورة المنشأة"
                                    class="img-thumbnail rounded mb-1" style="max-width:100px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-3">
                {{-- معلومات الحجز --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="border rounded-3 p-3 h-100 bg-light">
                        <h6 class="text-primary mb-3"><i class="fas fa-calendar-check me-1"></i> معلومات الحجز</h6>
                        <p class="mb-2"><strong>رقم الحجز:</strong> #{{ $booking->id }}</p>
                        <p class="mb-2"><strong>الحالة:</strong>
                            @php
                                $statusLabels = [
                                    'pending' => 'قيد الانتظار',
                                    'waiting_payment' => 'بانتظار الدفع',
                                    'paid' => 'مدفوع',
                                    'confirmed' => 'مؤكد',
                                    'cancelled' => 'ملغى',
                                    'completed' => 'مكتمل',
                                ];
                                $statusClasses = [
                                    'pending' => 'bg-secondary',
                                    'waiting_payment' => 'bg-warning text-dark',
                                    'paid' => 'bg-info',
                                    'confirmed' => 'bg-primary',
                                    'cancelled' => 'bg-danger',
                                    'completed' => 'bg-success',
                                ];
                            @endphp
                            <span
                                class="badge {{ $statusClasses[$booking->status] }}">{{ $statusLabels[$booking->status] }}</span>
                        </p>
                        <p class="mb-2">
                            <strong>تاريخ الحجز:</strong>
                            {{ $booking->created_at->format('Y-m-d') }}
                            <span class="badge bg-secondary">{{ $booking->created_at->format('h:i A') }}</span>
                        </p>
                        <p class="mb-2">
                            <strong>الموعد النهائي:</strong>
                            {{ \Carbon\Carbon::parse($booking->due_date)->format('Y-m-d') }}
                            <span
                                class="badge bg-secondary">{{ \Carbon\Carbon::parse($booking->due_date)->format('h:i A') }}</span>
                        </p>
                        @if (isset($booking->duration))
                            <p class="mb-0"><strong>مدة الحجز:</strong> {{ $booking->duration }}</p>
                        @endif
                    </div>
                </div>
                {{-- الملاحظات --}}
                <div class="col-12 col-md-6 col-lg-8">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-primary mb-3"><i class="fas fa-sticky-note me-1"></i> الملاحظات</h6>
                        <p class="text-muted mb-0">{{ $booking->notes ?? 'لا توجد ملاحظات' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- سجل تتبع الحجز --}}
    <div class="card shadow-soft rounded-4 mb-4 ">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">تتبع حالة الحجز</h5>
        </div>
        <div class="card-body">
            <div class="timeline-container">
                @php
                    $statusLabels = [
                        'pending' => 'قيد الانتظار',
                        'waiting_payment' => 'بانتظار الدفع',
                        'paid' => 'مدفوع',
                        'confirmed' => 'مؤكد',
                        'cancelled' => 'ملغى',
                        'completed' => 'مكتمل',
                    ];
                    $statusClasses = [
                        'pending' => 'bg-secondary',
                        'waiting_payment' => 'bg-warning text-dark',
                        'paid' => 'bg-info text-white',
                        'confirmed' => 'bg-primary',
                        'cancelled' => 'bg-danger',
                        'completed' => 'bg-success',
                    ];
                @endphp

                @foreach ($bookingLogs as $log)
                    @php
                        $fromStatusKey = $log->from_status_key ?? $log->from_status;
                        $toStatusKey = $log->to_status_key ?? $log->to_status;

                        $fromLabel = $statusLabels[$fromStatusKey] ?? $log->from_status;
                        $toLabel = $statusLabels[$toStatusKey] ?? $log->to_status;

                        $fromClass = $statusClasses[$fromStatusKey] ?? 'bg-secondary';
                        $toClass = $statusClasses[$toStatusKey] ?? 'bg-secondary';
                    @endphp

                    <div
                        class="timeline-card 
                @if ($toStatusKey == 'completed') status-complete 
                @elseif($toStatusKey == 'cancelled') status-cancel 
                @else status-pending @endif">

                        <div class="timeline-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>

                        <div class="timeline-content">

                        
                            {{-- وقت وتاريخ الإجراء --}}
                            <h6 >
                                <span class="badge bg-secondary">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('h:i A') }}
                                </span>
                                - {{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d') }}
                                ({{ $log->user->name ?? 'النظام' }})
                            </h6>

                            {{-- البادجات --}}
                            <p class="d-flex align-items-center gap-2">
                                 الحالة: <span class="text-primary mr-2">
                                     <span class="badge-status {{ $toClass }}" style="padding:0.5rem 1rem;">
                                        من
                                    {{ $toLabel }}
                            
                                </span>
                               
                                <span style="font-weight: bold; font-size: 1.2rem; color: #555;"> ←</span>
                                <span class="badge-status {{ $fromClass }}" style="padding:0.5rem 1rem;">
                                    الى
                                    {{ $fromLabel }}
                                </span>
                            </p>

{{-- 
                            <p><strong>الإجراء:</strong> {{ $log->action }}</p>
                            @if ($log->notes)
                                <p class="text-secondary"><strong>ملاحظة:</strong> {{ $log->notes }}</p>
                            @endif
                            <small class="text-muted">IP: {{ $log->ip_address }}</small> --}}
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

    @php
        $pointColors = $bookingLogs->map(function ($log) {
            return match ($log->to_status) {
                'مكتمل' => '#28a745',
                'ملغى' => '#dc3545',
                default => '#ffc107',
            };
        });
    @endphp

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
                    data: @json($bookingLogs->pluck('to_status_value')),
                    borderColor: '#007bff',
                    fill: false,
                    tension: 0.3,
                    pointBackgroundColor: @json($pointColors),
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
                                const statuses = ['معلق', 'قيد التنفيذ', 'مكتمل'];
                                return statuses[value - 1] ?? '';
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
