<div>
    <div class="container py-4">
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
            <h3 class="fw-bold text-primary mb-2 mb-sm-0 text-center text-sm-start">إدارة كوبونات الخصم</h3>

            @if (!$showForm && !$isEdit)
                <button wire:click="create" class="btn btn-primary rounded-pill add-btn px-4 py-2">
                    إضافة كوبون جديد
                    <i class="fas fa-plus-circle ms-2"></i>
                </button>
            @endif
        </div>

        {{-- Form Section --}}
        @if ($showForm || $isEdit)
            <div class="card mb-4 border-0  shadow-lg">
                <div class="card-header  bg-primary text-white py-3">
                    <h5 class="mb-0 text-right">
                        {{ $isEdit ? 'تعديل كود خصم ' : 'إضافة كود خصم جديد' }}
                        <i class="fas fa-edit ml-2"></i>
                    </h5>
                </div>
                <div class="card-body text-right">

                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" enctype="multipart/form-data">
                        <!-- المعلومات الأساسية -->
                        <h5 class="border-bottom pb-2 mb-3">المعلومات الأساسية</h5>
                        <div class="form-row">

                            <div class="form-group col-md-1 d-flex align-items-center mt-4 ">
                                <div class="d-flex align-items-center justify-content-start mt-2">
                                    <span class="me-2 small">{{ $is_active ? 'مفعل' : 'غير مفعل' }}</span>
                                    <div class="form-switch {{ $is_active ? 'switch-active' : 'switch-banned' }}">
                                        <input type="checkbox" id="isActiveSwitch" wire:model.live="is_active"
                                            @if ($is_active) checked @endif>
                                        <label for="isActiveSwitch"></label>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group col-md-5">
                                <label for="discount_type">نوع الخصم <span class="text-danger"><span
                                            class="text-danger">*</span></span></label>
                                <select wire:model.live="discount_type"
                                    class="form-control text-right @error('discount_type') is-invalid @enderror "
                                    id="discount_type">
                                    <option value="percentage">نسبة مئوية %</option>
                                    <option value="fixed_amount">مبلغ ثابت</option>
                                    @error('discount_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </select>

                            </div>

                            <div class="form-group col-md-6">
                                <label for="code ">كود الكوبون <span class="text-danger"><span
                                            class="text-danger">*</span></span></label>
                                <input type="text" wire:model="code"
                                    class="form-control text-right @error('code') is-invalid @enderror  " id="code"
                                    placeholder="مثال: SUMMER20">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label for="discount_value">قيمة الخصم <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" wire:model="discount_value"
                                        class="form-control text-right @error('discount_value') is-invalid @enderror  "
                                        id="discount_value" placeholder="0">

                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            @if ($discount_type == 'percentage')
                                                %
                                            @else
                                                {{ config('settings.currency_symbol') }}
                                            @endif
                                        </span>
                                    </div>

                                </div>
                                @error('discount_value')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror

                            </div>



                            <div class="form-group col-md-12">
                                <label for="description">وصف الكوبون</label>
                                <textarea class="form-control text-right @error('description') is-invalid @enderror" wire:model="description"
                                    id="description" rows="2" placeholder="...وصف مختصر عن الكوبون"></textarea>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>

                        <!-- الفترة الزمنية -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">الفترة الزمنية</h5>
                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="end_date">تاريخ النهاية <span class="text-danger">*</span></label>
                                <input type="datetime-local" wire:model="end_date"
                                    class="form-control text-right @error('end_date') is-invalid @enderror "
                                    id="end_date">
                                @error('end_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            @error('end_date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror

                            <div class="form-group col-md-6">
                                <label for="start_date">تاريخ البداية <span class="text-danger">*</span></label>
                                <input type="datetime-local" wire:model="start_date"
                                    class="form-control text-right @error('start_date') is-invalid @enderror "
                                    id="start_date">
                                @error('start_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- الاستخدام -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">الاستخدام</h5>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="max_uses">الحد الأقصى للاستخدام <span class="text-danger">*</span></label>
                                <input type="number" wire:model="max_uses"
                                    class="form-control text-right @error('max_uses') is-invalid @enderror "
                                    id="max_uses">
                                @error('max_uses')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- التطبيق -->
                        <h5 class="border-bottom pb-2 mb-3 mt-4">تطبيق الكوبون على</h5>
                        <div class="form-group">
                            <select wire:model.live="applies_to"
                                class="form-control text-right @error('applies_to') is-invalid @enderror ">
                                <option value="all_establishments">جميع المنشآت</option>
                                <option value="specific_establishments">منشآت محددة</option>
                                <option value="specific_types">أنواع محددة من المنشآت</option>
                            </select>
                             @error('applies_to')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                        </div>

                        <!-- منشآت محددة -->
                        @if ($applies_to == 'specific_establishments')
                            <div class="form-group mt-3">
                                <label>اختر المنشآت:</label>
                                <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    @foreach ($this->establishments as $establishment)
                                        <div class="form-check">
                                            <input type="checkbox" wire:model="selectedEstablishments"
                                                value="{{ $establishment->id }}" id="est_{{ $establishment->id }}"
                                                class="form-check-input">
                                            <label for="est_{{ $establishment->id }}"
                                                class="form-check-label">{{ $establishment->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('selectedEstablishments')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <!-- أنواع منشآت -->
                        @if ($applies_to == 'specific_types')
                            <div class="form-group mt-3">
                                <label>اختر أنواع المنشآت:</label>
                                <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    @foreach ($this->establishmentTypes as $type)
                                        <div class="form-check">
                                            <input type="checkbox" wire:model="selectedTypes"
                                                value="{{ $type->id }}" id="type_{{ $type->id }}"
                                                class="form-check-input">
                                            <label for="type_{{ $type->id }}"
                                                class="form-check-label">{{ $type->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('selectedTypes')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <div class="row">

                            <div class="col-md-6 mt-4">
                                <button type="button" wire:click="cancel"
                                    class="btn btn-outline-secondary btn-lg w-100 rounded-pill shadow-sm py-3">
                                    إلغاء <i class="fas fa-times ml-2"></i>

                                </button>
                            </div>


                            <!-- أزرار -->
                            <div class="col-md-6 mt-4">
                                <button type="submit"
                                    class="btn btn-{{ $isEdit ? 'warning' : 'primary' }} btn-lg w-100 rounded-pill shadow-sm py-3">
                                    {{ $isEdit ? 'حفظ التعديلات' : 'إضافة كود خصم' }}
                                    <i class="fas {{ $isEdit ? 'fa-save' : 'fa-plus-circle' }} ml-2"></i>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        @endif
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                    <input type="text" wire:model.debounce.300ms.live="search"
                        class="form-control  border-0 text-right py-2" placeholder="..ابحث باسم الكوبون">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                    <select wire:model.live="selectedStatu" class="form-control  border-0 text-right py-2">
                        <option value="">كل الحالات</option>
                        <option value="1">المفعلة</option>
                        <option value="0">غير المفعلة</option>
                    </select>
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-filter text-primary"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- عرض الكوبونات -->
        <div class="row">
            @forelse ($coupons as $coupon)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-flex align-items-stretch ">
                    <div class="card shadow-sm w-100 text-right position-relative border-0">
                        <div class="card-header p-0"
                            style="height: 160px; overflow: hidden; background: linear-gradient(135deg, #3b82f6, #0d47a1);">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-ticket-alt fa-3x text-white opacity-75"></i>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="row mb-2">
                                <div class="col-12 d-flex justify-content-end align-items-center">
                                    <h5 class="mb-0 text-primary">
                                        {{ $coupon->code }}
                                    </h5>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-12">
                                    <p class="text-muted mb-0 line-clamp-2"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $coupon->description }}
                                    </p>
                                </div>
                            </div>

                            <div class="row mt-3 g-2">
                                <div class="col-6">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="text-end me-2">
                                            <div class="fw-semibold small text-dark">
                                                نوع الخصم
                                                <i class="fas fa-percent text-success ms-2"></i>
                                            </div>
                                            <div class="text-muted extra-small">
                                                {{ $coupon->discount_type == 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="text-end me-2">
                                            <div class="fw-semibold small text-dark">
                                                قيمة الخصم
                                                <i class="fas fa-tag text-primary ms-2"></i>
                                            </div>
                                            <div class="text-muted extra-small">
                                                {{ $coupon->discount_value }}
                                                {{ $coupon->discount_type == 'percentage' ? '%' : config('settings.currency_symbol') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="text-end me-2">
                                            <div class="fw-semibold small text-dark">
                                                الاستخدام
                                                <i class="fas fa-sync-alt text-warning ms-2"></i>
                                            </div>
                                            <div class="text-muted extra-small">
                                                {{ $coupon->current_uses }} / {{ $coupon->max_uses }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="text-end me-2">
                                            <div class="fw-semibold small text-dark">
                                                الصلاحية
                                                <i class="fas fa-calendar-alt text-info ms-2"></i>
                                            </div>
                                            <div class="text-muted extra-small">
                                                {{ \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d') }} -
                                                {{ \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span wire:click.prevent="toggleVerification({{ $coupon->id }})"
                                class="feature-badge position-absolute"
                                style="top: 10px; left: 10px; font-size: 0.9rem; cursor: pointer; z-index: 10; background: {{ $coupon->is_active ? '#4CAF50' : '#F44336' }}!important; color: white !important;">
                                {{ $coupon->is_active ? 'موثقة' : 'غير موثقة' }}
                            </span>
                        </div>



                        {{-- أزرار التحكم --}}
                        <div class="card-footer bg-transparent pt-1 px-0 mx-2">
                            <div class="d-flex justify-content-between align-items-center gap-3 mx-1">

                                <!-- زر الحذف -->
                                <button
                                    class="btn btn-outline-danger rounded-pill flex-grow-1 d-flex align-items-center justify-content-center mx-1"
                                    wire:click.prevent="confirmDelete({{ $coupon->id }})" data-bs-toggle="tooltip"
                                    title="حذف الكوبون">
                                    <span class="d-none d-sm-inline-block">حذف</span>
                                    <i class="fas fa-trash-alt ml-2"></i>
                                </button>

                                <!-- زر التعديل -->
                                <button
                                    class="btn btn-outline-primary btn-sm rounded-pill flex-grow-1 py-2 d-flex align-items-center justify-content-center"
                                    wire:click.prevent="edit({{ $coupon->id }})" data-bs-toggle="tooltip"
                                    title="تعديل الكوبون">
                                    <span class="d-none d-sm-inline-block">تعديل</span>
                                    <i class="fas fa-edit ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">لا توجد كوبونات خصم حاليًا</div>
                </div>
            @endforelse
        </div>

        <!-- تأكيد الحذف -->
        @if ($deleteId)
            <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow">
                        <div class="modal-header bg-danger text-white">
                            <button type="button" wire:click="$set('deleteId', null)"
                                class="btn-close btn-close-white"></button>
                            <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> تأكيد الحذف</h5>

                        </div>
                        <div class="modal-body text-right">
                            <p>هل أنت متأكد أنك تريد حذف الكوبون التالي؟</p>
                            <p class="fw-bold text-danger">"{{ $deleteTitle }}"</p>
                        </div>
                        <div class="modal-footer">
                            <button wire:click="$set('deleteId', null)"
                                class="btn btn-secondary rounded-pill px-4">إلغاء</button>
                            <button wire:click="deleteCoupon" class="btn btn-danger rounded-pill px-4">نعم،
                                احذف</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- SweetAlert2 Script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.addEventListener('show-toast', event => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: event.detail.type,
                title: event.detail.message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#f8f9fa',
                color: '#212529',
                iconColor: event.detail.type === 'success' ? '#28a745' : '#dc3545'
            });
        });
    </script>
    <style>
        .hover-shadow {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
</div>
</div>
</div>
