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

Route::group(['middleware'=>'login'], function () use($router) {
    $router->get('/',['uses' => 'IndexController@index']);
    $router->get('/message_box',['uses' => 'IndexController@messageBox']);
    $router->get('/chat_log',['uses' => 'IndexController@chatLog']);
    $router->get('/userinfo',['uses' => 'UserController@userinfo']);
    $router->post('/add_friend',['uses' => 'UserController@addFriend']);
    $router->get('/find',['uses' => 'GroupController@find']);
    $router->get('/group_members',['uses' => 'GroupController@groupMember']);
    $router->post('/join_group',['uses' => 'GroupController@joinGroup']);
    $router->get('/create_group',['uses' => 'GroupController@createGroup']);
    $router->post('/create_group',['uses' => 'GroupController@createGroup']);
    $router->post('/refuse_friend',['uses' => 'UserController@refuseFriend']);
    $router->post('/update_sign',['uses' => 'UserController@updateSign']);
    $router->get('/loginout',['uses' => 'IndexController@loginOut']);
    $router->get('/chat_record_data',['uses' => 'IndexController@chatRecordData']);

	$router->get('/mobile',['uses' => 'MobileController@index']);
	$router->post('/mobile',['uses' => 'MobileController@index']);
	$router->get('/mobile_log',['uses' => 'MobileController@chatLog']);

});
$router->get('/login',['uses' => 'IndexController@login']);
$router->post('/login',['uses' => 'IndexController@login']);
$router->get('/register',['uses' => 'IndexController@register']);
$router->post('/register',['uses' => 'IndexController@register']);
$router->post('/upload',['uses' => 'IndexController@upload']);
$router->get('/image_code',['uses' => 'IndexController@imageCode']);


