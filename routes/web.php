<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskTaskController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('/auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/error404', function() {
    return view('error404');
})->middleware(['auth', 'verified'])->name('error404');

Route::get('/error500', function() {
    return view('error500');
})->middleware(['auth', 'verified'])->name('error500');

Route::post('/tasks/{task}/complete',
 [TaskController::class, 'complete'])->name('tasks.complete');

Route::get('/tasks/{task}/tasks/create',
 [TaskController::class, 'createChildTask'])->name('tasks.tasks.create');

 Route::post('/posts/{post}/comments',
  [PostController::class, 'storeComment'])->name('posts.comments.store');

 Route::post('/posts/{post}/complete',
  [PostController::class, 'complete'])->name('posts.complete');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/tasks', TaskController::class);
    Route::resource('/tasks/task', TaskTaskController::class);
    Route::resource('/posts', PostController::class);
    Route::resource('/projects', ProjectController::class);
    Route::get('/home', function () {
        return view('welcome');
    })->name('home');
    Route::get('about', function () {
        return view('welcome');
    })->name('about');
});

require __DIR__.'/auth.php';
