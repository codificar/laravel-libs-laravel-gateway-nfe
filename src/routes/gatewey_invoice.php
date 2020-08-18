<?php
// Route::group(['namespace' => 'api\v1', 'prefix' => '/admin/payment', 'middleware' => ['auth.admin_api', 'cors']], function () {  
//     Route::post('/save-card', array('as' => 'adminSaveCard', 'uses' => 'PaymentController@adminSavePaymentCard'));
// });

Route::group(['namespace' => 'api\v1', 'prefix' => '/api/v1/gatewey/invoice'], function () {  
    Route::post('/', array('as' => 'adminSaveCard', 'uses' => 'GatewayNFEController@WeebHookStore'));
});

