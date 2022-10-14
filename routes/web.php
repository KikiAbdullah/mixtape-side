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
Auth::routes();
Route::group(['middleware' => ['auth']], function ()
{
        Route::group(['middleware' => ['two_factor']], function ()
        {
            Route::get('/', 'AppController@index')->name('siteurl');

            Route::group(['prefix'=>'user-setup', 'as'=>'user-setup.'], function(){
                Route::middleware('can:view_users')->resource('user', 'UserController');
            });
            Route::get('/listuser', 'UserController@ajaxData')->name('get.user')->middleware('can:view_users');

            Route::get('/permission', 'AppController@permission')->name('permission')->middleware('can:view_permissions');
            Route::get('/permission-list', 'AppController@permissionlist')->name('permission.list');
            Route::get('/role', 'AppController@role')->name('role')->middleware('can:view_roles');
            Route::post('/getroles', 'AppController@getroles')->name('get.roles');
            Route::post('/addroles', 'AppController@saverole')->name('add.roles')->middleware('can:edit_roles');
            Route::delete('/deleteroles', 'AppController@deleteroles')->name('delete.roles')->middleware('can:edit_roles');
            Route::post('/getmenuoptionroles', 'AppController@menuoptionroles')->name('get.roles.menu');
            Route::post('/getlinesroles', 'AppController@lineroles')->name('get.roles.line');
            Route::post('/gethakakses', 'AppController@hakakses')->name('get.hakakses');
            Route::post('/gethakakses2', 'AppController@hakakses2')->name('get.hakakses2');
            Route::post('/addhakakses', 'AppController@addhakakses')->name('add.hakakses')->middleware('can:edit_roles');
            Route::post('/removehakakses', 'AppController@removehakakses')->name('remove.hakakses')->middleware('can:edit_roles');

            //others
            Route::get('get-button-option','AjaxController@getButtonOption')->name('get.button-option');
            Route::get('set-dark-theme','AppController@toggletheme')->name('toggle.theme');
            Route::post('changepassword','AppController@changepassword')->name('changepassword');
        });
        
    Route::get('2fa', 'TwoFactorController@showTwoFactorForm');
    Route::post('2fa', 'TwoFactorController@verifyTwoFactor')->name('verifyTwoFactor');
});
