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


    {{-- البيانات الأساسية --}}
    <div class="card shadow-sm border-0 mb-5 ">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-info-circle mr-2"></i>البيانات الأساسية
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- الاسم -->
                <div class="col-md-6">
                    <small class="text-muted">الاسم</small>
                    <div class="fw-semibold fs-6">{{ $establishment->name }}</div>
                </div>

                <!-- النوع -->
                <div class="col-md-6">
                    <small class="text-muted">النوع</small>
                    <div>{{ $establishment->type->name ?? '-' }}</div>
                </div>

                <!-- المالك -->
                <div class="col-md-6">
                    <small class="text-muted">المالك</small>
                    <div>{{ $establishment->owner->name ?? '-' }}</div>
                </div>

                <!-- المنطقة -->
                <div class="col-md-6">
                    <small class="text-muted">المنطقة</small>
                    <div>{{ $establishment->region->name ?? '-' }}</div>
                </div>

                <!-- العنوان -->
                <div class="col-12">
                    <small class="text-muted">العنوان</small>
                    <div class="bg-light rounded p-2 border">
                        {{ $establishment->address ?? 'غير متوفر' }}
                    </div>
                </div>

                <!-- الحالة -->
                <div class="col-md-4">
                    <small class="text-muted">الحالة</small><br>
                    <span
                        class="badge rounded-pill bg-{{ $establishment->is_active ? 'success' : 'danger' }} px-3 py-2">
                        {{ $establishment->is_active ? 'مفعلة' : 'غير مفعلة' }}
                    </span>
                </div>

                <!-- التحقق -->
                <div class="col-md-4">
                    <small class="text-muted">التحقق</small><br>
                    <span
                        class="badge rounded-pill bg-{{ $establishment->is_verified ? 'info' : 'secondary' }} px-3 py-2">
                        {{ $establishment->is_verified ? 'موثقة' : 'غير موثقة' }}
                    </span>
                </div>

                <!-- الموقع على الخريطة -->
                <div class="col-md-4">
                    <small class="text-muted">الموقع على الخريطة</small><br>
                    <a href="https://www.google.com/maps?q={{ $establishment->latitude }},{{ $establishment->longitude }}"
                        target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                        <i class="fas fa-map-marker-alt ms-1"></i> عرض الخريطة
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-4">
        {{-- معرض الصور --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0  h-100">
                <div class="card-header bg-secondary text-white fw-bold">
                    <i class="fas fa-images mr-2"></i> معرض الصور
                </div>
                <div class="card-body p-0">
                    <div class="overflow-auto p-3" style="max-height: 300px;">
                        <div class="row g-3">
                            @forelse ($images as $image)
                                <div class="col-6 col-md-4">
                                    <img src="{{ url($image->image) }}" alt="صورة"
                                        class="img-fluid rounded shadow-sm border"
                                        style="object-fit: cover; width: 100%; height: 120px;">
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted">لا توجد صور.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- المميزات --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0  h-100">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="fas fa-star mr-2"></i> المميزات
                </div>
                <div class="card-body p-3 overflow-auto" style="max-height: 300px;">
                    <div class="features-list">
                        @forelse ($features as $feature)
                            <div class="feature-item">
                                <div class="feature-icon-wrapper">
                                    @if ($feature->icon)
                                        <div class="feature-icon-img">
                                            <img src="{{ url($feature->icon) }}" alt="{{ $feature->name }}"
                                                class="img-fluid">
                                        </div>
                                    @else
                                        <div class="feature-icon-default">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="feature-content">
                                    <h5 class="feature-title">{{ $feature->name }}</h5>
                                    <p class="feature-desc">{{ $feature->description }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="no-features">
                                <i class="far fa-folder-open"></i>
                                <p>لا توجد مميزات متاحة حالياً</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        {{-- المواصفات --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0  h-100">
                <div class="card-header bg-warning text-white fw-bold">
                    <i class="fas fa-cogs mr-2"></i> المواصفات
                </div>
                <div class="card-body p-0">
                    <div class="overflow-auto" style="max-height: 300px;">
                        <ul class="list-group list-group-flush">
                            @forelse ($specifications as $spec)
                                <li class="list-group-item d-flex align-items-center justify-content-end">
                                    <span class="mr-2">
                                        @if ($spec->icon)
                                            <img src="{{ asset('storage/' . $spec->icon) }}" alt="أيقونة"
                                                class="img-fluid" style="max-width: 20px; max-height: 20px;">
                                        @else
                                            <i class="fas fa-info-circle text-secondary"></i>
                                        @endif
                                    </span>
                                    {{ $spec->name }}
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">لا توجد مواصفات.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- القواعد --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0  h-100">
                <div class="card-header bg-info text-white fw-bold">
                    <i class="fas fa-list-ol mr-2"></i>القواعد
                </div>
                <div class="card-body p-0">
                    <div class="overflow-auto p-3" style="max-height: 300px;">
                        @if ($rules->count())
                            <ol class="list-group list-group-numbered list-unstyled ps-3 pe-0">
                                @foreach ($rules as $rule)
                                    <li class="mb-2">{{ $rule->rule }}</li>
                                @endforeach
                            </ol>
                        @else
                            <div class="text-center text-muted">لا توجد قواعد.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
