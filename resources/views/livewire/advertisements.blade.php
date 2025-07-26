<div>
    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show text-right" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container py-4">
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary">
                 إدارة الإعلانات
            </h3>
            @if (!$showForm && !$isEdit)
                <button wire:click="create" class="btn btn-primary add-btn">
                    <i class="fas fa-plus-circle ml-2"></i> إضافة إعلان جديد
                </button>
            @endif
        </div>

        {{-- Form Section --}}
        @if ($showForm || $isEdit)
            <div class="card mb-4 border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-right">
                        <i class="fas fa-edit ml-2"></i>
                        {{ $isEdit ? 'تعديل الإعلان' : 'إضافة إعلان جديد' }}
                    </h5>
                </div>
                
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" enctype="multipart/form-data">
                        <div class="row g-4">
                            {{-- Title Field --}}
                            <div class="col-12">
                                <label class="form-label text-primary fw-bold d-block text-right mb-2">
                                     عنوان الإعلان <i class="fas fa-heading ml-2"></i><span class="text-danger">*</span>
                                </label>
                                <input type="text" wire:model.defer="title"
                                    class="form-control form-control-lg text-right py-3 rounded-3 @error('title') is-invalid @enderror"
                                    placeholder="أدخل عنوان الإعلان هنا...">
                                @error('title')
                                    <div class="invalid-feedback text-right d-block mt-2">
                                        {{ $message }}<i class="fas fa-exclamation-circle me-1"></i>
                                    </div>
                                @enderror
                            </div>

                            {{-- Image Field --}}
                            <div class="col-12">
                                <label class="form-label text-primary fw-bold d-block text-right mb-2">
                                     صورة الإعلان <i class="fas fa-image ml-2"></i>
                                    <span class="text-danger">{{ $isEdit ? '(اختياري)' : '*' }}</span>
                                </label>
                                
                                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                                    <label class="input-group-text btn btn-primary text-white border-0" style="cursor: pointer;">
                                        <i class="fas fa-cloud-upload-alt ml-2"></i> اختر صورة
                                        <input type="file" wire:model="image" accept="image/*" class="d-none">
                                    </label>
                                    <span class="form-control text-right bg-light text-truncate">
                                        @if ($image) {{ $image->getClientOriginalName() }}
                                        @elseif($imagePreview) تم اختيار صورة مسبقاً
                                        @else لم يتم اختيار صورة
                                        @endif
                                    </span>
                                </div>
                                
                                @error('image')
                                    <div class="text-danger small text-right mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                
                                {{-- Image Preview --}}
                                <div class="mt-3 text-center">
                                    @if ($image)
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ $image->temporaryUrl() }}" 
                                                 class="rounded-3 shadow border border-3 border-primary"
                                                 style="max-height: 200px; max-width: 100%; object-fit: contain;">
                                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">معاينة</span>
                                        </div>
                                    @elseif ($imagePreview)
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('storage/' . $imagePreview) }}" 
                                                 class="rounded-3 shadow border border-3 border-secondary"
                                                 style="max-height: 200px; max-width: 100%; object-fit: contain;">
                                            <span class="badge bg-secondary position-absolute top-0 start-0 m-2">الصورة الحالية</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Description Field --}}
                            <div class="col-12">
                                <label class="form-label text-primary fw-bold d-block text-right mb-2">
                                    وصف الإعلان <i class="fas fa-align-left ml-2"></i> <span class="text-danger">*</span>
                                </label>
                                <textarea wire:model.defer="description" rows="5"
                                    class="form-control form-control-lg text-right py-3 rounded-3 @error('description') is-invalid @enderror"
                                    placeholder="أدخل وصف الإعلان هنا..."></textarea>
                                @error('description')
                                    <div class="invalid-feedback text-right d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Dates Section --}}
                            <div class="col-md-6">
                                <label class="form-label text-primary fw-bold d-block text-right mb-2">
                                     تاريخ البدء <i class="fas fa-calendar-plus ml-2"></i><span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg shadow-sm rounded-3">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </span>
                                    <input type="datetime-local" wire:model.defer="start_date"
                                        class="form-control border-0 text-right py-3 @error('start_date') is-invalid @enderror">
                                </div>
                                @error('start_date')
                                    <div class="invalid-feedback text-right d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-primary fw-bold d-block text-right mb-2">
                                     تاريخ الانتهاء <i class="fas fa-calendar-times ml-2"></i><span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg shadow-sm rounded-3">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </span>
                                    <input type="datetime-local" wire:model.defer="end_date"
                                        class="form-control border-0 text-right py-3 @error('end_date') is-invalid @enderror">
                                </div>
                                @error('end_date')
                                    <div class="invalid-feedback text-right d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Action Buttons --}}
                            <div class="col-md-6 mt-4">
                                <button type="button" wire:click="cancel"
                                    class="btn btn-outline-secondary btn-lg w-100 rounded-pill shadow-sm py-3">
                                    <i class="fas fa-times ml-2"></i> إلغاء
                                </button>
                            </div>

                            <div class="col-md-6 mt-4">
                                <button type="submit"
                                    class="btn btn-{{ $isEdit ? 'warning' : 'primary' }} btn-lg w-100 rounded-pill shadow-sm py-3">
                                    <i class="fas {{ $isEdit ? 'fa-save' : 'fa-plus-circle' }} ml-2"></i>
                                    {{ $isEdit ? 'حفظ التعديلات' : 'إضافة الإعلان' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Search and Filter Section --}}
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                    <input type="text" wire:model.debounce.300ms.live="search"
                        class="form-control border-0 text-right py-2"
                        placeholder="ابحث باسم الإعلان...">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                    <select wire:model.live="selectedStatu" class="form-control border-0 text-right py-2">
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

        {{-- Advertisements Grid --}}
        <div class="row g-4">
            @forelse ($advertisements as $ad)
                <div class="col-md-6 col-lg-4" wire:key="ad-{{ $ad->id }}">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                        {{-- Advertisement Image --}}
                        <div class="position-relative">
                            @if ($ad->image)
                                <img src="{{ asset('storage/' . $ad->image) }}" 
                                     class="card-img-top" 
                                     alt="صورة الإعلان"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            {{-- Active Status Badge --}}
                            <span wire:click="toggleVerification({{ $ad->id }})"
                                class="position-absolute top-0 end-0 m-2 badge rounded-pill {{ $ad->is_active ? 'bg-success' : 'bg-danger' }}"
                                style="cursor: pointer; font-size: 0.85rem;">
                                {{ $ad->is_active ? 'مفعل' : 'غير مفعل' }}
                            </span>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            {{-- Title --}}
                            <h5 class="card-title fw-bold text-right mb-3 text-truncate" title="{{ $ad->title }}">
                                {{ $ad->title }}
                            </h5>
                            
                            {{-- Description --}}
                            <p class="card-text text-muted text-right mb-4" 
                               style="max-height: 4.5em; overflow: hidden; line-height: 1.6;"
                               title="{{ $ad->description }}">
                                {{ $ad->description }}
                            </p>
                            
                            {{-- Dates --}}
                            <div class="d-flex justify-content-between small text-muted mt-auto mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="far fa-calendar-alt text-primary mr-2"></i>
                                    <div>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($ad->start_date)->format('Y-m-d') }}</div>
                                        <div class="extra-small">تاريخ البدء</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center">
                                    <i class="far fa-calendar-check text-primary mr-2"></i>
                                    <div>
                                        <div class="fw-bold">
                                            {{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('Y-m-d') : '—' }}
                                        </div>
                                        <div class="extra-small">تاريخ النهاية</div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="d-flex justify-content-between mt-auto">
                                <button class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                        wire:click="edit({{ $ad->id }})">
                                    <i class="fas fa-edit me-1"></i> تعديل
                                </button>
                                <button class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                        wire:click="confirmDelete({{ $ad->id }})">
                                    <i class="fas fa-trash me-1"></i> حذف
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-ad fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">لا توجد إعلانات لعرضها</h4>
                            <p class="text-muted mt-2">يمكنك إضافة إعلان جديد باستخدام زر "إضافة إعلان"</p>
                        </div>
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
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle ml-2"></i> تأكيد الحذف
                            </h5>
                            <button type="button" class="btn-close btn-close-white" wire:click="$set('deleteId', null)"></button>
                        </div>
                        <div class="modal-body text-right">
                            <p>هل أنت متأكد أنك تريد حذف الإعلان التالي؟</p>
                            <p class="fw-bold text-danger">"{{ $deleteTitle }}"</p>
                            <p class="small text-muted">ملاحظة: لا يمكن استعادة الإعلان بعد الحذف</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary rounded-pill px-4"
                                wire:click="$set('deleteId', null)">
                                <i class="fas fa-times me-1"></i> إلغاء
                            </button>
                            <button type="button" class="btn btn-danger rounded-pill px-4"
                                wire:click="deleteAdvertisement">
                                <i class="fas fa-trash me-1"></i> نعم، احذف
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
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
</div>

