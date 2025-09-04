<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-3">
        <h3 class="mb-4 text-center">إضافة الاشعارات</h3>
        @if (session()->has('success'))
        <div class="alert alert-success text-right">{{ session('success') }}</div>
    @endif
        <form wire:submit.prevent="sendNotification()">
            @csrf

            <div class="mb-3">
                <label class="form-label">العنوان</label>
                <input type="text" wire:model.defer="title" class="form-control" placeholder="أدخل العنوان" required>
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea wire:model.defer="description" class="form-control" rows="3" placeholder="أدخل الوصف"></textarea>
                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

             <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                <span class="input-group-text bg-white border-0 rounded-0">
                    <i class="fas fa-user text-primary fs-5"></i>
                </span>
                <select wire:model.defer="user_type" class="form-control border-0">
                    <option value="All" selected>جميع المستخدمين</option>
                    <option value="owner">مالكي المنشئات</option>
                    <option value="user">المستخدمين</option>
                </select>
                @error('user_type') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary px-4 w-100">إرسال</button>
            </div>
        </form>
    </div>
</div>
