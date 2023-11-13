<?php

use App\Http\Controllers\ClipController;
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
    return redirect('/dashboard');
});

Route::any('/clip/{id}/verify-password', [ClipController::class,'verifyPassword'])->name('clip.verify-password');

Route::get('/c/{slug}', function ($slug) {
    // get the clip from the database
    $clip = App\Models\Clip::where('slug', $slug)->first();
    if(!$clip) {
        // return 404
        abort(404);
    }
    if($clip->expires_at && $clip->expires_at < now()) {
        // return 404
        abort(404);
    }
    if($clip->password) {
        // prompt for password
        return view('password', ['clip' => $clip]);
    }
    else {
        return redirect($clip->url);
    }
})->name('clip');
