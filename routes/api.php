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
Route::match(['get','post'],'login', [UserController::class, 'login'])->name('login');
Route::post('register',[UserController::class, 'register']);
Route::group(['middleware' => ['auth:api']], function () {
    /** Course routes **/
    Route::get('courses', [CourseController::class, 'index']);

    /** Package routes **/
    Route::get('packages', [PackageController::class, 'index']);


    /** CoursePackages routes **/
    Route::get('coursePackages', [CoursePackageController::class, 'index']);
});
Route::group(
    ['middleware' =>['auth:api', 'roleVerified:admin']],
    function () {
Route::get('course/{id}', [CourseController::class, 'show']);
Route::post('course/create', [CourseController::class, 'create']);
Route::patch('course/{id}', [CourseController::class, 'update']);
Route::delete('course/{id}', [CourseController::class, 'delete']);

Route::get('package/{id}', [PackageController::class, 'show']);
Route::post('package/create', [PackageController::class, 'create']);
Route::patch('package/{id}', [PackageController::class, 'update']);
Route::delete('package/{id}', [PackageController::class, 'delete']);

Route::post('coursePackage/create', [CoursePackageController::class, 'create']);
Route::patch('coursePackage/{id}', [CoursePackageController::class, 'update']);
Route::get('coursePackage/{id}', [CoursePackageController::class, 'show']);
Route::delete('coursePackage/{id}', [CoursePackageController::class, 'delete']);
    }
);
