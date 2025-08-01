<div>
    <div class="row mb-4">
        <div class="col-md-12 mb-2">
            <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                <input type="text" class="form-control border-0 text-right" placeholder="...ابحث باسم المنشأة"
                    wire:model.debounce.300ms.live="search">

                <div class="input-group-append">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-search text-secondary"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-2">
            <div class="input-group shadow-sm rounded-pill overflow-hidden">
                <select wire:model.live="selectedType" class="form-control text-right border-0">
                    <option value="">كل الأنواع</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-building text-primary"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-2">
            <div class="input-group shadow-sm rounded-pill overflow-hidden">
                <select wire:model.live="selectedStatus" class="form-control text-right border-0">
                    <option value="">كل الحالات</option>
                    <option value="1">موثقة</option>
                    <option value="0">غير موثقة</option>
                </select>
                <div class="input-group-append">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-check-circle text-success"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- عرض المنشآت -->

    <div class="row">
        @forelse ($establishments as $establishment)
            <div class=" col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                <div class="package-card shadow-sm w-100 text-right position-relative">
                    <a href="{{ route('establishment.show', $establishment->id) }}"
                        class="text-decoration-none text-dark">
                        <div class="package-header" style="height: 180px; overflow: hidden; padding: 0;">
                            <img src="{{ url($establishment->primary_image) }}" class="w-100 h-100"
                                style="object-fit: cover;" alt="{{ $establishment->name }}">
                        </div>

                        <div class="package-body d-flex flex-column">
                            <div class="row mb-2">
                                <div class="col-6 d-flex justify-content-end align-items-center">

                                    <h5 class="mb-0 text-primary text-truncate"
                                        style="width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"
                                        title="{{ $establishment->name }}">
                                        {{ $establishment->name }}
                                    </h5>
                                    <i class="fas fa-store-alt text-primary ml-2"></i>
                                </div>
                                <div class="col-6 d-flex justify-content-end align-items-center">

                                    <h5 class="mb-0 text-primary text-truncate"
                                        style="width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"
                                        title="{{ $establishment->name }}">
                                        {{ $establishment->owner->name }}
                                    </h5>
                                    <i class="fas fa-user text-primary mx-2"></i>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6 d-flex justify-content-end align-items-center text-muted">
                                    <span>{{ $establishment->type->name ?? '-' }}</span>
                                    <i class="fas fa-building text-primary mx-2"></i>
                                </div>
                                <div class="col-6 d-flex justify-content-end align-items-center text-muted">
                                    <span>{{ $establishment->region->name ?? '-' }}</span>
                                    <i class="fas fa-map-marker-alt text-danger mx-2"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- حالة التوثيق (خارج الرابط) --}}
                    <span wire:click.prevent="toggleVerification({{ $establishment->id }})"
                        class="feature-badge position-absolute"
                        style="top: 10px; left: 10px; font-size: 0.9rem; cursor: pointer; z-index: 10; background: {{ $establishment->is_verified ? '#4CAF50' : '#F44336' }}!important; color: white !important;">
                        {{ $establishment->is_verified ? 'موثقة' : 'غير موثقة' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center shadow-sm rounded">
                    لا توجد منشآت مطابقة لبحثك.
                </div>
            </div>
        @endforelse
    </div>

    {{-- روابط التصفح --}}
    @if ($establishments->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $establishments->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    @endif
