<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// // Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::post('/sendMessage', [MessageController::class, 'index'])->name('message');
Route::post('/upload', [MessageController::class, 'upload'])->name('upload-file');

Route::get('/video-chat', function () {
    // fetch all users apart from the authenticated user
    $users = User::where('id', '<>', Auth::id())->get();
    return view('video-chat', ['users' => $users]);
});

// Endpoints to call or receive calls.
Route::post('/video/call-user', 'App\Http\Controllers\VideoChatController@callUser');
Route::post('/video/accept-call', 'App\Http\Controllers\VideoChatController@acceptCall');
Auth::routes();
// Route::get('/home', [HomeController::class, 'home'])->name('home');