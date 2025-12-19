<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\Tour;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(UserController::class)->group(function () {
    // Trang chi tiết tour, sử dụng implicit model binding
    Route::get('/tours/{tour}', 'tourDetailsForm')->name('tours.show');
});
Route::get('/', function () {
    $tours = Tour::latest()->take(6)->get();
    return view('pages.dashboard', compact('tours'));
})->name('dashboard');


Route::middleware(['guest'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'loginForm')->name('login');
        Route::post('/login', 'loginHandle')->name('login_handle');
        Route::get('/register', 'registerForm')->name('register');
        Route::post('/register', 'registerHandle')->name('register_handle');

        // Quên mật khẩu
        Route::get('/forgot-password', 'forgotPasswordForm')->name('password.forgot');
        Route::post('/forgot-password', 'sendPasswordResetEmail')->name('send.password.email');

        Route::get('/password/reset/{token}', 'resetPasswordForm')->name('password.reset');
        Route::post('/password/reset', [AuthController::class, 'resetPasswordHandle'])->name('password.reset.update');



        // Route xác thực email (signed URL với thời hạn 15 phút)
        Route::get('/email/verify/{user_id}/{hash}', 'verifyEmail')
            ->middleware('signed')
            ->name('verification.verify');

        // Route gửi lại email xác thực cho guest (chưa đăng nhập)
        Route::post('/email/resend-verification', 'resendVerificationEmail')
            ->middleware('throttle:3,1')
            ->name('verification.resend');
    });
});


// Cho phép đặt lại mật khẩu ngay cả khi người dùng đang đăng nhập


Route::middleware(['auth'])->group(function () {
    Route::middleware(['verified'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/logout', 'logout')->name('logout');
        });

        Route::controller(UserController::class)->group(function () {
            Route::get('/profile', 'profileForm')->name('profile');
            Route::put('/profile', 'profileUpdate')->name('profile_update');
            Route::get('/password/change', 'passwordForm')->name('password.change');
            Route::put('/password', 'updatePassword')->name('password.update');
        });
    });


    Route::prefix('admin')->name('admin.')->group(function () {});
});
