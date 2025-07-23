<div class="container py-4">
    @if (session()->has('success'))
        <div class="alert alert-success text-right">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="row g-3">
                    <div class="col-md-4 mb-2">
                        <div class="input-group input-group-xl shadow-sm overflow-hidden">
                            <input type="file" wire:model="iconFile" class="form-control text-right border-0">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-upload"></i></span>
                        </div>
                        @error('iconFile')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        @if ($iconFile)
                            <div class="mt-2 text-center">
                                <img src="{{ $iconFile->temporaryUrl() }}" alt="معاينة" width="40" height="40"
                                    style="object-fit: cover; border-radius: 4px;">
                            </div>
                        @elseif ($icon)
                            <div class="mt-2 text-center">
                                <img src="{{ url($icon) }}" alt="الأيقونة الحالية" width="40" height="40"
                                    style="object-fit: cover; border-radius: 4px;">
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4 mb-2">
                        <div class="input-group input-group-xl shadow-sm overflow-hidden">
                            <input type="text" wire:model.defer="name" class="form-control text-right border-0"
                                placeholder="اسم البنك">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-university"></i></span>
                        </div>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-2">
                        <div class="input-group input-group-xl shadow-sm overflow-hidden">
                            <input type="text" class="form-control border-0 text-right"
                                placeholder="...ابحث باسم البنك" wire:model.debounce.300ms.live="search">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-search"></i></span>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <button type="submit" class="btn btn-{{ $isEdit ? 'warning' : 'primary' }} w-100">
                            {{ $isEdit ? 'تحديث' : 'إضافة' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive" dir="rtl">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>الأيقونة</th>
                        <th>الاسم</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($banks as $bank)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if ($bank->icon)
                                <img src="{{ url($bank->icon) }}" alt="Bank Icon" width="60" height="60">
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $bank->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-info mb-1" wire:click="edit({{ $bank->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger mb-1"
                                    wire:click="confirmDelete({{ $bank->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">لا توجد بنوك حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
