<?php

use Illuminate\Http\Request;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
    // return $request->user();
// });

Route::group([
    'prefix' => 'bill-pay'
], function () {
    // Basic bill pay without authentication. 
    Route::post('pay-cable', 'PaymentsController@payCableTv');
    Route::post('pay-electricity', 'PaymentsController@payElectricity');
    
    // If user needs to the authenticated, then we can change to use these routes
    // Route::group([
    //   'middleware' => 'bill-pay:api'
    // ], function() {
            // Route::post('pay-dstv', 'PaymentController@payCableTv');
    // });
});
