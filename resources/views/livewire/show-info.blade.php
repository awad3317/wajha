<style>
    /* خطوط ونمط عام */
    .section-header {
        font-weight: 700;
        font-size: 1.4rem;
        letter-spacing: 0.05em;
        padding: 10px 20px;
        border-radius: 6px 6px 0 0;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        font-family: 'Cairo', sans-serif;
    }

    .card-custom {
        border-radius: 10px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }

    .card-custom:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
    }

    .badge-custom {
        font-weight: 700;
        font-size: 1rem;
        padding: 0.4em 1em;
        border-radius: 50px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease;
    }

    .badge-custom:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    }

    .stats-box {
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: default;
    }

    .stats-box:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .stats-icon {
        font-size: 2.8rem;
        opacity: 0.85;
    }

    .img-gallery {
        border-radius: 15px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        transition: transform 0.3s ease;
        cursor: pointer;
        object-fit: cover;
        height: 200px;
        width: 100%;
    }

    .img-gallery:hover {
        transform: scale(1.05);
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.25);
        z-index: 10;
    }

    .feature-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        object-fit: contain;
        margin-left: 15px;
    }

    .feature-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.1rem;
    }

    .spec-icon {
        width: 28px;
        height: 28px;
        margin-left: 12px;
        opacity: 0.7;
        filter: drop-shadow(0 0 1px rgba(0, 0, 0, 0.1));
    }

    .rules-list li {
        font-size: 1rem;
        line-height: 1.7;
        border-left: 4px solid #007bff;
        padding-left: 15px;
        margin-bottom: 10px;
        background-color: #f7f9fc;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: background-color 0.3s ease;
    }

    .rules-list li:hover {
        background-color: #e9f1ff;
    }

    table.table-bordered th,
    table.table-bordered td {
        vertical-align: middle;
        font-size: 1rem;
    }
</style>

<div class="container py-4">

    {{-- البيانات الأساسية --}}
    <div class="card card-custom mb-5 text-right">
        <div class="card-header bg-primary text-white section-header">البيانات الأساسية</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">الاسم</h6>
                    <p class="font-weight-bold">{{ $establishment->name }}</p>
                </div>
                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">النوع</h6>
                    <p>{{ $establishment->type->name ?? '-' }}</p>
                </div>

                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">المالك</h6>
                    <p>{{ $establishment->owner->name ?? '-' }}</p>
                </div>
                <div class="col-md-6 mb-4">
                    <h6 class="text-muted">المنطقة</h6>
                    <p>{{ $establishment->region->name ?? '-' }}</p>
                </div>

                <div class="col-12 mb-4">
                    <h6 class="text-muted">العنوان</h6>
                    <p>{{ $establishment->address ?? 'غير متوفر' }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <h6 class="text-muted">الحالة</h6>
                    <span class="badge badge-custom badge-{{ $establishment->is_active ? 'success' : 'danger' }}">
                        {{ $establishment->is_active ? 'مفعلة' : 'غير مفعلة' }}
                    </span>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="text-muted">التحقق</h6>
                    <span class="badge badge-custom badge-{{ $establishment->is_verified ? 'info' : 'secondary' }}">
                        {{ $establishment->is_verified ? 'موثقة' : 'غير موثقة' }}
                    </span>
                </div>
                <div class="col-md-4 mb-3">
                    <h6 class="text-muted">الموقع على الخريطة</h6>
                    <a href="https://www.google.com/maps?q={{ $establishment->latitude }},{{ $establishment->longitude }}"
                        target="_blank" class="btn btn-outline-primary btn-sm font-weight-bold">
                        عرض الخريطة <i class="fas fa-map-marker-alt mr-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- إحصائيات --}}
    <div class="row text-center mb-5">
        @php
            $stats = [
                ['label' => 'عدد الصور', 'count' => $images->count(), 'icon' => 'fas fa-image', 'bg' => 'bg-info'],
                [
                    'label' => 'عدد الميزات',
                    'count' => $features->count(),
                    'icon' => 'fas fa-star',
                    'bg' => 'bg-success',
                ],
                [
                    'label' => 'عدد المواصفات',
                    'count' => $specifications->count(),
                    'icon' => 'fas fa-list',
                    'bg' => 'bg-warning',
                ],
                ['label' => 'عدد القواعد', 'count' => $rules->count(), 'icon' => 'fas fa-gavel', 'bg' => 'bg-primary'],
                [
                    'label' => 'أيام عدم التوفر',
                    'count' => $unavailabilities->count(),
                    'icon' => 'fas fa-calendar-times',
                    'bg' => 'bg-danger',
                ],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="col-md-2 col-6 mb-4">
                <div class="stats-box text-white {{ $stat['bg'] }} p-3 shadow-sm">
                    <i class="{{ $stat['icon'] }} stats-icon mb-2"></i>
                    <h3 class="font-weight-bold mb-0">{{ $stat['count'] }}</h3>
                    <small>{{ $stat['label'] }}</small>
                </div>
            </div>
        @endforeach
    </div>

    {{-- معرض الصور --}}
    <div class="card card-custom mb-5 text-right">
        <div class="card-header bg-secondary text-white section-header">معرض الصور</div>
        <div class="card-body">
            <div class="row">
                @forelse ($images as $image)
                    <div class="col-md-3 col-6 mb-4">
                        <img src="{{ asset('storage/' . $image->image) }}" alt="صورة"
                            class="img-gallery shadow-sm">
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">لا توجد صور.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- المميزات --}}
    <div class="card card-custom mb-5 text-right">
        <div class="card-header bg-success text-white section-header">المميزات</div>
        <div class="card-body">
            <div class="row">
                @forelse ($features as $feature)
                    <div class="col-md-6 d-flex align-items-start mb-4">
                        @if ($feature->icon)
                            <img src="{{ asset('storage/' . $feature->icon) }}" alt="أيقونة" class="feature-icon">
                        @else
                            <i class="fas fa-check-circle text-success fa-lg mr-3 mt-1"></i>
                        @endif
                        <div>
                            <h6 class="feature-title">{{ $feature->name }}</h6>
                            <p class="text-muted mb-0">{{ $feature->description }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">لا توجد مميزات.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- المواصفات --}}
    <div class="card card-custom mb-5 text-right">
        <div class="card-header bg-warning text-dark section-header">المواصفات</div>
        <div class="card-body">
            <ul class="list-group list-group-flush text-right">
                @forelse ($specifications as $spec)
                    <li class="list-group-item d-flex align-items-center">
                        @if ($spec->icon)
                            <img src="{{ asset('storage/' . $spec->icon) }}" alt="أيقونة" class="spec-icon">
                        @else
                            <i class="fas fa-info-circle text-secondary ml-3"></i>
                        @endif
                        {{ $spec->name }}
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted">لا توجد مواصفات.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- القواعد --}}
    <div class="card card-custom mb-5 text-right">
        <div class="card-header bg-info text-white section-header">القواعد</div>
        <div class="card-body">
            <ol class="rules-list list-group list-group-numbered text-right mb-0">
                @forelse ($rules as $rule)
                    <li>{{ $rule->rule }}</li>
                @empty
                    <li class="text-center text-muted">لا توجد قواعد.</li>
                @endforelse
            </ol>
        </div>
    </div>

    <!-- {{-- الأيام غير المتاحة --}}
    <div class="card card-custom mb-5 text-right">
        <div class="card-header bg-danger text-white section-header">الأيام غير المتاحة</div>
        <div class="card-body p-0">
            @if ($unavailabilities->isNotEmpty())
                <table class="table table-striped table-bordered text-right m-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unavailabilities as $un)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($un->unavailable_date)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-4 text-center text-muted">لا توجد أيام غير متاحة.</div>
            @endif
        </div>
    </div> -->

</div>
