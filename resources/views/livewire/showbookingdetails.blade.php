<div class="container py-4">
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
                        @if ($booking->user->email)
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
                        @if ($booking->establishment->owner->email)
                            <p class="mb-2"><strong>البريد الإلكتروني:</strong> {{ $booking->establishment->owner->email }}</p>
                        @endif
                        <p class="mb-0"><strong>رقم الهاتف:</strong> {{ $booking->establishment->owner->phone }}</p>
                    </div>
                </div>

                {{-- بيانات المنشأة --}}
                <div class="col-12 col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-primary mb-3"><i class="fas fa-building me-1"></i> المنشأة</h6>
                        <p class="mb-2"><strong>اسم المنشأة:</strong> {{ $booking->establishment->name }}</p>
                        @if ($booking->establishment->type)
                            <p class="mb-2"><strong>النوع:</strong> {{ $booking->establishment->type->name }}</p>
                        @endif
                        @if ($booking->establishment->address)
                            <p class="mb-2"><strong>العنوان:</strong> {{ $booking->establishment->address }}</p>
                        @endif
                        @if ($booking->establishment->image)
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $booking->establishment->image) }}" alt="صورة المنشأة"
                                    class="img-thumbnail rounded mb-1" style="max-width:100px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- تفاصيل إضافية: السعر + الدفع + التواريخ --}}
            <div class="row g-4 mt-3">
                {{-- السعر والباقة --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="border rounded-3 p-3 h-100 bg-light">
                        <h6 class="text-primary mb-3"><i class="fas fa-money-bill me-1"></i> تفاصيل السعر</h6>
                        @if ($booking->pricePackage)
                            <p class="mb-2"><strong>الباقة:</strong> {{ $booking->pricePackage->name }}</p>
                            <p class="mb-2"><strong>السعر:</strong> {{ number_format($booking->pricePackage->price, 2) }} ر.س</p>
                        @endif

                        @if ($booking->coupon)
                            <p class="mb-2"><strong>الكوبون:</strong> {{ $booking->coupon->code }}</p>
                            <p class="mb-2"><strong>قيمة الخصم:</strong> {{ $booking->discount_amount }} ر.س</p>
                        @endif

                        <p class="mb-0"><strong>الإجمالي:</strong> 
                            {{ number_format(($booking->pricePackage->price ?? 0) - ($booking->discount_amount ?? 0), 2) }} ر.س
                        </p>
                    </div>
                </div>

                {{-- الدفع --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-primary mb-3"><i class="fas fa-credit-card me-1"></i> الدفع</h6>
                        <p class="mb-2"><strong>الحالة:</strong>
                            <span class="badge {{ $booking->status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $booking->status == 'paid' ? 'مدفوع' : 'بانتظار الدفع' }}
                            </span>
                        </p>booking-receipts
                        @if ($booking->payment_receipt_image)
                            <p><strong>إيصال الدفع:</strong></p>
                            <img src="{{ asset('storage/booking-receipts/' . $booking->payment_receipt_image) }}" 
                                 alt="إيصال الدفع" class="img-thumbnail" style="max-width:150px;">
                        @endif
                    </div>
                </div>

                {{-- التواريخ --}}
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-primary mb-3"><i class="fas fa-clock me-1"></i> التواريخ</h6>
                        <p class="mb-2"><strong>تاريخ الحجز:</strong> {{ $booking->created_at?->format('Y-m-d H:i A') }}</p>
                        <p class="mb-2"><strong>آخر تحديث:</strong> {{ $booking->updated_at?->format('Y-m-d H:i A') }}</p>
                        @if ($booking->duration && $booking->created_at)
                            <p class="mb-0"><strong>تاريخ النهاية:</strong> 
                                {{ $booking->created_at->addHours($booking->duration)->format('Y-m-d H:i') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- الملاحظات --}}
            <div class="row g-4 mt-3">
                <div class="col-12">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-primary mb-3"><i class="fas fa-sticky-note me-1"></i> الملاحظات</h6>
                        <p class="text-muted mb-0">{{ $booking->notes ?? 'لا توجد ملاحظات' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- مخطط بياني مالي --}}
    <div class="card shadow-soft rounded-4 mt-4">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">التحليل المالي</h5>
        </div>
        <div class="card-body">
            <canvas id="financeChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const financeCtx = document.getElementById('financeChart').getContext('2d');
        new Chart(financeCtx, {
            type: 'bar',
            data: {
                labels: ['السعر الأصلي', 'الخصم', 'الإجمالي'],
                datasets: [{
                    data: [
                        {{ $booking->pricePackage->price ?? 0 }},
                        {{ $booking->discount_amount ?? 0 }},
                        {{ ($booking->pricePackage->price ?? 0) - ($booking->discount_amount ?? 0) }}
                    ],
                    backgroundColor: ['#007bff', '#ffc107', '#28a745']
                }]
            },
            options: { responsive: true }
        });
    </script>
</div>
