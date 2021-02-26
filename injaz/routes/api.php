<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\API\taskController;
use App\Http\Controllers\API\RegisterController;
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

    //show all ongoing tasks
    Route::get('tasks/user/ongoing/{id}', [taskController::class, 'ongoingTask']);

    //show today tasks
    Route::get('tasks/user/today/{id}', [taskController::class, 'showTodayTask']);

    //show tomorrow tasks
    Route::get('tasks/user/tomorrow/{id}', [taskController::class, 'showTomorrowTask']);

    //convert task to complete
    Route::put('tasks/user/complete/{id}', [taskController::class, 'convertToCompletedTask']);

});
