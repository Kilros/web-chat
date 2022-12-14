<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FileController;
use PhpParser\Node\Expr\Yield_;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('admin');
});
Route::get('/register', [UserController::class, 'registration'])->name('register');
Route::post('/register', [UserController::class, 'customRegistration']);
Route::get('/login', [UserController::class, 'index'])->name('login');
Route::post('/login', [UserController::class, 'customLogin']);
Route::get('/logout', [UserController::class, 'signOut'])->name('logout');
Route::get('/status', [UserController::class, 'userOnlineStatus']);
// Route::get('/admin', [UserController::class, 'admin']);
Route::prefix('admin')->middleware('verifyuser')->group(function (){
    Route::get('/', [ChatController::class, 'index'])->name('user');
    Route::post('/', [ChatController::class, 'userAction']);
    // Route::get('/chat', [ChatController::class, 'chat'])->name('chat');
    Route::post('/chat', [ChatController::class, 'chatAction']);
    // Route::post('/sent', [ChatController::class, 'sent']);
    // Route::post('/get', [ChatController::class, 'get']);
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('changePassword');
    Route::post('/change-profile', [UserController::class, 'changeProfile'])->name('changeProfile');
});
Route::get('/appchat/file/{filename}', [FileController::class, 'readFile']);
