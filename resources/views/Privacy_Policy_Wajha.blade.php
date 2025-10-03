@extends('layouts.download-wajha-app')

@section('title', 'سياسة الخصوصية – وجهة')

@section('content')
    <div class="container my-5">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white text-center py-4">
                <h2 class="mb-0 fw-bold">سياسة الخصوصية – تطبيق “وجهة”</h2>
            </div>

            <div class="card-body p-5" style="direction: rtl; text-align: right; line-height: 1.9;">

                <!-- 1. المقدمة -->
                <div class="mb-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-shield-lock-fill me-2"></i>1. مقدمة</h5>
                    <p class="text-muted">
                        في “وجهة”، نلتزم بحماية خصوصيتك وبياناتك الشخصية. تنطبق هذه السياسة على جميع المستخدمين
                        الذين يستخدمون تطبيقنا أو موقعنا الإلكتروني، وتوضح كيف نقوم بجمع واستخدام ومشاركة وحماية بياناتك.
                    </p>
                </div>

                <!-- 2. البيانات -->
                <div class="mb-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-folder2-open me-2"></i>2. البيانات التي نقوم
                        بجمعها</h5>
                    <ul class="list-group shadow-sm">
                        <li class="list-group-item">الاسم الكامل</li>
                        <li class="list-group-item">رقم الجوال</li>
                        <li class="list-group-item">البريد الإلكتروني (اختياري)</li>
                        <li class="list-group-item">المدينة أو الموقع</li>
                        <li class="list-group-item">بيانات الحجز (تاريخ الوصول والمغادرة، نوع المنشأة، عدد الضيوف… إلخ)</li>
                        <li class="list-group-item">صورة سند التحويل أو إيصال الدفع البنكي</li>
                        <li class="list-group-item">عنوان IP</li>
                        <li class="list-group-item">نوع الجهاز ونظام التشغيل</li>
                        <li class="list-group-item">بيانات الموقع (عند تفعيل خدمة تحديد الموقع)</li>
                        <li class="list-group-item">سجل الاستخدام والبحث داخل التطبيق</li>
                    </ul>
                </div>

                <!-- 3. الاستخدام -->
                <div class="mb-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-gear-fill me-2"></i>3. كيف نستخدم بياناتك؟</h5>
                    <ol class="list-group list-group-numbered shadow-sm">
                        <li class="list-group-item">تنفيذ وإدارة الحجوزات.</li>
                        <li class="list-group-item">التواصل معك بخصوص حالة الحجز أو أي تحديثات.</li>
                        <li class="list-group-item">إرسال إشعارات مهمة (مثل تذكير بالحجز أو العروض).</li>
                        <li class="list-group-item">تحسين تجربة المستخدم وتحليل سلوك الاستخدام.</li>
                        <li class="list-group-item">ضمان الامتثال القانوني وحماية التطبيق من الأنشطة الاحتيالية.</li>
                    </ol>
                </div>

                <!-- 4. مشاركة البيانات -->
                <div class="mb-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-people-fill me-2"></i>4. مشاركة البيانات</h5>
                    <p class="text-muted">
                        نشارك بياناتك فقط مع مزودي الخدمة (الفنادق أو الشاليهات...) أو الجهات التنظيمية عند الطلب الرسمي.
                        <br>
                        <span class="fw-bold text-danger">لن نقوم أبدًا ببيع أو تأجير بياناتك لأي طرف ثالث تجاري.</span>
                    </p>
                </div>

                <!-- 5. حماية -->
                <div class="mb-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-shield-check me-2"></i>5. حماية البيانات</h5>
                    <p class="text-muted">
                        نستخدم تقنيات تشفير واتصالات آمنة (SSL) ونخزن البيانات في خوادم محمية بمعايير عالية.
                    </p>
                </div>

                <!-- 6. الحقوق -->
                <div class="mb-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-person-check-fill me-2"></i>6. حقوق المستخدم</h5>
                    <ul class="list-group shadow-sm">
                        <li class="list-group-item">الوصول إلى بياناتك الشخصية في أي وقت.</li>
                        <li class="list-group-item">طلب تعديل أو حذف بياناتك.</li>
                        <li class="list-group-item">إلغاء الاشتراك من الرسائل التسويقية.</li>
                        <li class="list-group-item">التواصل مع الدعم الفني لأي استفسار بخصوص بياناتك.</li>
                    </ul>
                </div>

                <!-- 7. الاحتفاظ -->
                <div class="mb-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-archive-fill me-2"></i>7. الاحتفاظ بالبيانات</h5>
                    <p class="text-muted">
                        نحتفظ ببياناتك فقط للفترة اللازمة لتقديم الخدمة، أو وفقًا لما تتطلبه الأنظمة والقوانين المحلية.
                    </p>
                </div>

                <!-- 8. التعديلات -->
                <div class="mb-5">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-arrow-repeat me-2"></i>8. التعديلات على السياسة
                    </h5>
                    <p class="text-muted">
                        قد نقوم بتحديث سياسة الخصوصية من وقت لآخر، وسيتم إشعار المستخدمين بأي تغييرات رئيسية.
                    </p>
                </div>

                <!-- 9. التواصل -->
                <div>
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-telephone-fill me-2"></i>9. التواصل معنا</h5>
                    <p class="text-muted">
                        لأي استفسار بخصوص سياسة الخصوصية:<br>
                        📧 البريد الإلكتروني:
                        <a href="mailto:support@wajha.com" class="text-decoration-none text-primary">
                            support@wajha.com
                        </a><br>
                        📞 رقم الدعم الفني:
                        <a href="tel:+967770662355" class="text-decoration-none text-primary">
                            +967 770 662 355
                        </a>
                    </p>

                </div>

            </div>
        </div>
    </div>
@endsection
