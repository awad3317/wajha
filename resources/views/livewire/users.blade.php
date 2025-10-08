<div class="container py-4">
    <style>
        /* Switch Toggle Styles */
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
            transition: transform 0.3s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
        }

        .form-switch input:checked+label::after {
            transform: translateX(-1.1rem);
        }

        .switch-active label::before {
            background-color: #28a745;
        }

        .switch-banned label::before {
            background-color: #dc3545;
        }

        /* Badge Styles */
        .badge-red {
            background-color: #dc3545;
            color: white;
        }

        .badge-yellow {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-green {
            background-color: #28a745;
            color: white;
        }

        .badge-red,
        .badge-yellow,
        .badge-green {
            padding: 0.35em 0.65em;
            border-radius: 0.25rem;
            font-size: 0.875em;
        }

        /* Table Styles */
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        /* Pagination */
        .pagination {
            justify-content: flex-start;
        }

        /* Action Links */
        .action-link {
            color: #007bff;
            text-decoration: none;
            transition: color 0.2s;
        }

        .action-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Custom Card Styles */
        .card-header-custom {
            background: linear-gradient(135deg, #1976D2 0%, #0D47A1 100%);
        }

        .filter-section {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
    <!-- زر إضافة -->
    <div class="d-flex justify-content-end p-3">
        <button class="btn btn-primary add-btn shadow-sm rounded-pill px-4" wire:click="$toggle('showAddForm')">
            <i class="fas fa-plus me-2"></i> إضافة صاحب منشأة
        </button>
    </div>

    <!-- فورم الإضافة -->
    @if ($showAddForm)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-white"><i class="fas fa-user-plus me-2"></i> إضافة صاحب منشأة جديد</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">الاسم</label>
                        <input type="text" class="form-control @error('newName') is-invalid @enderror"
                            wire:model="newName">
                        @error('newName')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">رقم الجوال</label>
                        <div class="input-group">
                            <select class="form-select" style="max-width: 100px;" wire:model.live="newCountry">
                                <option value="YE">967 🇾🇪</option>
                                <option value="SA">966 🇸🇦</option>
                                <option value="EG">20 🇪🇬</option>
                                <option value="AE">971 🇦🇪</option>
                            </select>
                            <input type="text" class="form-control @error('newPhone') is-invalid @enderror"
                                wire:model="newPhone" placeholder="780236551">
                        </div>
                        @error('newPhone')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-md-6">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control @error('newPassword') is-invalid @enderror"
                            wire:model="newPassword">
                        @error('newPassword')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-between">
                    <button class="btn btn-primary rounded-pill px-4 mx-2 w-100" wire:click="storeOwner">
                        <i class="fas fa-save me-2"></i> حفظ
                    </button>
                    <button class="btn btn-outline-secondary rounded-pill px-4  w-100"
                        wire:click="$toggle('showAddForm')">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <!-- Card Header -->
            <div class="card-header card-header-custom text-white">
                <h5 class="mb-0 ">
                    <i class="fas fa-users-cog me-2"></i>
                    إدارة المستخدمين
                </h5>
            </div>
            <!-- Filter Section -->
            <div class="filter-section row g-3 p-4">
                <div class="col-md-4">
                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                        <input wire:model.debounce.300ms.live="search" type="text" class="form-control border-0 py-2"
                            placeholder="البحث بالاسم أو الجوال...">
                        <span class="input-group-text bg-white border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                        <select wire:model.live="userType" class="form-control border-0 py-2">
                            <option value="">جميع أنواع المستخدمين</option>
                            <option value="admin">مدير النظام</option>
                            <option value="owner">أصحاب المنشآت</option>
                            <option value="user">المستخدمون العاديون</option>
                        </select>
                        <span class="input-group-text bg-white border-0">
                            <i class="fas fa-user-tag text-muted"></i>
                        </span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden">
                        <select wire:model.live="bannedStatus" class="form-control border-0 py-2">
                            <option value="">جميع حالات المستخدمين</option>
                            <option value="1">الحسابات المحظورة</option>
                            <option value="0">الحسابات النشطة</option>
                        </select>
                        <span class="input-group-text bg-white border-0">
                            <i class="fas fa-user-lock text-muted"></i>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-3 fw-bold" width="60">#</th>
                            <th class="text-start py-3 fw-bold">الاسم</th>
                            <th class="text-start py-3 fw-bold">رقم الجوال</th>
                            <th class="text-center py-3 fw-bold">نوع المستخدم</th>
                            <th class="text-center py-3 fw-bold">الحالة</th>
                            <th class="text-center py-3 fw-bold" width="180">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr wire:key="{{ $user->id }}" class="{{ $user->is_banned ? 'table-danger' : '' }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-start fw-semibold">{{ $user->name }}</td>
                                <td class="text-start">{{ $user->phone ?? 'غير محدد' }}</td>

                                <td class="text-center">
                                    @if ($editingUserId === $user->id)
                                        @if ($user->user_type === 'owner')
                                            <span class="badge badge-yellow">صاحب منشأة</span>
                                        @else
                                            <select class="form-control form-control-sm shadow-sm"
                                                wire:model.defer="editType">
                                                <option value="admin">مدير</option>
                                                <option value="owner">صاحب منشأة</option>
                                                <option value="user">مستخدم</option>
                                            </select>
                                        @endif
                                    @else
                                        @switch($user->user_type)
                                            @case('admin')
                                                <span class="badge badge-red">مدير</span>
                                            @break

                                            @case('owner')
                                                <span class="badge badge-yellow">صاحب منشأة</span>
                                            @break

                                            @default
                                                <span class="badge badge-green">مستخدم</span>
                                        @endswitch
                                    @endif
                                </td>


                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="me-2 small">{{ $user->is_banned ? 'محظور' : 'نشط' }}</span>
                                        <div
                                            class="form-switch {{ $user->is_banned ? 'switch-banned' : 'switch-active' }}">
                                            <input type="checkbox" id="banSwitch{{ $user->id }}"
                                                wire:click="toggleBan({{ $user->id }})"
                                                @if (!$user->is_banned) checked @endif>
                                            <label for="banSwitch{{ $user->id }}"></label>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    @if ($editingUserId === $user->id)
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-success shadow-sm rounded-pill px-3"
                                                wire:click="updateUser">
                                                <i class="fas fa-check me-1"></i> حفظ
                                            </button>
                                            <button
                                                class="btn btn-sm btn-outline-secondary shadow-sm rounded-pill px-3"
                                                wire:click="cancelEdit">
                                                <i class="fas fa-times me-1"></i> إلغاء
                                            </button>
                                        </div>
                                    @else
                                     @if ($user->user_type === 'owner')
                                            <span class="badge badge-primary">لايمكن التعديل</span>
                                        @else
                                        <button class="btn btn-sm btn-outline-primary shadow-sm rounded-pill px-3"
                                            wire:click="editUser({{ $user->id }})">
                                            <i class="fas fa-edit me-1"></i> تعديل
                                        </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Card Footer with Pagination -->
            <div class="card-footer">
                {{ $users->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
