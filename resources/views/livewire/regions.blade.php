<div>
    @if (session()->has('success'))
        <div class="alert alert-success text-right">{{ session('success') }}</div>
    @endif
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center my-2">
            @if (!$showForm && !$isEdit)
                <button wire:click="create" class="btn btn-primary add-btn text-left">إضافة منطقة</button>
            @endif
            <h3 class="text-left">إدارة المناطق</h3>
        </div>
        @if ($showForm || $isEdit)
            <div class="card mb-4 border-0 shadow-sm rounded-3">
                <div class="card-header bg-gradient-primary text-white py-3 rounded-top-3">
                    <h5 class="mb-0 text-center">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        {{ $isEdit ? 'تعديل المنطقة' : 'إضافة منطقة جديدة' }}
                    </h5>
                </div>

                <div class="card-body py-4">
                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                        <div class="row g-4">
                            {{-- حقل المنطقة الرئيسية --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-primary fw-bold d-block text-right mb-2">
                                        المنطقة الرئيسية
                                    </label>

                                    <div class="input-group input-group-lg shadow-sm rounded-2 overflow-hidden">
                                        <span class="input-group-text bg-white border-0">
                                            <i class="fas fa-layer-group text-primary"></i>
                                        </span>
                                        <select wire:model.defer="parent_id"
                                            class="form-control text-right border-0 py-3">
                                            <option value="">بدون منطقة رئيسية</option>
                                            @foreach ($parents as $parent)
                                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- حقل اسم المنطقة --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-primary fw-bold d-block text-right mb-2">
                                        اسم المنطقة <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group input-group-lg shadow-sm rounded-2 overflow-hidden">
                                        <input type="text" wire:model.defer="name"
                                            class="form-control border-0 text-right py-3 @error('name') is-invalid @enderror"
                                            placeholder="أدخل اسم المنطقة">
                                        <span class="input-group-text bg-white border-0">
                                            <i class="fas fa-map-marker-alt text-primary"></i>
                                        </span>
                                    </div>

                                    @error('name')
                                        <div class="invalid-feedback text-right d-block mt-2">
                                            <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- زر الإرسال والإلغاء --}}
                            <div class="col-12 mt-4">
                                <div class="d-flex gap-3">
                                    {{-- زر الإلغاء --}}
                                    <div class="flex-grow-1">
                                        <button type="button" wire:click="cancel"
                                            class="btn btn-outline-secondary btn-lg w-100 rounded-pill shadow-sm py-3">
                                            <i class="fas fa-times me-2"></i> إلغاء
                                        </button>
                                    </div>

                                    {{-- زر الحفظ/الإضافة --}}
                                    <div class="flex-grow-1">
                                        <button type="submit"
                                            class="btn btn-{{ $isEdit ? 'warning' : 'primary' }} btn-lg w-100 rounded-pill shadow-sm py-3">
                                            {{ $isEdit ? 'حفظ التغييرات' : 'إضافة منطقة' }}
                                            <i class="fas {{ $isEdit ? 'fa-save' : 'fa-plus-circle' }} me-2"></i>

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
    <!-- رأس البطاقة مع شريط البحث -->
    <div class="card-header bg-light py-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    إدارة المناطق
                </h5>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                    <input type="text" 
                           class="form-control border-0 text-right py-2"
                           placeholder="ابحث باسم المنطقة..."
                           wire:model.debounce.300ms.live="search">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول البيانات -->
    <div class="card-body p-0" dir="rtl">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-primary">
                    <tr>
                        <th class="text-center py-3 fw-bold">#</th>
                        <th class="text-start py-3 fw-bold">المديرية</th>
                        <th class="text-start py-3 fw-bold">المحافظة</th>
                        <th class="text-center py-3 fw-bold" >الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($regions as $region)
                        <tr class="border-bottom">
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            
                            <td class="text-start">
                                <span class="fw-bold">{{ $region->name }}</span>
                            </td>
                            
                            <td class="text-start">
                                @if($region->parent)
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $region->parent->name }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            
                            <td class="text-center">
                                <div class="d-flex  gap-2"  style="margin: 0px 29%">
                                    <button class="btn btn-icon btn-sm btn-info mr-5 mx-3 rounded-circle shadow-sm"
                                  
                                            wire:click="edit({{ $region->id }})"
                                            data-bs-toggle="tooltip"
                                            title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-icon btn-sm btn-danger rounded-circle shadow-sm"
                                            wire:click="confirmDelete({{ $region->id }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-bs-toggle="tooltip"
                                            title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-map-marker-alt fa-2x mb-3"></i>
                                <p class="mb-0 fs-5">لا توجد مناطق مسجلة</p>
                                @if($search)
                                    <p class="text-muted small mt-2">لا توجد نتائج مطابقة لبحثك</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- مودال الحذف Livewire فقط --}}
    @if ($deleteId)
        <div class="modal fade show d-block text-right" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white ">
                        <button type="button" class="btn btn-close" wire:click="$set('deleteId', false)">X</button>

                        <h5 class="modal-title">تأكيد الحذف</h5>
                    </div>
                    <div class="modal-body">
                        هل أنت متأكد أنك تريد حذف المنطقة:
                        <strong class="text-danger">"{{ $deleteName }}"</strong>؟ لا يمكن التراجع بعد الحذف.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('deleteId', false)">إلغاء</button>
                        <button type="button" class="btn btn-danger" wire:click="deleteRegion">نعم، حذف</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
