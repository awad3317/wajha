<div>
    <div class="container py-4">
        <h3 class="mb-4 text-right">إدارة الإعلانات</h3>

        {{-- رسالة النجاح --}}
        @if (session()->has('success'))
            <div class="alert alert-success text-right">
                {{ session('success') }}
            </div>
        @endif
        <style>
            .form-switch {
                position: relative;
                display: inline-block;
                width: 2.5rem;
                height: 1.4rem;
                margin-right: 0.5rem;
            }

            .form-switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }

            .form-switch label::before {
                content: "";
                position: absolute;
                top: 0;
                right: 0;
                width: 2.5rem;
                height: 1.4rem;
                background-color: #adb5bd;
                border-radius: 1.5rem;
                transition: background-color 0.3s;
            }

            .form-switch label::after {
                content: "";
                position: absolute;
                top: 0.1rem;
                right: 0.1rem;
                width: 1.2rem;
                height: 1.2rem;
                background-color: white;
                border-radius: 50%;
                transition: transform 0.3s, background-color 0.3s;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
            }

            .form-switch input:checked+label::after {
                transform: translateX(-1.1rem);
            }

            .switch-active label::before {
                background-color: #007bff;
            }

            .switch-banned label::before {
                background-color: #dc3545;
            }
        </style>
        {{-- النموذج --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" enctype="multipart/form-data">
                    <div class="row g-3">

                        {{-- حالة التفعيل --}}
                        <div class="col-md-4 d-flex align-items-center mt-4 justify-content-end">
                            <div class="form-check d-flex align-items-center">
                                <span
                                    class="form-check-label-text me-2 {{ $is_active ? 'text-primary' : 'text-danger' }}">
                                    {{ $is_active ? 'مفعل' : 'غير مفعل' }}
                                </span>
                                <div class="form-switch {{ $is_active ? 'switch-active' : 'switch-banned' }}">
                                    <input type="checkbox" id="isActiveSwitch" wire:model.defer="is_active"
                                        @if ($is_active) checked @endif>
                                    <label for="isActiveSwitch"></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="image" class="form-label text-right d-block">صورة الإعلان
                                {{ $isEdit ? '(اختياري)' : '*' }}</label>
                            <input id="image" type="file" wire:model="image" accept="image/*"
                                class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            {{-- معاينة الصورة --}}
                            <div class="mt-3">
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail"
                                        style="max-height: 150px;">
                                @elseif ($imagePreview)
                                    <img src="{{ asset('storage/' . $imagePreview) }}" class="img-thumbnail"
                                        style="max-height: 150px;">
                                @endif
                            </div>
                        </div>

                        {{-- العنوان --}}
                        <div class="col-md-4">
                            <label for="title" class="form-label text-right d-block">عنوان الإعلان <span
                                    class="text-danger">*</span></label>
                            <input id="title" type="text" wire:model.defer="title"
                                class="form-control text-right @error('title') is-invalid @enderror"
                                placeholder="عنوان الإعلان">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- وصف الإعلان --}}
                        <div class="col-12 mb-2">
                            <label for="description" class="form-label text-right d-block">وصف الإعلان <span
                                    class="text-danger">*</span></label>
                            <textarea id="description" wire:model.defer="description" rows="4"
                                class="form-control text-right @error('description') is-invalid @enderror" placeholder="وصف الإعلان"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- تواريخ البدء والانتهاء --}}

                        <div class="col-md-6 ">
                            <label for="end_date" class="form-label text-right d-block">تاريخ الانتهاء</label>
                            <input id="end_date" type="datetime-local" wire:model.defer="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                placeholder="تاريخ الانتهاء">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="start_date" class="form-label text-right d-block">تاريخ البدء <span
                                    class="text-danger">*</span></label>
                            <input id="start_date" type="datetime-local" wire:model.defer="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                placeholder="تاريخ البدء">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        {{-- زر الإضافة أو التحديث --}}
                        <div class="col-md-12 d-flex align-items-end mt-2">
                            <button type="submit" class="btn btn-{{ $isEdit ? 'warning' : 'primary' }} w-100">
                                {{ $isEdit ? 'تحديث' : 'إضافة' }}
                            </button>
                        </div>



                    </div>
                </form>
            </div>
        </div>

        {{-- جدول الإعلانات --}}
        <div class="row g-4 mt-2">
            <div class="col-md-12  mb-2">
                <div class="input-group input-group-xl shadow-sm  overflow-hidden">
                    <input type="text" class="form-control border-0 text-right" placeholder="...ابحث باسم الاعلان"
                        wire:model.debounce.300ms.live="search">

                    <div class="input-group-append">
                        <span class="input-group-text bg-white border-0">
                            <i class="fas fa-search text-secondary"></i>
                        </span>
                    </div>
                </div>
            </div>
            @forelse ($advertisements as $ad)
                <div class="col-md-6 col-lg-4 my-2">
                    <div class="card border-0 shadow rounded-3 h-100 overflow-hidden position-relative">

                        {{-- صورة الإعلان --}}
                        @if ($ad->image)
                            <img src="{{ asset('storage/' . $ad->image) }}" alt="صورة الإعلان" class="w-100"
                                style="height: 180px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="height: 180px;">
                                <span class="text-muted">لا توجد صورة</span>
                            </div>
                        @endif

                        {{-- الحالة في الزاوية --}}
                        <span
                            class="position-absolute top-0 end-0 m-2 badge {{ $ad->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $ad->is_active ? 'مفعل' : 'غير مفعل' }}
                        </span>

                        <div class="card-body text-end d-flex flex-column">

                            {{-- عنوان الإعلان --}}
                            <h5 class="fw-bold text-truncate mb-2 text-right" title="{{ $ad->title }}">
                                {{ $ad->title }}
                            </h5>

                            {{-- وصف الإعلان --}}
                            <p class="text-muted small text-right" style="max-height: 4.5em; overflow: hidden;"
                                title="{{ $ad->description }}">
                                {{ $ad->description }}
                            </p>

                            {{-- التواريخ --}}
                            <div class="d-flex justify-content-end small mt-2 mb-3 text-right">

                                <div>النهاية:
                                    {{ $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('Y-m-d') : '—' }}
                                    <i class="far fa-calendar-check ms-1"></i>
                                </div>
                                <div class="mx-4">البداية:
                                    {{ \Carbon\Carbon::parse($ad->start_date)->format('Y-m-d') }}
                                    <i class="far fa-calendar-alt ms-1"></i>
                                </div>
                            </div>

                            {{-- الأزرار --}}
                            <div class="d-flex gap-2 mt-auto ">
                                <button class="btn btn-info btn-sm w-50" wire:click="edit({{ $ad->id }})">
                                    <i class="fas fa-edit ms-1"></i> تعديل
                                </button>
                                <button class="btn btn-danger btn-sm w-50 ml-2"
                                    wire:click="confirmDelete({{ $ad->id }})">
                                    <i class="fas fa-trash ms-1"></i> حذف
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted fs-5">
                    لا توجد إعلانات بعد.
                </div>
            @endforelse
        </div>




        {{-- مودال الحذف Livewire فقط --}}
        @if ($deleteId)
            <div class="modal fade show d-block text-right" tabindex="-1"
                style="background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">تأكيد الحذف</h5>
                            <button type="button" class="btn-close" wire:click="$set('deleteId', null)"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            هل أنت متأكد أنك تريد حذف الإعلان:
                            <strong class="text-danger">"{{ $deleteTitle }}"</strong>؟ لا يمكن التراجع بعد الحذف.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('deleteId', null)">إلغاء</button>
                            <button type="button" class="btn btn-danger" wire:click="deleteAdvertisement">نعم،
                                حذف</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- سكريبتات SweetAlert2 --}}
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
            });
        });

        window.addEventListener('close-delete-modal', () => {
            const modal = document.querySelector('.modal.show');
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }
        });
    </script>
</div>
