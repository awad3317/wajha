<div>
    <div class="container py-4">
        <h3 class="mb-4 text-right">إدارة المناطق</h3>

        @if (session()->has('success'))
            <div class="alert alert-success text-right">{{ session('success') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">

                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                    <div class="row g-3">
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-xl shadow-sm  overflow-hidden">

                                <input type="text" wire:model.defer="name" class="form-control text-right border-0"
                                    placeholder="اسم المنطقة">
                                <span class="input-group-text bg-white border-0"><i
                                        class="fas fa-map-marker-alt"></i></span>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="input-group-xl shadow-sm  overflow-hidden">
                                <select wire:model.defer="parent_id" class="form-control text-right border-0">
                                    <option value="">بدون منطقة رئيسية</option>
                                    @foreach ($parents as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4  mb-2">
                            <div class="input-group input-group-xl shadow-sm  overflow-hidden">
                                <input type="text" class="form-control border-0 text-right"
                                    placeholder="...ابحث باسم المنطقه" wire:model.debounce.300ms.live="search">

                                <div class="input-group-append">
                                    <span class="input-group-text bg-white border-0">
                                        <i class="fas fa-search text-secondary"></i>
                                    </span>
                                </div>
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
                            <th>الاسم</th>
                            <th>الرئيسية</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($regions as $region)
                            <tr>
                                <td>{{ $region->id }}</td>
                                <td>{{ $region->name }}</td>
                                <td>{{ $region->parent->name ?? '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info mb-1" wire:click="edit({{ $region->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger mb-1"
                                        wire:click="confirmDelete({{ $region->id }})" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">لا توجد مناطق بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
