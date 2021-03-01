<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\API\taskController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\mailController;
use App\Http\Controllers\API\BassController as BassController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\ForgotPasswordController;

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

//Route::post('/password/email', 'API\ForgotPasswordController@sendResetLinkEmail');
//Route::post('/password/reset', 'API\ResetPasswordController@reset');

Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);

Route::middleware('auth:api')->group( function() {
    Route::resource('task', 'API\taskController');

    //create tomorrow tasks
    Route::post('tasks/create_tomorrow', [taskController::class, 'createTomorrowTask']);

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
