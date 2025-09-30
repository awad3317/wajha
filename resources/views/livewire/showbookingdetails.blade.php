<div class="container py-4">

    {{-- تفاصيل الحجز --}}
    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <div class="card-header bg-gradient bg-primary text-white rounded-top-4">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> تفاصيل الحجز</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">

                {{-- صاحب الحجز --}}
                <div class="col-12 col-md-4">
                    <div class="card h-100 shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i> صاحب الحجز</h6>
                            <p><strong>الاسم:</strong> {{ $booking->user->name }}</p>
                            <p><strong>الهاتف:</strong> {{ $booking->user->phone }}</p>

                            <p><strong>الحالة:</strong>
                                <span class="badge {{ $booking->user->is_banned ? 'bg-danger' : 'bg-success' }}">
                                    {{ $booking->user->is_banned ? 'محظور' : 'نشط' }}
                                </span>
                            </p>
                            <p><strong>نوع المستخدم:</strong> {{ $booking->user->user_type }}</p>
                        </div>
                    </div>
                </div>

                {{-- المنشأة --}}
                <div class="col-12 col-md-4">
                    <div class="card h-100 shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="fas fa-building me-2"></i> المنشأة</h6>
                            <p><strong>الاسم:</strong> {{ $booking->establishment->name }}</p>
                            <p><strong>العنوان:</strong> {{ $booking->establishment->address }}</p>
                            <p><strong>النوع:</strong> {{ $booking->establishment->type->name ?? '-' }}</p>
                            <p><strong>المنطقة:</strong> {{ $booking->establishment->region->name ?? '-' }}</p>
                            <p><strong>الحالة:</strong>
                                <span
                                    class="badge {{ $booking->establishment->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $booking->establishment->is_active ? 'فعالة' : 'غير فعالة' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- الباقة --}}
                <div class="col-12 col-md-4">
                    <div class="card h-100 shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="fas fa-box me-2"></i> الباقة</h6>
                            <p><strong>الاسم:</strong> {{ $booking->pricePackage->name }}</p>
                            <p><strong>الوصف:</strong> {{ $booking->pricePackage->description }}</p>
                            <p><strong>السعر:</strong> {{ $booking->pricePackage->price }}
                                {{ $booking->pricePackage->currency->name ?? '' }}</p>
                            <p><strong>الفترة:</strong> {{ $booking->pricePackage->time_period }}</p>
                            @if ($booking->pricePackage->features)
                                <p><strong>المميزات:</strong></p>
                                <ul class="small">
                                    @foreach ($booking->pricePackage->features as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- السعر والتحليل --}}
            <di class="row g-4 mt-3">
                <div class="col-6 col-lg-6">
                    <div class="card h-100 shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="fas fa-money-bill me-2"></i> تفاصيل السعر</h6>
                            <p><strong>الباقة:</strong> {{ $booking->pricePackage->name ?? '-' }}</p>
                            <p><strong>السعر:</strong> {{ number_format($booking->pricePackage->price ?? 0, 2) }} ر.س
                            </p>
                            <p><strong>الخصم:</strong> {{ number_format($booking->discount_amount ?? 0, 2) }} ر.س</p>
                            <hr>
                            <h5 class="text-success">الإجمالي:
                                {{ number_format(($booking->pricePackage->price ?? 0) - ($booking->discount_amount ?? 0), 2) }}
                                ر.س
                            </h5>
                        </div>
                    </div>
                </div>
                {{-- الملاحظات --}}

                <div class="col-6 col-lg-6">
                    <div class="card shadow-sm border-0 rounded-3 bg-warning-subtle">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="fas fa-sticky-note me-2"></i> الملاحظات</h6>
                            <p class="mb-0 fst-italic">{{ $booking->notes ?? 'لا توجد ملاحظات' }}</p>
                        </div>
                    </div>
                </div>

        </div>



        {{-- التواريخ --}}
        <div class="row g-4 mt-3">
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fas fa-clock me-2"></i> التواريخ</h6>
                        <p><strong>تاريخ الحجز:</strong> {{ $booking->booking_date?->format('Y-m-d') }}</p>
                        <p><strong>أنشئ في:</strong> {{ $booking->created_at?->format('Y-m-d H:i') }}</p>
                        <p><strong>آخر تحديث:</strong> {{ $booking->updated_at?->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- إيصال الدفع --}}
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h6 class="text-primary mb-3"><i class="fas fa-credit-card me-2"></i> الدفع</h6>
                        <p><strong>الحالة:</strong>
                            <span
                                class="badge {{ $booking->status == 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $booking->status == 'paid' ? 'مدفوع' : 'بانتظار الدفع' }}
                            </span>
                        </p>
                        @if ($booking->payment_receipt_image)
                            <p><strong>إيصال الدفع:</strong></p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#receiptModal">
                                <img src="{{ asset($booking->payment_receipt_image) }}"
                                    class="img-fluid rounded shadow" style="max-width:180px; cursor: zoom-in;">
                            </a>

                            <!-- Modal -->
                            <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content bg-transparent border-0">
                                        <div class="modal-body text-center">
                                            <img src="{{ asset($booking->payment_receipt_image) }}"
                                                class="img-fluid rounded shadow"
                                                style="max-width:100%; cursor: zoom-in;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-12">
            <div class="card h-100 shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <h5 class="text-primary mb-4">
                        <i class="fas fa-route me-2"></i> تتبع حالة الحجز
                    </h5>

                    <div class="timeline d-flex flex-wrap justify-content-between position-relative">
                        @foreach ($bookingLogs as $log)
                            <div class="timeline-step text-center flex-fill position-relative mb-4">
                                {{-- أيقونة الحالة --}}
                                <div
                                    class="timeline-icon 
                                @if ($log->to_status == 'pending') bg-warning text-dark
                                @elseif($log->to_status == 'waiting_payment') bg-secondary text-white
                                @elseif($log->to_status == 'paid') bg-info text-white
                                @elseif($log->to_status == 'confirmed') bg-primary text-white
                                @elseif($log->to_status == 'completed') bg-success text-white
                                @elseif($log->to_status == 'cancelled') bg-danger text-white @endif">
                                    <i class="fas fa-check"></i>
                                </div>

                                {{-- تفاصيل الحالة --}}
                                <div class="mt-2 small fw-bold">
                                    من <span class="text-muted">{{ $log->from_status }}</span>
                                    إلى <span class="text-primary">{{ $log->to_status }}</span>
                                </div>
                                <div class="text-muted small">{{ $log->user?->name ?? 'النظام' }}</div>
                                <div class="text-secondary small">{{ $log->created_at->format('Y-m-d H:i') }}</div>

                                {{-- الخط بين المراحل --}}
                                @if (!$loop->last)
                                    <div class="timeline-line"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if ($bookingLogs->isEmpty())
                        <p class="text-muted text-center mt-3">لا يوجد تتبع للحالة حتى الآن</p>
                    @endif
                </div>
            </div>
        </div>

        <style>
            .timeline {
                margin-top: 20px;
                position: relative;
            }

            .timeline-step {
                position: relative;
                min-width: 160px;
                flex: 1;
            }

            .timeline-icon {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0px 20px;
                font-size: 18px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                z-index: 2;
            }

            .timeline-line {
                position: absolute;
                top: 25px;
                left: 50%;
                transform: translateX(50%);
                width: 100%;
                height: 4px;
                background: #dee2e6;
                z-index: 1;
            }

            /* آخر عنصر بدون خط */
            .timeline-step:last-child .timeline-line {
                display: none;
            }

            /* موبايل: العمود يصير عمودي */
            @media (max-width: 768px) {
                .timeline {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .timeline-step {
                    flex: none;
                    width: 100%;
                    text-align: left;
                    margin-bottom: 30px;
                }

                .timeline-line {
                    top: 50px;
                    left: 25px;
                    width: 4px;
                    height: calc(100% - 50px);
                    transform: none;
                }

                .timeline-step:last-child .timeline-line {
                    display: none;
                }

                .timeline-icon {
                    margin: 0;
                }
            }
        </style>

    </div>


</div>
</div>

</div>
