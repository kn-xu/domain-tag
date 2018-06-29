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

Route::any('{path?}', function () {
    return view('welcome');
})->where("all", "^((?!api).)*");

Route::prefix('api/v1')->group(function () {
    Route::get('domains', "DomainsController@get");
    Route::get('domains/total', "DomainsController@total");
    Route::get('domains/{domainId}/description', "DomainsController@description");

    Route::post('domains', "DomainsController@create");
    Route::post('validate', "DomainsController@validateDomain");
});
