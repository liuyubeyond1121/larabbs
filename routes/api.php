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

put 替换某个资源，需提供完整的资源信息
patch 部分修改资源，提供部分资源信息
|
*/



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {
    $api->get('version', function() {
        return response('this is version v1');
    });
    $api->put('version', function() {
        return response('this is version v1 put');
    });
    $api->group(['namespace'=>'App\Http\Controllers\Api','middleware' => ['serializer:array','bindings']], function($api){

        //用户登录
        $api->post('authorizations', 'AuthorizationsController@store')->name('api.authorizations.store');

        // 刷新token
        $api->put('authorizations/current', 'AuthorizationsController@update')->name('api.authorizations.update');

        // 删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')->name('api.authorizations.destroy');

        $api->group([
            'middleware' => 'api.throttle',
            'limit' => config('api.rate_limits.access.limit'),
            'expires' => config('api.rate_limits.access.expires'),
        ], function ($api) {
            // 游客可以访问的接口
            $api->get('categories', 'CategoriesController@index')->name('api.categories.index');
            //话题列表
            $api->get('topics', 'TopicsController@index')->name('api.topics.index');


            // 需要 token 验证的接口
            $api->group(['middleware' => 'api.auth'], function($api) {
                // 当前登录用户信息
                $api->get('user', 'UsersController@me')->name('api.user.show');

                // 编辑登录用户信息
                $api->patch('user', 'UsersController@update')->name('api.user.update');

                // 图片资源
                $api->post('images', 'ImagesController@store')->name('api.images.store');

                // 发布话题
                $api->post('topics', 'TopicsController@store')->name('api.topics.store');

                //修改话题
                $api->patch('topics/{topic}', 'TopicsController@update')->name('api.topics.update');

                //删除话题
                $api->delete('topics/{topic}', 'TopicsController@destroy')->name('api.topics.destroy');
            });
        });
    });
});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});

// 第三方登录
// $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
//     ->name('api.socials.authorizations.store');
// 登录
