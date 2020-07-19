<?php

use Illuminate\Http\Request;

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
Route::get('/me','AuthJWT\AuthController@me');
Route::post('/register', 'AuthJWT\AuthController@register');
Route::post('/login', 'AuthJWT\AuthController@login');
Route::post('/logout', 'AuthJWT\AuthController@logout');
Route::post('/edit-profile','AuthJWT\AuthController@EditProfile');
Route::get('/jenis-zakat','SettingController@jenis_zakat');
Route::get('/cek-status-pembayaran','BayarZakatController@cek_pembayaran');


Route::middleware(['auth:api'])->group(function () {
Route::get('/role-management','Role\RoleManagementController@index');
Route::get('/setting-aplikasi','SettingController@index');
Route::post('/setting-aplikasi','SettingController@store');
Route::post('/role-management','Role\RoleManagementController@store');
Route::get('/role-management/{id}/edit','Role\RoleManagementController@edit');
Route::resource('masterdata', 'Masterdata\MasterdataController');
Route::resource('menu', 'Menu\MenuController');
Route::resource('users', 'Users\UsersController');
Route::resource('penyalur-zakat', 'Penyalur\PenyalurController');
Route::resource('mustahik', 'Mustahik\MustahikController');
Route::resource('dana-zakat', 'DanaZakat\DanaZakatController');
});
Route::resource('pembayaran-zakat', 'BayarZakatController');
