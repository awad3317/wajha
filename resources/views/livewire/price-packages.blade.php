<div>


    <style>
        body {
            background: #f6f8fb;
        }

        .main-title {
            font-weight: 700;
            letter-spacing: 1px;
            color: #1565c0;
            margin-bottom: 2rem;
        }

        .add-btn {
            border-radius: 2rem;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 0.7rem 2.2rem;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.07);
        }

        .card {
            border-radius: 1.5rem;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .card-header {
            border-radius: 1.5rem 1.5rem 0 0;
            background: linear-gradient(90deg, #4f8cff 0%, #6dd5ed 100%);
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            border-bottom: none;
            padding: 1.2rem 1.5rem;
        }

        .input-group-xl .form-control {
            font-size: 1.15rem;
            padding: 1rem 1.2rem;
            border-radius: 2rem;
        }

        .package-card {
            border-radius: 1.5rem;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            transition: box-shadow 0.2s, transform 0.2s;
            position: relative;
        }

        .package-card:hover {
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-6px) scale(1.02);
        }

        .package-header {
            background: linear-gradient(90deg, #4f8cff 0%, #6dd5ed 100%);
            color: #fff;
            padding: 1.5rem 1rem 1rem;
            position: relative;
            text-align: right;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .package-icon {
            position: absolute;
            left: 1.25rem;
            top: 1.25rem;
            font-size: 2.8rem;
            color: rgba(255, 255, 255, 0.18);
            pointer-events: none;
        }

        .price-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #fff;
            color: #4f8cff;
            font-weight: bold;
            border-radius: 2rem;
            padding: 0.45em 1.2em;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            z-index: 5;
        }

        .package-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .feature-badge {
            font-size: 0.85rem;
            background: linear-gradient(90deg, #e3f2fd 0%, #bbdefb 100%);
            color: #0d47a1;
            margin: 0.2rem 0.25rem 0.25rem 0;
            display: inline-block;
            border-radius: 0.6rem;
            padding: 0.3em 0.8em;
            box-shadow: 0 1px 3px rgba(33, 150, 243, 0.1);
        }

        .btn-pill {
            border-radius: 50rem;
            padding: 0.35rem 1.1rem;
            font-weight: 500;
        }

        .pagination .page-link {
            border-radius: 1rem !important;
            margin: 0 0.15rem;
        }
    </style>

    <div class="container mt-4">
        <h3 class="main-title text-right"> إدارة الباقات </h3>

        @if (session()->has('success'))
            <div class="alert alert-success text-right rounded-pill px-4 py-2 mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if (!$showForm)
            <button wire:click="create" class="btn btn-primary add-btn mb-4 " ">
                    إضافة باقة جديدة
                    <i class="fas fa-plus-circle me-2"></i>
            </button>
        @endif

        @if ($showForm)
            <div class="card mb-4 shadow-sm border-primary rounded-4 text-right">
                <div class="card-header text-right">
                    {{ $isEdit ? 'تعديل الباقة' : 'إضافة باقة جديدة' }}
                     <i class="fas fa-gift me-2"></i>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="row g-4 text-right">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">السعر <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg rounded-pill shadow-sm">
                                    <input type="number" step="0.01" min="0"
                                        class="form-control border-0 text-right" wire:model.defer="price"
                                        placeholder="0.00">
                                    <span class="input-group-text bg-white border-0 rounded-0">
                                        <i class="fas fa-dollar-sign text-primary fs-5"></i>
                                    </span>
                                </div>
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 text-right">
                                <label class="form-label fw-semibold ">اسم الباقة <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg rounded-pill text-right"
                                    wire:model.defer="name" placeholder="أدخل اسم الباقة" autofocus>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12 my-2">
                                <label class="form-label fw-semibold">الوصف</label>
                                <textarea class="form-control rounded-3 text-right" wire:model.defer="description" rows="3"
                                    placeholder="وصف الباقة (اختياري)"></textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">المؤسسة <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                                    <select wire:model.defer="establishment_id"
                                        class="form-control text-right border-0">
                                        <option value="">-- اختر مؤسسة --</option>
                                        @foreach ($establishments as $est)
                                            <option value="{{ $est->id }}">{{ $est->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text bg-white border-0 rounded-0">
                                        <i class="fas fa-building text-primary fs-5"></i>
                                    </span>
                                </div>
                                @error('establishment_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 my-2">
                                <label class="form-label fw-semibold">أيقونة الباقة<span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                                    <select wire:model.defer="icon_id" class="form-control text-right border-0">
                                        <option value="">-- اختر أيقونة --</option>
                                        @foreach ($icons as $icon)
                                            <option value="{{ $icon->id }}">
                                                {{ $icon->name ?? 'أيقونة #' . $icon->id }}</option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text bg-white border-0 rounded-0">
                                        <i class="fas fa-icons text-primary fs-5"></i>
                                    </span>
                                </div>
                                @error('icon_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">الحالة</label>
                                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                                    <select wire:model.defer="is_active" class="form-control text-right border-0">
                                        <option value="1">نشط</option>
                                        <option value="0">غير نشط</option>
                                    </select>
                                    <span class="input-group-text bg-white border-0 rounded-0">
                                        <i class="fas fa-toggle-on text-success fs-5"></i>
                                    </span>
                                </div>
                                @error('is_active')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">المميزات (يفصل بينها بفواصل)</label>
                                <input type="text" class="form-control form-control-lg rounded-pill text-right"
                                    wire:model.defer="featuresInput" placeholder="مثل: ميزة 1, ميزة 2, ميزة 3">
                                @error('features')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm rounded-pill">
                                حفظ</button>
                            <button type="button" wire:click="$set('showForm', false)"
                                class="btn btn-outline-secondary btn-lg px-5 shadow-sm rounded-pill">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Search --}}
        <div class="col-md-12 mb-4">
            <div class="input-group input-group-xl shadow-sm rounded-pill overflow-hidden">
                <input type="text" class="form-control border-0 text-right" placeholder="...ابحث باسم الاعلان"
                    wire:model.debounce.300ms.live="search">
                <div class="input-group-append">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-search text-secondary"></i>
                    </span>
                </div>
            </div>
        </div>

        {{-- Packages Table --}}
        <div class="card-body">
            <div class="row g-4">
                @forelse ($packages as $package)
                    <div class="col-12 col-md-6 col-lg-4 text-right">
                        <div class="card package-card h-100 border-0">
                            <div class="package-header">
                                <span class="price-badge">
                                    {{ number_format($package->price, 2) }} <span style="font-size:0.85em;">$</span>
                                </span>
                                <i class="fas fa-gift package-icon"></i>
                                <h5 class="card-title fw-bold mb-0">{{ $package->name }}</h5>
                            </div>

                            <div class="package-body ">
                                <p class="text-muted mb-2 text-truncate" style="min-height: 48px;">
                                    {{ $package->description ?? 'لا يوجد وصف' }}
                                </p>

                                <p class="mb-1">
                                    <strong>المؤسسة:</strong>
                                    {{ $package->establishment->name ?? '-' }}
                                </p>

                                <p class="mb-3">
                                    <span class="badge bg-{{ $package->is_active ? 'success' : 'danger' }}">
                                        <i
                                            class="fas fa-{{ $package->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                        {{ $package->is_active ? 'نشطة' : 'غير نشطة' }}
                                    </span>
                                    <strong>:الحالة</strong>
                                </p>

                                <div class="mb-3 flex-grow-1 overflow-auto" style="max-height: 70px;">
                                    <strong>:المميزات</strong>
                                    <div class="mt-2">
                                        @forelse ($package->features ?? [] as $feature)
                                            <span class="feature-badge">{{ $feature }}</span>
                                        @empty
                                            <span class="text-muted">لا توجد مميزات</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="mt-auto d-flex justify-content-between">
                                    <button wire:click="edit({{ $package->id }})"
                                        class="btn btn-outline-primary btn-sm btn-pill">
                                        <i class="fas fa-edit"></i> تعديل
                                    </button>
                                    <button wire:click="delete({{ $package->id }})"
                                        onclick="confirm('هل أنت متأكد من الحذف؟') || event.stopImmediatePropagation()"
                                        class="btn btn-outline-danger btn-sm btn-pill">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">لا توجد باقات مسجلة.</div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        @if ($packages->hasPages())
            <div class="card-footer d-flex justify-content-center bg-transparent border-0">
                {{ $packages->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
