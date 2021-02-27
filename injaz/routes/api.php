<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\API\taskController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\mailController;
use App\Http\Controllers\API\BassController as BassController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group( function() {
    Route::resource('task', 'API\taskController');

    //Show All Tasks for the User
    Route::get('tasks/user/all', [taskController::class, 'userTask']);

    //show spicific task
    Route::get('tasks/user/{id}', [taskController::class, 'showSpecificTask']);

    //show today tasks
    Route::get('tasks/user/today/{id}', [taskController::class, 'showTodayTask']);

    //show tomorrow tasks
    Route::get('tasks/user/tomorrow/{id}', [taskController::class, 'showTomorrowTask']);

    //convert task to complete
    Route::put('tasks/user/complete/{id}', [taskController::class, 'convertToCompletedTask']);

    //transfer task from today to tomorrow
    Route::put('tasks/user/trantotom/{id}', [taskController::class, 'transToTomorrow']);
});

    //to sent email
    Route::get('/send-email',  [mailController::class, 'sendEmail']);

    //to sent email to password reset link! -  not yet
   // Route::get('/password/reset',  [mailController::class, 'sendEmail']);
