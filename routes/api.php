<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\CoursePackageController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\PaymentMethodController;
use App\Http\Controllers\API\PackagePrerequisiteController;
use App\Http\Controllers\API\AccountDetailController;
use App\Http\Controllers\API\ClassesController;
use App\Http\Controllers\API\EnrollController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\ClassStudentController;
use App\Http\Controllers\API\ClassSessionController;
use App\Http\Controllers\API\ScheduleHourController;
use App\Http\Controllers\API\ScheduleController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/** LOGIN REGISTRATION API **/
Route::match(['get','post'],'login', [UserController::class, 'login'])->name('login');
Route::post('register',[UserController::class, 'register']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::patch('update-profile', [UserController::class, 'updateProfile']);

    Route::get('courses', [CourseController::class, 'index']);
    Route::get('packages', [PackageController::class, 'index']);
    Route::get('course-packages', [CoursePackageController::class, 'index']);
    /** Account Details routes **/
    Route::get('account-details', [AccountDetailController::class, 'index']);
    Route::get('account-detail/{id}', [AccountDetailController::class, 'show']);
    Route::post('account-detail/create', [AccountDetailController::class, 'create']);
    Route::patch('account-detail/{id}', [AccountDetailController::class, 'update']);
    Route::delete('account-detail/{id}', [AccountDetailController::class, 'delete']);
    /** Enroll routes **/
    Route::get('enrolls', [EnrollController::class, 'index']);
    Route::get('enroll/{id}', [EnrollController::class, 'show']);
    Route::post('enroll/create', [EnrollController::class, 'create']);
    Route::patch('enroll/{id}', [EnrollController::class, 'update']);
    Route::delete('enroll/{id}', [EnrollController::class, 'delete']);
    /** Cart routes **/
    Route::get('carts', [CartController::class, 'index']);
    Route::get('cart/{id}', [CartController::class, 'show']);
    Route::post('cart/create', [CartController::class, 'create']);
    Route::patch('cart/{id}', [CartController::class, 'update']);
    Route::delete('cart/{id}', [CartController::class, 'delete']);
    /** Transaction routes **/
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transaction/{id}', [TransactionController::class, 'show']);
    Route::post('transaction/create', [TransactionController::class, 'create']);
    Route::patch('transaction/{id}', [TransactionController::class, 'update']);
    Route::delete('transaction/{id}', [TransactionController::class, 'delete']);
    /** ClassStudent routes **/
    Route::get('class-students', [ClassStudentController::class, 'index']);
    Route::get('class-student/{id}', [ClassStudentController::class, 'show']);
    Route::post('class-student/create', [ClassStudentController::class, 'create']);
    Route::patch('class-student/{id}', [ClassStudentController::class, 'update']);
    Route::delete('class-student/{id}', [ClassStudentController::class, 'delete']);

});
Route::group(['middleware' =>['auth:api', 'roleVerified:admin']],function () {
        /** Course routes **/
        Route::get('course/{id}', [CourseController::class, 'show']);
        Route::post('course/create', [CourseController::class, 'create']);
        Route::patch('course/{id}', [CourseController::class, 'update']);
        Route::delete('course/{id}', [CourseController::class, 'delete']);
        /** Package routes **/
        Route::get('package/{id}', [PackageController::class, 'show']);
        Route::post('package/create', [PackageController::class, 'create']);
        Route::patch('package/{id}', [PackageController::class, 'update']);
        Route::delete('package/{id}', [PackageController::class, 'delete']);
        /** CoursePackages routes **/
        Route::post('course-package/create', [CoursePackageController::class, 'create']);
        Route::patch('course-package/{id}', [CoursePackageController::class, 'update']);
        Route::get('course-package/{id}', [CoursePackageController::class, 'show']);
        Route::delete('course-package/{id}', [CoursePackageController::class, 'delete']);
        /** Wallet routes **/
        Route::get('wallets', [WalletController::class, 'index']);
        Route::get('wallet/{id}', [WalletController::class, 'show']);
        Route::post('wallet/create', [WalletController::class, 'create']);
        Route::patch('wallet/{id}', [WalletController::class, 'update']);
        Route::delete('wallet/{id}', [WalletController::class, 'delete']);
        /** Payment method routes **/
        Route::get('payment-methods', [PaymentMethodController::class, 'index']);
        Route::get('payment-method/{id}', [PaymentMethodController::class, 'show']);
        Route::post('payment-method/create', [PaymentMethodController::class, 'create']);
        Route::patch('payment-method/{id}', [PaymentMethodController::class, 'update']);
        Route::delete('payment-method/{id}', [PaymentMethodController::class, 'delete']);
        /** Prerequisites Routes **/
        Route::get('package-prerequisites', [PackagePrerequisiteController::class, 'index']);
        Route::get('package-prerequisite/{id}', [PackagePrerequisiteController::class, 'show']);
        Route::post('package-prerequisite/create', [PackagePrerequisiteController::class, 'create']);
        Route::patch('package-prerequisite/{id}', [PackagePrerequisiteController::class, 'update']);
        Route::delete('package-prerequisite/{id}', [PackagePrerequisiteController::class, 'delete']);
        /** Classes routes **/
        Route::get('classes', [ClassesController::class, 'index']);
        Route::get('classes/{id}', [ClassesController::class, 'show']);
        Route::post('classes/create', [ClassesController::class, 'create']);
        Route::patch('classes/{id}', [ClassesController::class, 'update']);
        Route::delete('classes/{id}', [ClassesController::class, 'delete']);
    }
);

Route::group(['middleware' =>['auth:api', 'roleVerified:teacher']],function () {

        /** Classes routes **/
        Route::get('classes', [ClassesController::class, 'index']);
        Route::get('classes/{id}', [ClassesController::class, 'show']);
        Route::post('classes/create', [ClassesController::class, 'create']);
        Route::patch('classes/{id}', [ClassesController::class, 'update']);
        Route::delete('classes/{id}', [ClassesController::class, 'delete']);

        /** ClassSession routes **/
        Route::get('class-sessions', [ClassSessionController::class, 'index']);
        Route::get('class-session/{id}', [ClassSessionController::class, 'show']);
        Route::post('class-session/create', [ClassSessionController::class, 'create']);
        Route::patch('class-session/{id}', [ClassSessionController::class, 'update']);
        Route::delete('class-session/{id}', [ClassSessionController::class, 'delete']);

        /** Schedule routes **/
        Route::get('schedules', [ScheduleController::class, 'index']);
        Route::get('schedule/{id}', [ScheduleController::class, 'show']);
        Route::post('schedule/create', [ScheduleController::class, 'create']);
        Route::patch('schedule/{id}', [ScheduleController::class, 'update']);
        Route::delete('schedule/{id}', [ScheduleController::class, 'delete']);

        /** ScheduleHour routes **/
        Route::get('schedule-hours', [ScheduleHourController::class, 'index']);
        Route::get('schedule-hour/{id}', [ScheduleHourController::class, 'show']);
        Route::post('schedule-hour/create', [ScheduleHourController::class, 'create']);
        Route::patch('schedule-hour/{id}', [ScheduleHourController::class, 'update']);
        Route::delete('schedule-hour/{id}', [ScheduleHourController::class, 'delete']);

    }
);
