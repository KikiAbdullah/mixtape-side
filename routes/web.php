<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// ===========================================
// PUBLIC PORTAL ROUTES (GUEST ACCESS)
// ===========================================
Route::get('/', 'PublicHomeController@index')->name('homepage');
Route::get('/bands', 'PublicBandController@index')->name('public.band.index');
Route::get('/bands/{slug}', 'PublicBandController@show')->name('public.band.show');
Route::get('/bands/{band_slug}/{release_slug}', 'PublicReleaseController@show')->name('public.release.show');
Route::get('/labels/{slug}', 'PublicLabelController@show')->name('public.label.show');
Route::get('/discovery', 'PublicDiscoveryController@index')->name('public.discovery');
Route::get('/gigs', 'PublicGigController@index')->name('public.gig.index');
Route::get('/gigs/{slug}', 'PublicGigController@show')->name('public.gig.show');
Route::get('/releases', 'PublicReleaseController@index')->name('public.release.index');
Route::redirect('/about', '/');
Route::redirect('/events', '/gigs');
Route::redirect('/contacts', '/discovery');

Auth::routes(['verify' => true]);
Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['two_factor']], function () {
        Route::get('/dashboard', 'AppController@index')->name('siteurl');

        // ===========================================
        // USER SETUP (existing)
        // ===========================================
        Route::group(['prefix' => 'user-setup', 'as' => 'user-setup.'], function () {
            // PERMISSIONS
            Route::group(['prefix' => 'permission', 'as' => 'permission.', 'middleware' => 'can:permissions_view'], function () {
                Route::get('get-data', 'PermissionController@ajaxData')->name('get-data');
            });
            Route::resource('permission', 'PermissionController')->middleware('can:permissions_view');

            // ROLES
            Route::group(['prefix' => 'role', 'as' => 'role.', 'middleware' => 'can:roles_view'], function () {});
            Route::resource('role', 'RoleController')->middleware('can:roles_view');

            // USERS
            Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'can:users_view'], function () {
                Route::get('get-data', 'UserController@ajaxData')->name('get-data');
            });
            Route::resource('user', 'UserController')->middleware('can:users_view');
        });

        // ===========================================
        // DEBUG / UTILITIES
        // ===========================================
        Route::group(['prefix' => 'debug', 'as' => 'debug.'], function () {
            Route::get('log-viewer', 'LogViewerController@index')->name('log-viewer.index');
        });
        Route::get('get-button-option', 'AjaxController@getButtonOption')->name('get.button-option');
    });

    // Two-Factor Routes
    Route::get('2fa', 'TwoFactorController@showTwoFactorForm');
    Route::post('2fa', 'TwoFactorController@verifyTwoFactor')->name('verifyTwoFactor');
});
