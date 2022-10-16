<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

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

//Admin routes
Route::namespace('Admin')->prefix('admin')->group(function () {
    Route::post('login', 'AuthController@Login');

    //Admin auth routes
    Route::middleware(['auth:api-admin'])->group(function () {
        //auth
        Route::get('me', 'AuthController@me');
        Route::post('logout', 'AuthController@logout');

        Route::middleware('adminPermission')->group(function () {
            Route::apiResource('performance-reports', 'PerformanceReportController')->only(['index', 'update', 'show']);
            Route::put('change-claim-status', 'PerformanceReportController@updateClaimStatus');
            Route::put('change-active-2-tier', 'PerformanceReportController@updateActive2Tier');
            Route::apiResource('tokens', 'TokenController')->only(['show', 'store', 'update', 'delete']);
            Route::apiResource('projects', 'ProjectController')->only(['store', 'update']);
            Route::apiResources([
                'affiliators' => 'AffiliatorController',
                'project-owners' => 'ProjectOwnerController',
                'networks' => 'NetworkController',
                'sponsors' => 'SponsorController'
            ]);
            Route::get('admin-dashboard', 'AdminDashboardController@dashboard');
        });

        Route::middleware('ownerPermission')->group(function () {
            Route::get('owner-dashboard', 'OwnerDashboardController@dashboard');
            Route::get('owner-dashboard-detail/{id}', 'OwnerDashboardController@dashboardDetail');
        });

        Route::get('transaction-history-of-affiliator', 'PerformanceReportController@transactionHistoryOfAffiliator');

        //upload file
        Route::post('upload-file', 'FileController@upload');

        //export 
        Route::post('export-performance-report', 'PerformanceReportController@exportData');

        Route::apiResource('tokens', 'TokenController')->only('index');
        Route::apiResource('projects', 'ProjectController')->only(['index', 'show']);
        Route::get('countries', 'CountryController@index');
        Route::get('affiliators/get-projects/{id}', 'ProjectController@getProjectsOfAffiliator');
        Route::get('get-project-iframe', 'ProjectController@generateIframe');
        Route::apiResource('faqs', 'FaqController')->only(['index', 'show', 'store', 'update']);
    });
});

//User routes
Route::namespace('User')->middleware('language')->prefix('user')->group(function () {
    // Route::post('login', 'AuthController@Login');
    Route::post('login-social-media', 'AuthController@LoginSocialMedia');

    Route::post('sponsors', 'SponsorController@store');

    Route::get('upcoming-projects', 'ProjectController@upcomingProjects');

    //User auth routes
    Route::middleware(['auth:api-user'])->group(function () {
        //auth
        Route::get('me', 'AuthController@me');
        Route::put('update-me', 'AuthController@updateMe');
        Route::post('check-wallet-address', 'AuthController@checkWalletAddress');
        Route::post('logout', 'AuthController@logout');

        //project
        Route::apiResource('projects', 'ProjectController')->only(['index', 'show']);
        Route::post('join-projects', 'ProjectController@joinProject');
        Route::get('my-projects', 'ProjectController@myProjects');

        Route::get('countries', 'CountryController@index');
        Route::get('my-performance-reports', 'PerformanceReportController@myPerformanceReport');

        Route::get('get-project-iframe', 'ProjectController@generateIframe');
        Route::post('claim-request', 'ClaimRequestController@claim');
        Route::post('faqs', "FaqController@store");
        Route::get('tier2-performance-reports', 'ProjectController@getTier2PerformanceReports');

        Route::get('claim-request-history', 'ClaimRequestController@myHistory');
        Route::get('project-transaction-history', 'PerformanceReportController@transactionHistory');
    });
    //claim transactions
    // Route::post('claim-request', 'ClaimRequestController@claim')->middleware(['throttle:onePerMin']);
});
