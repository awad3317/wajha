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
    {{-- <div class="row">
        @forelse ($establishments as $establishment)
            <div 
                class="col-xl-3 col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                <a href="{{ route('establishment.show', $establishment->id) }}" class="card shadow-sm rounded-lg w-100 text-right">
                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                        <img src="{{ url($establishment->primary_image) }}" class="w-100 h-100"
                            style="object-fit: cover;" alt="{{ $establishment->name }}">

                        <span wire:click="toggleVerification({{ $establishment->id }})"
                            class="{{ $establishment->is_verified ? 'badge-green' : 'badge-red' }} position-absolute"
                            style="top: 10px; left: 10px; font-size: 0.9rem; cursor: pointer;">
                            {{ $establishment->is_verified ? 'موثقة' : 'غير موثقة' }}
                        </span>


                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2 text-truncate">{{ $establishment->name }}</h5>

                        <div class="row text-right">
                            <div class="col-7 d-flex justify-content-end align-items-center gap-2">
                                <span class="mx-1">{{ $establishment->type->name ?? '-' }}</span>
                                <strong>:النوع</strong>
                                <i class="fas fa-building text-primary mx-1"></i>
                            </div>
                            <div class="col-5 d-flex justify-content-end align-items-center gap-2">
                                <span class="mx-1">{{ $establishment->region->name ?? '-' }}</span>
                                <strong>:المنطقة</strong>
                                <i class="fas fa-map-marker-alt text-danger mx-1"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center shadow-sm rounded">
                    لا توجد منشآت مطابقة لبحثك.
                </div>
            </div>
        @endforelse
    </div>
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-center">
            {{ $establishments->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</div> --}}
    <div class="row">
        @forelse ($establishments as $establishment)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                <div class="card shadow-sm rounded-lg w-100 text-right position-relative">
                    <a href="{{ route('establishment.show', $establishment->id) }}"
                        class="text-decoration-none text-dark">
                        <div style="height: 180px; overflow: hidden;">
                            <img src="{{ url($establishment->primary_image) }}" class="w-100 h-100"
                                style="object-fit: cover;" alt="{{ $establishment->name }}">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2 text-truncate">{{ $establishment->name }}</h5>

                            <div class="row text-right">
                                <div class="col-7 d-flex justify-content-end align-items-center gap-2">
                                    <span class="mx-1">{{ $establishment->type->name ?? '-' }}</span>
                                    <strong>:النوع</strong>
                                    <i class="fas fa-building text-primary mx-1"></i>
                                </div>
                                <div class="col-5 d-flex justify-content-end align-items-center gap-2">
                                    <span class="mx-1">{{ $establishment->region->name ?? '-' }}</span>
                                    <strong>:المنطقة</strong>
                                    <i class="fas fa-map-marker-alt text-danger mx-1"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- حالة التوثيق (خارج الرابط) --}}
                    <span wire:click.prevent="toggleVerification({{ $establishment->id }})"
                        class="{{ $establishment->is_verified ? 'badge-green' : 'badge-red' }} position-absolute"
                        style="top: 10px; left: 10px; font-size: 0.9rem; cursor: pointer; z-index: 10;">
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
