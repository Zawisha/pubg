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

Auth::routes();

Route::post('/646209266:AAGumg3FhYVmLC-JqYoTap1m10tbeAe3wE4/webhook', 'TelegramController@webhook')->name('telegram.webhook');

Route::get('/', 'Controller@index');
Route::get('/js/lang.{lang}.js', 'Controller@langJS')->name('assets.lang');
Route::get('/registration', 'Controller@index');
Route::post('/', 'Controller@indexPost');

Route::post('/api/register', 'AuthController@register');
Route::post('/api/login', 'AuthController@login');
Route::get('/api/user', 'AuthController@user');
Route::post('/api/user/set', 'CabinetController@setNameAndId');
Route::get('/api/payment/game/{game}', 'CabinetController@payForGame');
Route::get('/api/payment/free/{amount}', 'CabinetController@payForFree');
Route::get('/api/payment/un/{amount}', 'CabinetController@payForUn');
Route::get('/api/payment/checkOrder/{orderId}', 'CabinetController@checkPayPal');
Route::get('/api/game/enter/{game}', 'GameController@becomeMember');
Route::get('/api/game/leave/{game}', 'GameController@leaveGame');
Route::get('/api/game/teams/{game}', 'GameController@getTeams');
Route::get('/api/game/mode/{game}/{isSingle}/{team}/{groupIndex}', 'GameController@setMode');
Route::get('/api/game/current_mode/{game}', 'GameController@getMode');
Route::get('/api/games', 'GameController@getGames');
Route::post('/api/withdraw', 'PaymentController@requestWithdraw');
Route::post('/api/bloggers', 'Controller@bloggersRequest');
Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/api/user/avatar', 'CabinetController@uploadAvatar');
Route::post('/payment', 'PaymentController@Payment');
Route::get('/payment', 'PaymentController@Payment');
Route::get('/payment/success', 'PaymentController@PaymentSuccess');
Route::post('/payment/success', 'PaymentController@PaymentSuccess');
Route::get('/payment/fail', 'PaymentController@PaymentFail');
Route::post('/payment/fail', 'PaymentController@PaymentFail');
Route::post('/check_time', 'GameListController@check_time');

Route::post('/get_list_mob', 'GameListController@get_list_mob');
Route::post('/get_list_mob_all', 'GameListController@get_list_mob_all');
Route::post('/show_img_results_func', 'GameListController@show_img_results_func');
Route::post('/delete_show_img_results_func', 'GameListController@delete_show_img_results_func');
//Auth::routes();

Route::prefix('/missioncontrol')
    ->namespace('Auth')
    ->name('admin.')
    ->group(function () {
//        Route::get('/', '\onthetop\Http\Controllers\TasksAdminController@getDashboard')->name('dashboard');
        // Authentication Routes...
        Route::post('/getGameMembers/{game}', '\App\Http\Controllers\AdminController@getGameMembers');
        Route::post('/stat_export', '\App\Http\Controllers\AdminController@sendStatToEmail');
        Route::post('/setGameMembers', '\App\Http\Controllers\AdminController@setGameMembers');
        Route::post('/publishGameMembers', '\App\Http\Controllers\AdminController@publishGameMembers');
        Route::post('/fillTeams', '\App\Http\Controllers\AdminController@fillTeams');
        Route::get('/')->name('dashboard');
        Route::post('/screenshot', '\App\Http\Controllers\AdminController@uploadScreens')->name('screenshot');
        Route::post('/screenshot/parse', '\App\Http\Controllers\AdminController@processParsedData')->name('screenshot.data');
        Route::name('login')->get('login', 'AdminLoginController@showLoginForm');
        Route::name('login')->post('login', 'AdminLoginController@login');
        Route::name('logout')->get('logout', 'AdminLoginController@logout');
        Route::name('logout')->post('logout', 'AdminLoginController@logout');
    });


Route::get('/missioncontrol/loginasuser/{userId}', 'Controller@authAs');
Route::get('/{referalId}', 'Controller@join');
