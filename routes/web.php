<?php

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

use App\Mail\SendCode;

Route::get('/test',function(){
    $user = \App\User::findOrFail(1);
    $user->password = \Hash::make(123456);
    $user->save();

});
Route::get('/mencoba',function(){
    $data= 'mencoba';
    return new SendCode($data);

});
Route::get('/laporan-pembayaran','CetakController@pembayaran');
Route::get('/laporan-pembagian','CetakController@pembagian');
Route::get('/{any}',function(){
    return view('index');
})->where('any', '.*');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

