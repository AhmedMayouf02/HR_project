# HR_Proj

نظام إدارة موارد بشرية (HR). حالياً الـ backend فقط، مبني على **Laravel 13** كـ REST API مع مصادقة **Sanctum**.

```
HR_Proj/
└── backend/        ← Laravel API
    (frontend/      ← لاحقاً)
```

## التشغيل السريع

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

حساب تجريبي: `admin@hr.test` / `password`

الاختبارات:

```bash
cd backend && php artisan test
```

## الوحدات (Modules)

| الوحدة | الجدول | الـ Endpoint |
|---|---|---|
| الأقسام | `departments` | `/api/v1/departments` |
| المسميات الوظيفية | `positions` | `/api/v1/positions` |
| الموظفين | `employees` | `/api/v1/employees` |
| الحضور والانصراف | `attendances` | `/api/v1/attendances` |
| أنواع الإجازات | `leave_types` | `/api/v1/leave-types` |
| طلبات الإجازة (+ موافقة/رفض) | `leave_requests` | `/api/v1/leave-requests` |
| الرواتب | `payrolls` | `/api/v1/payrolls` |
| مستندات الموظف | `employee_documents` | `/api/v1/employees/{id}/documents` |
| المصادقة | `personal_access_tokens` | `/api/v1/auth/login`, `/auth/me`, `/auth/logout` |

كل الـ endpoints (ما عدا login) تحتاج الهيدر `Authorization: Bearer <token>`.

## تقسيمة الملفات

```
backend/
├── app/
│   ├── Enums/                      # القيم الثابتة (الحالات والأنواع)
│   │   ├── EmploymentStatus.php    # active, on_leave, suspended, terminated
│   │   ├── EmploymentType.php      # full_time, part_time, contract, intern
│   │   ├── Gender.php
│   │   ├── AttendanceStatus.php    # present, absent, late, on_leave, remote
│   │   ├── LeaveRequestStatus.php  # pending, approved, rejected, cancelled
│   │   ├── PayrollStatus.php       # draft, approved, paid
│   │   └── DocumentType.php
│   │
│   ├── Http/
│   │   ├── Controllers/Api/V1/     # Controller لكل وحدة (رفيع: يستقبل ويرجّع فقط)
│   │   │   ├── AuthController.php
│   │   │   ├── DepartmentController.php
│   │   │   ├── PositionController.php
│   │   │   ├── EmployeeController.php
│   │   │   ├── AttendanceController.php
│   │   │   ├── LeaveTypeController.php
│   │   │   ├── LeaveRequestController.php
│   │   │   ├── PayrollController.php
│   │   │   └── EmployeeDocumentController.php
│   │   │
│   │   ├── Requests/               # التحقق من المدخلات (Validation) — مجلد لكل وحدة
│   │   │   ├── Auth/LoginRequest.php
│   │   │   ├── Employee/{Store,Update}EmployeeRequest.php
│   │   │   ├── LeaveRequest/{Store,Update,Review}LeaveRequestRequest.php
│   │   │   └── ...
│   │   │
│   │   └── Resources/              # شكل الـ JSON الراجع للـ frontend
│   │       ├── EmployeeResource.php
│   │       └── ...
│   │
│   ├── Models/                     # جداول قاعدة البيانات والعلاقات بينها
│   │   ├── User.php                # حساب الدخول (مرتبط بموظف)
│   │   ├── Department.php
│   │   ├── Position.php
│   │   ├── Employee.php
│   │   ├── Attendance.php
│   │   ├── LeaveType.php
│   │   ├── LeaveRequest.php
│   │   ├── Payroll.php
│   │   └── EmployeeDocument.php
│   │
│   └── Services/                   # منطق العمل (Business logic)
│       ├── LeaveRequestService.php # حساب أيام العمل، الموافقة/الرفض
│       └── PayrollService.php      # حساب صافي الراتب، تاريخ الدفع
│
├── config/hr.php                   # إعدادات الـ HR (أيام العطلة، قرص المستندات)
├── database/
│   ├── migrations/                 # إنشاء الجداول
│   ├── factories/                  # بيانات وهمية للاختبارات
│   └── seeders/DatabaseSeeder.php  # بيانات تجريبية (أقسام، موظفين، أنواع إجازات)
├── routes/api.php                  # كل مسارات الـ API تحت /api/v1
└── tests/Feature/Api/              # اختبارات لكل وحدة
```

### القاعدة عند إضافة وحدة جديدة

1. `Model` + `migration` + `factory` في `app/Models` و `database/`
2. `Store…Request` / `Update…Request` في `app/Http/Requests/<Module>/`
3. `…Resource` في `app/Http/Resources/`
4. `…Controller` في `app/Http/Controllers/Api/V1/`
5. أي منطق معقّد يروح `app/Services/` (مو في الـ Controller)
6. سطر في `routes/api.php` + اختبار في `tests/Feature/Api/`

## العلاقات

```
Department 1─* Position
Department 1─* Employee *─1 Position
Employee   1─* Employee            (manager_id → المدير المباشر)
Employee   1─* Attendance | LeaveRequest | Payroll | EmployeeDocument
User       1─1 Employee            (حساب الدخول)
LeaveType  1─* LeaveRequest
```

## ملاحظات

- أيام العطلة الأسبوعية الافتراضية **الجمعة والسبت** (`HR_WEEKEND_DAYS=5,6` في `.env`). للسبت والأحد: `HR_WEEKEND_DAYS=6,0`.
- الصلاحيات (Admin / HR / موظف) **غير مطبّقة بعد**: أي مستخدم مسجّل دخول يقدر يوصل لكل الـ endpoints.
- قاعدة البيانات الافتراضية SQLite؛ للـ MySQL عدّل `DB_*` في `.env`.
