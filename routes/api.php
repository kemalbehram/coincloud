<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthorizationsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;

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

Route::prefix('v1')
    //->namespace('Api') // laravel 8 api 需要去掉这个
    ->name('api.v1.')
    ->group(function () {

        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
                Route::post('authorizations', [AuthorizationsController::class, 'store']); // 登录
                Route::put('authorizations/current', [AuthorizationsController::class, 'update']);// 刷新token
                Route::delete('authorizations/current', [AuthorizationsController::class, 'destroy']);// 删除token
            });

        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {
                // 游客可以访问的接口
                Route::resource('announcement', 'AnnouncementController'); // 公告
                Route::resource('article', 'ArticleController'); // 文章系统
                Route::get('index', 'IndexController@index'); // APP 首页
                Route::get('version', 'VersionController@index'); // 检测最新版本
                Route::post('checkversion', 'VersionController@check'); // 比较版本号

//                Route::resource('product', ProductController::class); // 产品资源
                Route::get('product', [ProductController::class, 'index']); // 产品列表
                Route::get('product/{product}', [ProductController::class, 'show']); // 产品详情

                // 登录后可以访问的接口
                Route::middleware('auth:api')->group(function () {
                    Route::get('user', [UserController::class, 'me']); // 当前登录用户信息

                    Route::get('my', 'UserController@my'); // 我的
                    Route::get('team', 'UserController@team'); // 我的团队
                    Route::post('avatar', 'UserController@avatar'); // 修改用户头像
                    Route::post('verify', 'UserController@verify'); // 用户实名认证
                    Route::post('reset', 'UserController@resetPassword'); // 重设密码
                    Route::get('invite', 'UserController@invite'); // 邀请码

                    Route::get('mypower', 'UserController@mypower'); // 算力管理
                    Route::get('myorder', 'UserController@myorder'); // 我的订单
                    Route::get('account', 'UserController@account'); // 我的资产
                    Route::get('walletlog', 'UserController@walletLog'); // 我的资产流水
                    Route::get('rewardwalletlog', 'UserController@RewardwalletLog'); // 奖励算力资产流水

                    Route::resource('withdraw', 'WithdrawController'); // 提币
                    Route::get('mycoin', 'WithdrawController@my'); // 我的提币
                    Route::resource('withdrawmoney', 'WithdrawMoneyController'); // 提现
                    Route::get('mymoney', 'WithdrawMoneyController@my'); // 我的提现
                    Route::resource('bankcard', 'BankcardController'); // 银行卡
                    Route::resource('feedback', 'FeedbackController'); // 问题反馈

                    Route::resource('order', OrderController::class); // 订单
                    Route::post('checkorder', [OrderController::class, 'check']); // 预览检测订单
                    Route::patch('orders/{order}', [OrderController::class, 'update']); // 更新订单支付凭证

                    Route::resource('recharge', 'RechargeController'); // 充值
                    Route::get('myrecharge', 'RechargeController@my'); // 我的充值页面
                    Route::get('powerlog', 'RechargeController@powerlog'); // 算力封装记录
                    Route::get('companyborrow', 'RechargeController@companyBorrow'); // 公司代充记录

                    Route::resource('lend', 'LendController'); // 出借
                    Route::get('mylend', 'LendController@my'); // 我的出借页面
                });
            });
    });
