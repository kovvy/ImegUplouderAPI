<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['prefix' => 'api/v{api_version}'], function()
{
	// route for gettin token by user_id
	Route::post('signup', ['uses' =>'ApiController@signUp']);

	// route for getting list of user's earlier resized images
	Route::get('images', ['middleware' => 'auth', 'uses' =>'ApiController@getListOfImages']);

	// route for upload and resize new image
	Route::post('resize-image', ['middleware' => ['auth', 'validateParameters'], 'uses' =>'ApiController@resizeNewImage']);

	// route for resize earlier uploaded image
	Route::patch('resize-image/{image_id}', ['middleware' => ['auth', 'validateParameters'], 'uses' =>'ApiController@resizeOldImage']);


});

