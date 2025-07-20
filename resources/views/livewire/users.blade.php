<div dir="rtl">

    {{-- @if ($message)
        <div class="message-center"
            style="position: fixed;left: 50%;transform: translate(-50%, -50%);
        z-index: 9999;padding: 20px;border-radius: 8px;text-align: center;
        animation: fadeInOut 12s forwards;
        background: {{ $messageType == 'success' ? '#4CAF50' : '#F44336' }};
        color: white;">
            {{ $message }}
        </div>
    @endif

    <style>
        @keyframes fadeInOut {
            0% {
                opacity: 0;
            }

            5% {
                opacity: 1;
            }

            85% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                visibility: hidden;
            }
        }
    </style> --}}

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

        .form-check-label-text {
            margin-left: 0.5rem;
        }


        .text-right {
            text-align: right;
        }

        .table {
            direction: rtl;
        }

        .table th,
        .table td {
            text-align: right;
        }

        .input-group-append {
            margin-right: -1px;
            margin-left: 0;
        }

        .input-group-text {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }

        .form-control {
            text-align: right;
        }

        .card-footer .pagination {
            justify-content: flex-start;
        }

        .d-flex {
            flex-direction: row-reverse;
        }
    </style>

    <div class="col-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="m-1 row">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <input wire:model.debounce.300ms.live="search" type="text"
                                class="form-control text-right" placeholder="ابحث بالاسم أو الجوال">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <select wire:model.live="userType" class="form-control text-right">
                                <option value="">كل الأنواع</option>
                                <option value="admin">مدير</option>
                                <option value="owner">صاحب منشأة</option>
                                <option value="user">مستخدم عادي</option>
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <select wire:model.live="bannedStatus" class="form-control text-right">
                                <option value="">كل الحالات</option>
                                <option value="1">محظور</option>
                                <option value="0">نشط</option>
                            </select>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-ban"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>الاسم</th>
                                <th>رقم الجوال</th>
                                <th>نوع المستخدم</th>
                                <th>حالة الحظر</th>
                                <th style="width: 150px">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr wire:key="{{ $user->id }}">
                                    <td>{{ $loop->iteration }}</td>

                                    <td>

                                        {{ $user->name }}

                                    </td>

                                    <td>

                                        {{ $user->phone ?? 'غير محدد' }}

                                    </td>

                                    <td>
                                        @if ($editingUserId === $user->id)
                                            <select class="form-control" wire:model.defer="editType">
                                                <option value="admin">مدير</option>
                                                <option value="owner">صاحب منشأة</option>
                                                <option value="user">مستخدم</option>
                                            </select>
                                        @else
                                            @switch($user->user_type)
                                                @case('admin')
                                                    <span class="badge-red">مدير</span>
                                                @break

                                                @case('owner')
                                                    <span class="badge-yellow">صاحب منشأة</span>
                                                @break

                                                @default
                                                    <span class="badge-green">مستخدم</span>
                                            @endswitch
                                        @endif
                                    </td>

                                    <td style="vertical-align: middle; text-align: end;">
                                        <div class="form-check d-flex align-items-center justify-content-end">
                                            <span class="form-check-label-text me-2">
                                                {{ $user->is_banned ? 'محظور' : 'نشط' }}
                                            </span>
                                            <div
                                                class="form-switch {{ $user->is_banned ? 'switch-banned' : 'switch-active' }}">
                                                <input type="checkbox" id="banSwitch{{ $user->id }}"
                                                    wire:click="toggleBan({{ $user->id }})"
                                                    @if (!$user->is_banned) checked @endif>
                                                <label for="banSwitch{{ $user->id }}"></label>
                                            </div>
                                        </div>
                                    </td>



                                    <td>
                                        @if ($editingUserId === $user->id)
                                            <div class="d-flex my-2 ">
                                                <button class="btn btn-success btn-xs mx-1" wire:click="updateUser"
                                                    title="حفظ">
                                                    <i class="fas fa-check"></i>
                                                    حفظ
                                                </button>
                                                <button class="btn btn-secondary btn-xs" wire:click="cancelEdit">
                                                    ✖ إلغاء
                                                </button>

                                            </div>
                                        @else
                                            <button class="btn btn-warning btn-sm"
                                                wire:click="editUser({{ $user->id }})" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{ $users->links() }}
            </div>
        </div>
    </div>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
</div>
