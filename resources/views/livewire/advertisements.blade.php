<div>
    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show " role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container py-4">
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
            <!-- العنوان -->


            @if (!$showForm && !$isEdit)
                <button wire:click="create" class="btn btn-primary add-btn py-2 px-3 px-sm-4 rounded-pill flex-shrink-0">
                    <i class="fas fa-plus-circle ms-1 ms-sm-2"></i> <span class="d-none d-inline">إضافة إعلان
                        جديد</span>
                </button>
            @endif
            <h3 class="fw-bold text-primary mb-2 mb-sm-0 text-center text-sm-start d-none d-sm-block">
                إدارة الإعلانات
            </h3>
        </div>

        {{-- Form Section --}}
        @if ($showForm || $isEdit)
            <div class="card mb-4 border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 ">
                        <i class="fas fa-edit ml-2"></i>
                        {{ $isEdit ? 'تعديل الإعلان' : 'إضافة إعلان جديد' }}
                    </h5>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" enctype="multipart/form-data">
                        <div class="row g-4">
                            {{-- Title Field --}}
                            <div class="col-12">
                                <label class="form-label text-primary fw-bold d-block  mb-2">
                                    <span class="text-danger">*</span> <i class="fas fa-heading mr-2"></i> عنوان الإعلان
                                </label>
                                <input type="text" wire:model.defer="title"
                                    class="form-control form-control-lg  py-3 rounded-3 @error('title') is-invalid @enderror"
                                    placeholder="...أدخل عنوان الإعلان هنا">
                                @error('title')
                                    <div class="invalid-feedback  d-block mt-2">
                                        {{ $message }}<i class="fas fa-exclamation-circle ml-1"></i>
                                    </div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label text-primary fw-bold d-block mb-2">
                                    @unless ($isEdit)
                                        <span class="text-danger">*</span>
                                    @endunless
                                    <i class="fas fa-image mr-2"></i> صورة الإعلان
                                </label>

                                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                    <label class="input-group-text btn btn-primary text-white border-0"
                                        style="cursor: pointer;">
                                        <i class="fas fa-cloud-upload-alt ml-2"></i> اختر صورة
                                        <input type="file" wire:model="image" accept="image/*" class="d-none">
                                    </label>
                                    <span class="form-control bg-light text-truncate">
                                        @if ($image)
                                            {{ $image->getClientOriginalName() }}
                                        @elseif($imagePreview)
                                            تم اختيار صورة مسبقاً
                                        @else
                                            لم يتم اختيار صورة
                                        @endif
                                    </span>
                                </div>

                                @error('image')
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-exclamation-circle ml-1"></i>{{ $message }}
                                    </div>
                                @enderror

                                {{-- Image Preview --}}
                                <div class="mt-3 text-center">
                                    @if ($image)
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ $image->temporaryUrl() }}"
                                                class="rounded-3 shadow border-3 border-primary"
                                                style="max-height: 200px; max-width: 100%; object-fit: contain;">
                                            <span
                                                class="badge bg-primary position-absolute top-0 start-0 m-2">معاينة</span>
                                        </div>
                                    @elseif ($imagePreview)
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('storage/establishment-image/' . $imagePreview) }}"
                                                class="rounded-3 shadow border-3 border-secondary"
                                                style="max-height: 200px; max-width: 100%; object-fit: contain;">
                                            <span class="badge bg-secondary position-absolute top-0 start-0 m-2">الصورة
                                                الحالية</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Description Field --}}
                            <div class="col-12">
                                <label class="form-label text-primary fw-bold d-block  mb-2">
                                    <span class="text-danger">*</span> <i class="fas fa-align-left mr-2"></i> وصف
                                    الإعلان
                                </label>
                                <textarea wire:model.defer="description" rows="5"
                                    class="form-control form-control-lg  py-3 rounded-3 @error('description') is-invalid @enderror"
                                    placeholder="أدخل وصف الإعلان هنا"></textarea>
                                @error('description')
                                    <div class="invalid-feedback  d-block mt-2">
                                    </div>
                                @enderror
                            </div>

                            {{-- Dates Section --}}
                            <div class="col-md-6">
                                <label class="form-label text-primary fw-bold d-block  mb-2">
                                    <span class="text-danger">*</span>
                                    <i class="fas fa-calendar-plus mr-2"></i> تاريخ البدء
                                </label>
                                <div class="input-group input-group-lg shadow-sm rounded-3">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </span>
                                    <input type="datetime-local" wire:model.defer="start_date"
                                        class="form-control border-0  py-3 @error('start_date') is-invalid @enderror">
                                </div>
                                @error('start_date')
                                    <div class="invalid-feedback  d-block mt-2">
                                        {{ $message }}<i class="fas fa-exclamation-circle mr-1"></i>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-primary fw-bold d-block  mb-2">
                                    <span class="text-danger">*</span>
                                    <i class="fas fa-calendar-times mr-2"></i> تاريخ الانتهاء

                                </label>
                                <div class="input-group input-group-lg shadow-sm rounded-3">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </span>
                                    <input type="datetime-local" wire:model.defer="end_date"
                                        class="form-control border-0  py-3 @error('end_date') is-invalid @enderror">
                                </div>
                                @error('end_date')
                                    <div class="invalid-feedback  d-block mt-2">
                                        {{ $message }}<i class="fas fa-exclamation-circle mr-1"></i>
                                    </div>
                                @enderror
                            </div>


                            <div class="col-12 mt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <button type="button" wire:click="cancel"
                                            class="btn btn-outline-secondary btn-lg w-100 rounded-pill shadow-sm py-3 mb-2">
                                            <i class="fas fa-times mr-2"></i> إلغاء
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <button type="submit"
                                            class="btn btn-{{ $isEdit ? 'warning' : 'primary' }} btn-lg w-100 rounded-pill shadow-sm py-3">
                                            <i class="fas {{ $isEdit ? 'fa-save' : 'fa-plus-circle' }} mr-2"></i>
                                            {{ $isEdit ? 'حفظ التعديلات' : 'إضافة عملة' }}

                                        </button>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </form>

                </div>
            </div>
        @endif

        {{-- Search and Filter Section --}}
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                    <input type="text" wire:model.debounce.300ms.live="search" class="form-control border-0  py-2"
                        placeholder="..ابحث باسم الإعلان">

                </div>
            </div>

            <div class="col-md-4">
                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-filter text-primary"></i>
                    </span>
                    <select wire:model.live="selectedStatu" class="form-control border-0  py-2">
                        <option value="">كل الحالات</option>
                        <option value="1">المفعلة</option>
                        <option value="0">غير المفعلة</option>
                    </select>

                </div>
            </div>
        </div>

        {{-- Advertisements Grid --}}
        <div class="row">
            @forelse ($advertisements as $ad)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                    <div class="card   shadow-sm w-100  position-relative border-0">
                        <div class="text-decoration-none text-dark">
                            <div class="card-header p-0" style="height: 180px; overflow: hidden;">
                                @if ($ad->image)
                                    <img src="{{ url($ad->image) }}" alt="ad image"
                                        class="object-fit-cover w-100 h-100" alt="{{ $ad->title }}">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="fas fa-ad fa-3x text-secondary opacity-25"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="row mb-2">
                                    <div class="col-12 d-flex justify-content-start align-items-center">
                                        <h5 class="mb-0 text-primary">
                                            {{ $ad->title }} </i>
                                        </h5>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p class="text-muted mb-0 line-clamp-2"
                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ $ad->description }}
                                        </p>
                                    </div>
                                </div>

                                <div class="row mt-3 g-2">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <div class="text-start me-2">
                                                <div class="fw-semibold small text-dark">
                                                    <i class="fas fa-calendar-check text-success mr-1"></i>
                                                    تاريخ النهاية
                                                </div>
                                                <div class="text-muted extra-small ">
                                                    {{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('Y-m-d') : 'لا نهاية' }}

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center justify-content-start">
                                            <div class="text-start me-2">
                                                <div class="fw-semibold small text-dark">
                                                    <i class="fas fa-calendar-day text-primary mr-1"></i> تاريخ البداية


                                                </div>
                                                <div class="text-muted extra-small">
                                                    {{ \Carbon\Carbon::parse($ad->start_date)->format('Y-m-d') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        {{-- حالة التفعيل --}}
                        <span wire:click.prevent="toggleVerification({{ $ad->id }})"
                            class="position-absolute badge rounded-pill py-2 px-3 d-flex align-items-center shadow-sm"
                            style="top: 10px; left: 10px; cursor: pointer; z-index: 10; background: {{ $ad->is_active ? '#4CAF50' : '#F44336' }}; color: white;">
                            <span>{{ $ad->is_active ? 'مفعل' : 'غير مفعل' }}</span>
                        </span>

                        {{-- أزرار التحكم --}}
                        <div class="card-footer bg-transparent  pt-1 px-0 mx-2">
                            <div class="d-flex justify-content-between align-items-center gap-3 mx-1">


                                <!-- زر الحذف مع تحسينات -->
                                <button
                                    class="btn btn-outline-danger  rounded-pill flex-grow-1  d-flex align-items-center justify-content-center mx-1"
                                    wire:click.prevent="confirmDelete({{ $ad->id }})" data-bs-toggle="tooltip"
                                    title="حذف الإعلان">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    <span class="d-none d-sm-inline-block">حذف</span>
                                </button>
                                <!-- زر التعديل مع تحسينات -->
                                <button
                                    class="btn btn-outline-primary btn-sm rounded-pill flex-grow-1 py-2 d-flex align-items-center justify-content-center"
                                    wire:click.prevent="edit({{ $ad->id }})" data-bs-toggle="tooltip"
                                    title="تعديل الإعلان">
                                    <i class="fas fa-edit mr-2"></i>
                                    <span class="d-none d-sm-inline-block">تعديل</span>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center shadow-sm rounded">
                        <i class="fas fa-ad fa-lg me-2"></i> لا توجد إعلانات متاحة حالياً
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Delete Confirmation Modal --}}
        @if ($deleteId)
            <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-danger text-white">
                            <button type="button" class="btn-close btn-light rounded-pill"
                                wire:click="$set('deleteId', null)">X</button>
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle ml-2"></i> تأكيد الحذف
                            </h5>

                        </div>
                        <div class="modal-body ">
                            <p>هل أنت متأكد أنك تريد حذف الإعلان التالي؟</p>
                            <p class="fw-bold text-danger">"{{ $deleteTitle }}"</p>
                            <p class="small text-muted">ملاحظة: لا يمكن استعادة الإعلان بعد الحذف</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary rounded-pill px-4"
                                wire:click="$set('deleteId', null)">
                                <i class="fas fa-times mr-1"></i> إلغاء
                            </button>
                            <button type="button" class="btn btn-danger rounded-pill px-4"
                                wire:click="deleteAdvertisement">
                                <i class="fas fa-trash mr-1"></i> نعم، احذف
                            </button>
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
