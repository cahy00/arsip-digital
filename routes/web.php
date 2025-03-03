<?php

use Filament\Pages\Dashboard;
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
    return view('welcome');
});

Route::middleware(['auth', 'role:pimpinan'])->group(function(){
//    Route::get('/admin/surat-disposisi', \App\Filament\Resources\LetterInResource\Pages\ListLetterIns::class);
    // Route::get('/admin', function(){
    //     return Dashboard::class;
    // });
});
