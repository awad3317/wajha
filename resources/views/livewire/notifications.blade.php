<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-3">
        <h3 class="mb-4 text-center">إضافة الاشعارات</h3>
        <form action="" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">العنوان</label>
                <input type="text" name="title" class="form-control" placeholder="أدخل العنوان" required>
            </div>

            <div class="mb-3">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control" rows="3" placeholder="أدخل الوصف"></textarea>
            </div>

            <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                <span class="input-group-text bg-white border-0 rounded-0">
                    <i class="fas fa-user text-primary fs-5"></i>
                </span>
                <select name="user_type" class="form-control  border-0">
                    <option value="" selected disabled>اختر اليوزر</option>
                    <option value="admin">مدير</option>
                    <option value="driver">سائق</option>
                    <option value="student">طالب</option>
                    <option value="employee">موظف</option>
                </select>
            </div>


            <div class="text-center mt-3 ">
                <button type="submit" class="btn btn-primary px- w-100">حفظ</button>
            </div>
        </form>
    </div>
</div>
