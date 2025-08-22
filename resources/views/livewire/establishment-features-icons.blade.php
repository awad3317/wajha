<div>
    @if (session()->has('success'))
        <div class="alert alert-success text-right">{{ session('success') }}</div>
    @endif
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center my-2">
       
            <h3 class="fw-bold text-primary mb-2 mb-sm-0 text-center text-sm-start d-none d-sm-block">إدارة ايقونات المميزات</h3>
                 @if (!$showForm && !$isEdit)
                <button wire:click="create" class="btn btn-primary add-btn text-left">إضافة الأيقونة     <i class="fas fa-plus ms-1"></i></button>
            @endif
        </div>
        @if ($showForm || $isEdit)
            <div class="card mb-4 border-0 shadow-lg rounded-3">
                <div class="card-header bg-primary text-white py-3 rounded-top-3">
                    <h5 class="mb-0 text-center">
                        <i class="fas fa-plus ms-1"></i>
                         {{ $isEdit ? 'تعديل الأيقونة' : 'إضافة أيقونة جديدة' }}
                    </h5>
                </div>

                <div class="card-body py-4">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="row g-4">
                            <!-- Icon Upload Field -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label text-primary fw-bold d-block text-right mb-3">
                                        <i class="fas fa-upload me-2"></i>
                                        رفع الأيقونة
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group input-group-lg shadow-sm rounded-2 overflow-hidden">
                                        <label class="input-group-text btn btn-primary text-white border-0 py-3"
                                            style="cursor: pointer;">
                                            <i class="fas fa-cloud-upload-alt me-2"></i> اختر ملف
                                            <input type="file" wire:model="iconFile" class="d-none" accept="image/*">
                                        </label>
                                        <span class="form-control text-right bg-light py-3">
                                            @if ($iconFile)
                                                {{ $iconFile->getClientOriginalName() }}
                                            @elseif($icon)
                                                تم اختيار ملف مسبقاً
                                            @else
                                                لم يتم اختيار ملف
                                            @endif
                                        </span>
                                    </div>

                                    @error('iconFile')
                                        <div class="text-danger small text-right mt-3">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror

                                    <div class="mt-4 text-center">
                                        @if ($iconFile)
                                            <img src="{{ $iconFile->temporaryUrl() }}" alt="معاينة" width="80"
                                                height="80"
                                                class="rounded-circle  border-3 border-primary shadow-sm">
                                        @elseif ($icon)
                                            <img src="{{ url($icon) }}" alt="الأيقونة الحالية" width="80"
                                                height="80"
                                                class="rounded-circle  border-3 border-primary shadow-sm">
                                        @else
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light"
                                                style="width: 80px; height: 80px; border: 2px dashed #dee2e6;">
                                                <i class="fas fa-image text-muted fa-lg"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 mt-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <button type="button" wire:click="cancel"
                                            class="btn btn-outline-secondary btn-lg w-100 rounded-pill shadow-sm py-3 mb-2">
                                            إلغاء<i class="fas fa-times ml-2"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit"
                                            class="btn btn-{{ $isEdit ? 'warning' : 'primary' }} btn-lg w-100 rounded-pill shadow-sm py-3">

                                            {{ $isEdit ? 'حفظ التعديلات' : 'إضافة أيقونة' }}
                                            <i class="fas {{ $isEdit ? 'fa-save' : 'fa-plus-circle' }} ml-2"></i>
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
            <!-- Card Header -->
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 text-right">
                    قائمة الباقات<i class="fas fa-boxes ml-2"></i>
                </h5>
            </div>

            <div class="card-body p-0" dir="rtl">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-primary">
                            <tr>
                                <th class="text-center py-3 fw-bold" width="80">#</th>
                                <th class="text-center py-3 fw-bold">أيقونة الباقة</th>
                                <th class="text-center py-3 fw-bold" width="200">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($packages as $package)
                                <tr class="border-bottom hover-highlight">
                                    <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>

                                    <td class="text-center">
                                        @if ($package->icon)
                                            <div class="d-flex justify-content-center">
                                                <img src="{{ url($package->icon) }}" alt="أيقونة الباقة"
                                                    class="rounded  border-3 border-primary p-1" width="100"
                                                    height="100"
                                                    style="object-fit: cover; background-color: #f8f9fa;">
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-light"
                                                    style="width: 70px; height: 70px; border: 2px dashed #dee2e6;">
                                                    <i class="fas fa-box-open text-muted fa-lg"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-3">
                                            <button class="btn btn-icon btn-sm btn-info rounded-circle shadow-sm mx-1"
                                                wire:click="edit({{ $package->id }})" data-bs-toggle="tooltip"
                                                title="تعديل الباقة">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-icon btn-sm btn-danger rounded-circle shadow-sm"
                                                wire:click="confirmDelete({{ $package->id }})" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-bs-toggle="tooltip"
                                                title="حذف الباقة">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">لا توجد باقات مسجلة</h5>
                                            <p class="text-muted small mt-2">يمكنك إضافة باقة جديدة باستخدام زر الإضافة
                                            </p>
                                        </div>
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
                            هل أنت متأكد من حذف الايقونة: <strong class="text-danger">{{ $deleteId }}</strong> ؟
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="resetDelete">إلغاء</button>
                            <button type="button" class="btn btn-danger" wire:click="deletepackage">نعم،
                                حذف</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
