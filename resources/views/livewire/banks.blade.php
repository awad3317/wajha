<div>
    @if (session()->has('success'))
        <div class="alert alert-success text-right">{{ session('success') }}</div>
    @endif
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center my-2">
            @if (!$showForm && !$isEdit)
                <button wire:click="create" class="btn btn-primary add-btn text-left">إضافة بنك</button>
            @endif
            <h3 class="text-left">إدارة البنوك</h3>
        </div>
        @if ($showForm || $isEdit)
       <div class="card mb-4 border-0 shadow-lg rounded-3">
    <div class="card-header bg-primary text-white py-3 rounded-top-3">
        <h5 class="mb-0 text-center">
            <i class="fas fa-university me-2"></i>
            {{ $isEdit ? 'تعديل بيانات البنك' : 'إضافة بنك جديد' }}
        </h5>
    </div>

    <div class="card-body py-4">
        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
            <div class="row g-4">
                {{-- حقل رفع الأيقونة --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label text-primary fw-bold d-block text-right mb-2">
                            <i class="fas fa-image me-2"></i>
                            أيقونة البنك
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group input-group-lg shadow-sm rounded-2 overflow-hidden">
                            <label class="input-group-text btn btn-primary text-white border-0"
                                style="cursor: pointer;">
                                <i class="fas fa-cloud-upload-alt me-2"></i> رفع
                                <input type="file" wire:model="iconFile" class="d-none" accept="image/*">
                            </label>
                            <span class="form-control text-right bg-light text-truncate">
                                @if ($iconFile)
                                    {{ $iconFile->getClientOriginalName() }}
                                @elseif($icon)
                                    الأيقونة الحالية
                                @else
                                    لم يتم اختيار ملف
                                @endif
                            </span>
                        </div>

                        @error('iconFile')
                            <div class="text-danger small text-right mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <div class="mt-3 text-center">
                            @if ($iconFile)
                                <img src="{{ $iconFile->temporaryUrl() }}" alt="معاينة" width="80" height="80"
                                    class="rounded-circle  border-3 border-primary shadow" style="object-fit: cover;">
                            @elseif ($icon)
                                <img src="{{ url($icon) }}" alt="الأيقونة الحالية" width="80" height="80"
                                    class="rounded-circle  border-3 border-primary shadow" style="object-fit: cover;">
                            @else
                                {{-- <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light" 
                                     style="width: 80px; height: 80px; border: 2px dashed #dee2e6;">
                                    <i class="fas fa-building text-muted fa-2x"></i>
                                </div> --}}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- حقل اسم البنك --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label text-primary fw-bold d-block text-right mb-2">
                            <i class="fas fa-signature me-2"></i>
                            اسم البنك
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group input-group-lg shadow-sm rounded-2 overflow-hidden">
                            <input type="text" wire:model.defer="name"
                                class="form-control border-0 text-right py-3 @error('name') is-invalid @enderror"
                                placeholder="أدخل اسم البنك هنا...">
                            <span class="input-group-text bg-white border-0">
                                <i class="fas fa-university text-primary"></i>
                            </span>
                        </div>

                        @error('name')
                            <div class="invalid-feedback text-right d-block mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- حقل البحث --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label text-primary fw-bold d-block text-right mb-2">
                            <i class="fas fa-search me-2"></i>
                            بحث سريع
                        </label>

                        <div class="input-group input-group-lg shadow-sm rounded-2 overflow-hidden">
                            <input type="text" wire:model.debounce.300ms.live="search"
                                class="form-control border-0 text-right py-3"
                                placeholder="ابحث باسم البنك...">
                            <span class="input-group-text bg-white border-0">
                                <i class="fas fa-search text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- أزرار الإجراءات --}}
                <div class="col-md-12 mt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <button type="button" wire:click="cancel"
                                class="btn btn-outline-secondary btn-lg w-100 rounded-pill shadow-sm py-3">
                                <i class="fas fa-times me-2"></i> إلغاء
                            </button>
                        </div>
                        
                        <div class="col-md-6">
                            <button type="submit"
                                class="btn btn-{{ $isEdit ? 'warning' : 'primary' }} btn-lg w-100 rounded-pill shadow-sm py-3">
                                <i class="fas {{ $isEdit ? 'fa-save' : 'fa-plus-circle' }} me-2"></i>
                                {{ $isEdit ? 'حفظ التعديلات' : 'إضافة بنك' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


        @endif
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 text-center">
                    <i class="fas fa-university me-2"></i>
                    قائمة البنوك
                </h5>
            </div>

            <div class="card-body p-0" dir="rtl">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-primary">
                            <tr>
                                <th class="text-center py-3 fw-bold">#</th>
                                <th class="text-center py-3 fw-bold">الشعار</th>
                                <th class="text-start py-3 fw-bold">اسم البنك</th>
                                <th class="text-center py-3 fw-bold">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($banks as $bank)
                                <tr class="border-bottom">
                                    <td class="text-center text-muted">{{ $loop->iteration }}</td>

                                    <td class="text-center">
                                        @if ($bank->icon)
                                            <img src="{{ url($bank->icon) }}" alt="Bank Logo"
                                                class="rounded  border-3 border-primary p-1" width="100"
                                                height="100" style="object-fit: contain; background: #f8f9fa;">
                                        @else
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light"
                                                style="width: 100px; height: 100px;">
                                                <i class="fas fa-university text-muted fa-lg"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-start">
                                        <span class="fw-bold">{{ $bank->name }}</span>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex  gap-2" style="margin: 0px 35%">
                                            <button class="btn btn-icon btn-sm btn-info mx-2 rounded-circle shadow-sm"
                                                wire:click="edit({{ $bank->id }})" data-bs-toggle="tooltip"
                                                title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-icon btn-sm btn-danger rounded-circle shadow-sm"
                                                wire:click="confirmDelete({{ $bank->id }})"
                                                data-bs-toggle="tooltip" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-university fa-2x mb-3"></i>
                                        <p class="mb-0 fs-5">لا توجد بنوك مسجلة</p>
                                        <p class="text-muted small mt-2">انقر على زر "إضافة بنك" لبدء التسجيل</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @if ($deleteId)
            <div class="modal fade show d-block text-right" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <button type="button" class="btn btn-close" wire:click="resetDelete">X</button>
                            <h5 class="modal-title">تأكيد الحذف</h5>
                        </div>
                        <div class="modal-body">
                            هل أنت متأكد من حذف البنك: <strong class="text-danger">{{ $deleteName }}</strong> ؟
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="resetDelete">إلغاء</button>
                            <button type="button" class="btn btn-danger" wire:click="deleteBank">نعم، حذف</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
