<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\CoursePackageController;


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
Route::post('login', [UserController::class, 'login']);
Route::post('register',[UserController::class, 'register']);
Route::group(['middleware' => ['auth:api']], function () {
    /** Course routes **/
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('course/{id}', [CourseController::class, 'show']);
    Route::post('course/create', [CourseController::class, 'create']);
    Route::patch('course/{id}', [CourseController::class, 'update']);
    Route::delete('course/{id}', [CourseController::class, 'delete']);

    /** Package routes **/
    Route::get('packages', [PackageController::class, 'index']);
    Route::get('package/{id}', [PackageController::class, 'show']);
    Route::post('package/create', [PackageController::class, 'create']);
    Route::patch('package/{id}', [PackageController::class, 'update']);
    Route::delete('package/{id}', [PackageController::class, 'delete']);

    /** CoursePackages routes **/
    Route::get('coursePackage/create', [CoursePackageController::class, 'create'])->name('createPackage');
    Route::get('coursePackage/update', [CoursePackageController::class, 'update'])->name('updatePackage');

    Route::get('coursePackage/{id}', [CoursePackageController::class, 'show']);
    Route::delete('coursePackage/{id}', [CoursePackageController::class, 'delete']);

});

