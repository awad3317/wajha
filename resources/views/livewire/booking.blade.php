<div class="container-fluid" >

    {{-- البطاقات الإحصائية المحسنة --}}
    <div class="row m-4">
        @php
            $cards = [
                [
                    'title' => 'إجمالي الحجوزات',
                    'count' => $stats['total'],
                    'icon' => 'fas fa-calendar',
                    'color' => 'primary',
                ],
                [
                    'title' => 'قيد الانتظار',
                    'count' => $stats['pending'],
                    'icon' => 'fas fa-hourglass-half',
                    'color' => 'secondary',
                ],
                ['title' => 'مدفوع', 'count' => $stats['paid'], 'icon' => 'fas fa-credit-card', 'color' => 'info'],
                [
                    'title' => 'مؤكد',
                    'count' => $stats['confirmed'],
                    'icon' => 'fas fa-check-circle',
                    'color' => 'primary',
                ],
                [
                    'title' => 'مكتمل',
                    'count' => $stats['completed'],
                    'icon' => 'fas fa-check-double',
                    'color' => 'success',
                ],
                [
                    'title' => 'ملغى',
                    'count' => $stats['cancelled'],
                    'icon' => 'fas fa-times-circle',
                    'color' => 'danger',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-md-2 mb-3">
                <div class="card text-white shadow-sm border-0"
                    style="background: linear-gradient(135deg,
                        {{ $card['color'] == 'primary' ? '#4e73df' : ($card['color'] == 'secondary' ? '#858796 ' : ($card['color'] == 'info' ? '#36b9cc' : ($card['color'] == 'success' ? '#1cc88a' : '#e74a3b'))) }} 60%, #ffffff 120%);">
                    <div class="card-body d-flex  justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">{{ $card['title'] }}</h6>
                            <h4 class=" text-right mb-0">{{ $card['count'] }}</h4>
                        </div>

                        <div>
                            <i class="{{ $card['icon'] }} fa-2x opacity-50"></i>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- البحث والفلاتر --}}
    <div class="row mb-3">
        <div class="col-md-6 mb-2">
            <div class="input-group input-group shadow-sm rounded-pill overflow-hidden">
                <div class="input-group-append">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-search text-secondary"></i>
                    </span>
                </div>
                <input type="text" class="form-control border-0 " placeholder="ابحث باسم اسم الحجز"
                    wire:model.debounce.300ms.live="search">
            </div>
        </div>
        <div class="col-md-6 mb-2">

            <div class="input-group shadow-sm rounded-pill overflow-hidden">
                <div class="input-group-append">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-check-circle text-success"></i>
                    </span>
                </div>
                <select wire:model.live="statusFilter" class="form-control  border-0">
                    <option value="">كل الحالات</option>
                    <option value="pending">قيد الانتظار</option>
                    <option value="waiting_payment">بانتظار الدفع</option>
                    <option value="paid">مدفوع</option>
                    <option value="confirmed">مؤكد</option>
                    <option value="cancelled">ملغى</option>
                    <option value="completed">مكتمل</option>
                </select>
            </div>
        </div>
        <div class="table-responsive shadow-sm rounded mt-3">
            <table class="table table-hover table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>العميل</th>
                        <th>رقم الهاتف</th>
                        <th>المنشأة</th>
                        <th>المالك</th>
                        <th>الباقة</th>
                        <th>سعر الباقة</th>
                        <th>تاريخ الحجز</th>
                        <th>موعد الحجز</th>
                        <th>الخصم</th>
                        <th>كود الخصم</th>
                        <th>حالة الحجز</th>
                        <th>الإيصال</th>
                        <th>العمليات</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->user->phone }}</td>
                            <td>{{ $booking->establishment->name }}</td>
                            <td>{{ $booking->establishment->owner->name ?? 'غير محدد' }}</td>
                            <td>{{ $booking->pricePackage->name }}</td>
                            <td>{{ $booking->pricePackage->price }} ريال</td>
                            <td>{{ $booking->created_at }} </td>
                            <td>{{ $booking->booking_date->format('Y-m-d H:i') }}</td>
                            <td>{{ $booking->discount_amount }} ريال</td>
                            <td>
                                {{ $booking->coupon->code ?? '-' }}
                            </td>

                            <td>
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
                                <span class="badge px-3 py-2 {{ $statusClasses[$booking->status] }}">
                                    {{ $statusLabels[$booking->status] }}
                                </span>
                            </td>
                            <td>
                                @if ($booking->payment_receipt_image)
                                    <img src="{{ config('app.url') }}/storage/establishment-image/{{ $booking->payment_receipt_image }}" width="50"
                                        class="rounded cursor-pointer" data-bs-toggle="modal"
                                        data-bs-target="#receiptModal{{ $booking->id }}">
                                    {{-- مودال الإيصال --}}
                                    <div class="modal fade" id="receiptModal{{ $booking->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">إيصال الدفع</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ config('app.url') }}/storage/establishment-image/ . $booking->payment_receipt_image) }}"
                                                        class="img-fluid rounded">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ url('show_booking/' . $booking->id) }}" class="btn btn-sm btn-success">
                                            عرض التفاصيل
                                        </a>


                                </div>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15">لا يوجد حجوزات</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>


        {{-- Pagination --}}
        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
