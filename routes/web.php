<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamCategoryController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\GradeBookController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentFeeController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\SyllabusController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\Report\BankBalanceReportController;
use App\Http\Controllers\Admin\Report\ExpenseReportController;
use App\Http\Controllers\Admin\Report\StudentFeeReportController;
use App\Http\Controllers\Admin\Report\TeacherSalaryReportController;
use App\Http\Controllers\Admin\TeacherAttendanceController;
use App\Http\Controllers\Admin\TeacherSalaryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermission\PermissionController;
use App\Http\Controllers\RolePermission\RoleController;
use App\Http\Controllers\RolePermission\RolePermissionController;
use App\Http\Controllers\User\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Roles Routes
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Permissions Routes
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');


    Route::prefix('role-permissions')->name('role-permissions.')->group(function () {
        Route::get('/', [RolePermissionController::class, 'index'])->name('index');
        Route::get('/create', [RolePermissionController::class, 'create'])->name('create');
        Route::post('/', [RolePermissionController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [RolePermissionController::class, 'edit'])->name('edit'); // Add this line
        Route::put('/{role}', [RolePermissionController::class, 'update'])->name('update'); // Add this line
        Route::delete('/{role}', [RolePermissionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::get('/admin/teacher-attendance', [TeacherAttendanceController::class, 'index'])
    ->name('teacher-attendance.index');

    Route::post('/teacher-attendance/fetch', [TeacherAttendanceController::class, 'fetch'])
    ->name('teacher-attendance.fetch');

    Route::get('/teacher-attendance/{teacher}/history', [TeacherAttendanceController::class, 'teacherHistory'])
    ->name('admin.teacher-attendance.history');


});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/admin/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
Route::post('/admin/payments/mark-as-paid/{studentId}', [PaymentController::class, 'markAsPaid'])->name('payments.markAsPaid');

Route::get('/admin/teachers/salaries', [TeacherSalaryController::class, 'index'])->name('salaries.index');
Route::post('/admin/teacher-salaries/pay-all', [TeacherSalaryController::class, 'payAllTeachers'])->name('teacher_salaries.pay_all');
Route::post('/teachers/salaries/store', [TeacherSalaryController::class, 'store'])->name('salaries.store');

Route::get('/admin/teachers/{teacher}/download-pdf', [TeacherController::class, 'downloadPdf'])
    ->name('admin.teachers.download-pdf');


Route::get('/admin/reports/expenses', [ExpenseReportController::class, 'index'])->name('admin.reports.expenses');
Route::prefix('admin')->group(function () {
    Route::get('/reports/student-fees', [StudentFeeReportController::class, 'index'])
         ->name('admin.reports.student-fees');
});
Route::get('/admin/reports/teacher-salaries', [TeacherSalaryReportController::class, 'index'])->name('teacher.salary.report');
Route::get('/admin/reports/bank-balance', [BankBalanceReportController::class, 'index'])->name('bank.balance.report');




Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('school-classes', SchoolClassController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('parents', ParentController::class);
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('syllabus', SyllabusController::class);

    Route::resource('exam-categories', ExamCategoryController::class);
    Route::resource('exams', ExamController::class);
    Route::resource('grades', GradeController::class);

    Route::resource('gradebooks', GradeBookController::class)->only(['index', 'create']);


    Route::get('gradebooks/show-students', function () {
        return redirect()->route('admin.gradebooks.create')->with('error', 'Direct access to this route is not allowed.');
    })->name('gradebooks.showStudents.get');
    // Only allow POST requests for showStudents
    Route::post('gradebooks/show-students', [GradeBookController::class, 'showStudents'])->name('gradebooks.showStudents');
    Route::post('gradebooks/store-marks', [GradeBookController::class, 'storeMarks'])->name('gradebooks.storeMarks');


    Route::resource('student-fees', StudentFeeController::class);
    Route::resource('teacher-salaries', TeacherSalaryController::class);
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::resource('expenses', ExpenseController::class);



});

require __DIR__.'/auth.php';
