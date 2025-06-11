<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ClassRoomController;
use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Check if user is logged in and is admin
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Check if user is admin and redirect to admin dashboard
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('teachers', TeacherController::class);
    Route::resource('students', StudentController::class);
    Route::resource('classrooms', ClassRoomController::class)->parameters([
        'classrooms' => 'classRoom'
    ]);
    Route::resource('payments', PaymentController::class);
    
    // Additional student actions
    Route::post('students/{student}/assign-class', [StudentController::class, 'assignToClass'])->name('students.assignToClass');
    Route::delete('students/{student}/classes/{classRoom}', [StudentController::class, 'removeFromClass'])->name('students.removeFromClass');
    
    // Additional classroom actions
    Route::post('classrooms/{classRoom}/add-student', [ClassRoomController::class, 'addStudent'])->name('classrooms.addStudent');
    Route::delete('classrooms/{classRoom}/students/{student}', [ClassRoomController::class, 'removeStudent'])->name('classrooms.removeStudent');
    
    // Additional payment actions
    Route::patch('payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::patch('payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', function() {
        // If admin tries to access profile, redirect to admin dashboard
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return app(ProfileController::class)->edit(request());
    })->name('profile.edit');
    
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
