<?php

use Illuminate\Http\Request;

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

Auth::routes();

Route::get('/{vue_capture?}', function (Request $request) {

    if (!$request->user()) {
        return redirect('/login');
    }

    if (!$request->ajax() ) {
        return view('home');
    }    

})->where('vue_capture', '.*');