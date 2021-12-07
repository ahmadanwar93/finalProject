<?php

use App\Http\Controllers\customerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user', [customerController::class, 'user']);
Route::get('message', [customerController::class, 'message']);

// Ching Session
Route::post('/login', [ApiController::class, 'login'])->name("api.login");

// aku punya
// Route::get('/showcategory/{id}', [ApiController::class, 'showCategory'])->name("api.show");
// Route::get('/showtask/{id}', [ApiController::class, 'showTask'])->name("api.showtask");
Route::get('/scoreboard', [ApiController::class, 'scoreboard'])->name("api.scoreboard");
Route::get('/important', [ApiController::class, 'importantTask'])->name("api.importanttask");
Route::get('/account', [ApiController::class, 'getAccount'])->name("api.getaccount");

// test api
// Route::get('/retrieveall/{id}', [ApiController::class, 'retrieveAll'])->name("api.retrieveall");
// test api




// aku try bawah
// Route::get('/retrieveall', [ApiController::class, 'retrieveAll'])->name("api.retrieveall");
// aku try atas
// Route::post('/storecategory/{id}', [ApiController::class, 'createCategory'])->name("api.createcategory");
// Route::post('/storetask/{id}', [ApiController::class, 'createTask'])->name("api.createtask");
// Route::put('/delete/{id}', [ApiController::class, 'updateDelete'])->name("api.updatedelete");
// Route::put('/update/{id}', [ApiController::class, 'updateCompleted'])->name("api.updatecompleted");
// Route::put('/important/{id}', [ApiController::class, 'updateImportant'])->name("api.updateimportant");


//aku try cros
// Route::get('/retrieveall/{id}', [ApiController::class, 'retrieveAll'])->name("api.retrieveall");
// Route::get('/showcategory/{id}', [ApiController::class, 'showCategory'])->name("api.show");
// Route::get('/showtask/{id}', [ApiController::class, 'showTask'])->name("api.showtask");
// Route::post('/storetask/{id}', [ApiController::class, 'createTask'])->name("api.createtask");
// Route::put('/important/{id}', [ApiController::class, 'updateImportant'])->name("api.updateimportant");
// Route::put('/delete/{id}', [ApiController::class, 'updateDelete'])->name("api.updatedelete");
// Route::put('/update/{id}', [ApiController::class, 'updateCompleted'])->name("api.updatecompleted");
// Route::post('/storecategory/{id}', [ApiController::class, 'createCategory'])->name("api.createcategory");



// try bawah
Route::post('/register', [ApiController::class, 'registerNewUser'])->name("api.register");
// try atas

Route::group(
    //     [
    //     'middleware' => 'auth.jwt',
    // ], 
    [
        'middleware' => 'api',
    ],
    function () {

        // Route::post('/dashboard', [ApiController::class, 'dashboard']);
        // Route::post('/users', [ApiController::class, 'users']);
        Route::get('/getauthenticateduser', [ApiController::class, 'getAuthenticatedUser']);
        Route::post('/logout', [ApiController::class, 'logout']);

        // test api
        Route::get('/retrieveall/{id}', [ApiController::class, 'retrieveAll'])->name("api.retrieveall");
        Route::get('/showcategory/{id}', [ApiController::class, 'showCategory'])->name("api.show");
        Route::get('/showtask/{id}', [ApiController::class, 'showTask'])->name("api.showtask");
        Route::post('/storetask/{id}', [ApiController::class, 'createTask'])->name("api.createtask");
        Route::put('/important/{id}', [ApiController::class, 'updateImportant'])->name("api.updateimportant");
        Route::put('/delete/{id}', [ApiController::class, 'updateDelete'])->name("api.updatedelete");
        Route::put('/update/{id}', [ApiController::class, 'updateCompleted'])->name("api.updatecompleted");
        Route::post('/storecategory/{id}', [ApiController::class, 'createCategory'])->name("api.createcategory");
    }
);
