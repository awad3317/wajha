<div>
    @if (session()->has('success'))
        <div class="alert alert-success ">{{ session('success') }}</div>
    @endif

    <div class="container py-4">
        {{-- العنوان وزر الإضافة --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center my-2 gap-2" dir="rtl">
            @if (!$showForm && !$isEdit)
                <button wire:click="create" class="btn btn-primary add-btn">
                    <i class="fas fa-plus mr-1"></i>
                    <span class="d-none d-md-inline">إضافة عملة</span>
                </button>
            @endif
            <h3 class="fw-bold text-primary mb-2 mb-sm-0 text-center text-sm-start d-none d-sm-block">
                إدارة العملات
            </h3>
        </div>

        {{-- نموذج الإضافة / التعديل --}}
        @if ($showForm || $isEdit)
            <div class="card mb-4 border-0 shadow-lg rounded-3">
                <div class="card-header bg-primary text-white py-3 rounded-top-3">
                    <h5 class="mb-0 ">
                        <i class="fas fa-coins mr-2"></i>  {{ $isEdit ? 'تعديل بيانات العملة' : 'إضافة عملة جديدة' }}
                      
                    </h5>
                </div>

                <div class="card-body py-4">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="row">
                            {{-- أيقونة العملة --}}
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label text-primary fw-bold d-block  mb-2">
                                    <i class="fas fa-image mr-2"></i> أيقونة العملة <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg shadow-sm rounded-2 overflow-hidden">
                                    <label class="input-group-text btn btn-primary text-white border-0" style="cursor: pointer;">
                                        <i class="fas fa-cloud-upload-alt mr-2"></i> رفع
                                        <input type="file" wire:model="iconFile" class="d-none" accept="image/*">
                                    </label>
                                    <span class="form-control  bg-light text-truncate">
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
                                    <div class="text-danger small  mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="mt-3 text-center">
                                    @if ($iconFile)
                                        <img src="{{ $iconFile->temporaryUrl() }}" alt="معاينة" class="img-thumbnail" width="80" height="80">
                                    @elseif ($icon)
                                        <img src="{{ url($icon) }}" alt="الأيقونة الحالية" class="img-thumbnail" width="80" height="80">
                                    @endif
                                </div>
                            </div>

                            {{-- اسم العملة --}}
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label text-primary fw-bold d-block  mb-2">
                                    <i class="fas fa-signature mr-2"></i> اسم العملة <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg shadow-sm rounded-2 overflow-hidden">
                                       <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-coins text-primary"></i>
                                    </span>
                                    <input type="text" wire:model.defer="name"
                                        class="form-control border-0  py-3 @error('name') is-invalid @enderror"
                                        placeholder="أدخل اسم العملة هنا...">
                                 
                                </div>
                                @error('name')
                                    <div class="invalid-feedback  d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- كود العملة --}}
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label text-primary fw-bold d-block  mb-2">
                                    <i class="fas fa-code mr-2"></i> كود العملة <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg shadow-sm rounded-2 overflow-hidden">
                                     <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-tag text-primary"></i>
                                    </span>
                                    <input type="text" wire:model.defer="code"
                                        class="form-control border-0  py-3 @error('code') is-invalid @enderror"
                                        placeholder="مثال: USD">
                                   
                                </div>
                                @error('code')
                                    <div class="invalid-feedback  d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- رمز العملة --}}
                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label text-primary fw-bold d-block  mb-2">
                                    <i class="fas fa-dollar-sign mr-2"></i> رمز العملة <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg shadow-sm rounded-2 overflow-hidden">
                                       <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-dollar-sign text-primary"></i>
                                    </span>
                                    <input type="text" wire:model.defer="symbol"
                                        class="form-control border-0  py-3 @error('symbol') is-invalid @enderror"
                                        placeholder="مثال: $">
                                 
                                </div>
                                @error('symbol')
                                    <div class="invalid-feedback  d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- الأزرار --}}
                            <div class="col-12 mt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <button type="button" wire:click="cancel" class="btn btn-outline-secondary btn-lg w-100 rounded-pill shadow-sm py-3 mb-2">
                                           <i class="fas fa-times mr-2"></i> إلغاء 
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <button type="submit" class="btn btn-{{ $isEdit ? 'warning' : 'primary' }} btn-lg w-100 rounded-pill shadow-sm py-3">
                                         <i class="fas {{ $isEdit ? 'fa-save' : 'fa-plus-circle' }} mr-2"></i>    {{ $isEdit ? 'حفظ التعديلات' : 'إضافة عملة' }}
                                           
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- جدول العملات --}}
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header-custom bg-light py-2 py-md-3">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                        <div class="input-group shadow-sm rounded-pill overflow-hidden border-0 ">
                               <span class="input-group-text bg-white border-0 rounded-0">
                                <i class="fas fa-search text-primary"></i>
                            </span>
                            <input type="text" class="form-control border-0  py-2"
                                placeholder="...ابحث باسم العملة" wire:model.debounce.300ms.live="search">
                         
                        </div>
                    </div>
                    <div class="col-12 col-md-6 d-none d-md-block">
                        <h5 class="mb-0 text-white">
                        <i class="fas fa-coins mr-2"></i>   قائمة العملات 
                        </h5>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0" dir="rtl">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-primary">
                            <tr>
                                <th class="text-center py-3 fw-bold">#</th>
                                <th class="text-center py-3 fw-bold">الشعار</th>
                                <th class="text-start py-3 fw-bold">اسم العملة</th>
                                <th class="text-center py-3 fw-bold">الكود</th>
                                <th class="text-center py-3 fw-bold">الرمز</th>
                                <th class="text-center py-3 fw-bold">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($currencies as $currency)
                                <tr class="border-bottom">
                                    <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        @if ($currency->icon)
                                            <img src="{{ url($currency->icon) }}" alt="Currency Icon" class="img-thumbnail" width="50" height="50">
                                        @else
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light" style="width: 50px; height: 50px;">
                                                <i class="fas fa-coins text-muted fa-lg"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-start fw-bold">{{ $currency->name }}</td>
                                    <td class="text-center">{{ $currency->code }}</td>
                                    <td class="text-center">{{ $currency->symbol }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <button class="btn btn-icon btn-sm btn-info mx-2 rounded-circle shadow-sm"
                                                wire:click="edit({{ $currency->id }})" data-bs-toggle="tooltip" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-icon btn-sm btn-danger rounded-circle shadow-sm"
                                                wire:click="confirmDelete({{ $currency->id }})" data-bs-toggle="tooltip" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-coins fa-2x mb-3"></i>
                                        <p class="mb-0 fs-5">لا توجد عملات مسجلة</p>
                                        <p class="text-muted small mt-2">انقر على زر "إضافة عملة" لبدء التسجيل</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- مودال الحذف --}}
        @if ($deleteId)
            <div class="modal fade show d-block " tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <button type="button" class="btn btn-close" wire:click="resetDelete">X</button>
                            <h5 class="modal-title">تأكيد الحذف</h5>
                        </div>
                        <div class="modal-body">
                            هل أنت متأكد من حذف العملة: <strong class="text-danger">{{ $deleteName }}</strong> ؟
                        </div>
                        <div class="modal-footer justify-content-start">
                            <button type="button" class="btn btn-secondary" wire:click="resetDelete">إلغاء</button>
                            <button type="button" class="btn btn-danger" wire:click="deleteCurrency">نعم، حذف</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
