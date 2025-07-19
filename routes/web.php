<?php

use App\Http\Controllers\ClipController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Welcome page route
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('welcome');
})->name('welcome');

// Documentation route
Route::get('/docs', function () {
    return redirect('/docs/index.html');
})->name('docs');

// Authentication routes
Route::get('/login', [WebController::class, 'showLogin'])->name('login');
Route::post('/login', [WebController::class, 'login'])->name('login.post');

// Registration routes (only if enabled)
if (config('app.registration_enabled')) {
    Route::get('/register', [WebController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebController::class, 'register'])->name('register.post');
}

Route::post('/logout', [WebController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');

    // Clip management routes
    Route::get('/clips/create', [WebController::class, 'showCreateClip'])->name('clips.create');
    Route::post('/clips', [WebController::class, 'createClip'])->name('clips.store');
    Route::get('/clips/{id}/edit', [WebController::class, 'showEditClip'])->name('clips.edit');
    Route::put('/clips/{id}', [WebController::class, 'updateClip'])->name('clips.update');
    Route::delete('/clips/{id}', [WebController::class, 'deleteClip'])->name('clips.destroy');
});

// Public clip access routes
Route::any('/clip/{id}/verify-password', [ClipController::class,'verifyPassword'])->name('clip.verify-password');

Route::get('/{slug}', function ($slug) {
    // Skip these routes to avoid conflicts
    if (in_array($slug, ['login', 'register', 'dashboard', 'clips', 'logout', 'clip'])) {
        abort(404);
    }

    // get the clip from the database
    $clip = App\Models\Clip::where('slug', $slug)->first();
    if(!$clip) {
        // return custom 404 with the slug
        return response()->view('errors.clip-404', ['slug' => $slug], 404);
    }
    if($clip->expires_at && $clip->expires_at < now()) {
        // return custom 404 for expired clips
        return response()->view('errors.clip-404', ['slug' => $slug, 'expired' => true], 404);
    }
    if($clip->password) {
        // prompt for password
        return view('password', ['clip' => $clip]);
    }
    else {
        return redirect($clip->url);
    }
})->name('clip');
