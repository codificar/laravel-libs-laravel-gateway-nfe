<?php
//Admin
Route::group(['prefix' => '/admin', 'middleware' => 'auth.admin'], function(){	
Route::group(['prefix' => '/admin'], function(){	
    Route::group(['prefix' => '/issuer/company'], function(){
         //Company      
         Route::get('/create', array('as' => 'issuerCreateProviderCompany', 'uses' => 'ProviderCompanyController@issuerCreate'));
         Route::post('/store', array('as' => 'issuerCompanyStore', 'uses' => 'ProviderCompanyController@issuerStore'));                 
         Route::post('/update', array('as' => 'adminCompanyUpdate', 'uses' => 'ProviderCompanyController@issuerUpdate')); 
 
         //Certifie
         Route::post('/certified', array('as' => 'issuerSetCompanyCertifie', 'uses' => 'ProviderCompanyController@setIssuerCompanyCertifie'));           
         Route::post('/login', array('as' => 'issuerGetAuthType', 'uses' => 'ProviderCompanyController@issuerAuthLogin'));    
    });
    	
    Route::group(['prefix' => '/provider/company'], function(){
        //Company
        Route::get('gateway/{id}', array('as' => 'adminGetProviderCompany', 'uses' => 'ProviderCompanyController@getProviderCompanyOnGateway'));
        Route::get('/{id}', array('as' => 'adminGetProviderCompany', 'uses' => 'ProviderCompanyController@getProviderCompany'));
        Route::get('/create/{id}', array('as' => 'adminCreateProviderCompany', 'uses' => 'ProviderCompanyController@create'));
        Route::post('/store', array('as' => 'adminCompanyStore', 'uses' => 'ProviderCompanyController@store'));      
        Route::post('/update', array('as' => 'adminCompanyUpdate', 'uses' => 'ProviderCompanyController@update'));

        //Certifie
        Route::post('/certified', array('as' => 'adminSetCompanyCertifie', 'uses' => 'ProviderCompanyController@setCompanyCertifie'));           
        Route::post('/auth', array('as' => 'adminGetAuthType', 'uses' => 'ProviderCompanyController@getAuthType'));    
        Route::post('/login', array('as' => 'adminGetAuthType', 'uses' => 'ProviderCompanyController@authLogin'));       

        //NFE
        Route::post('/nfe/generate', array('as' => 'adminSetCompanyCertifie', 'uses' => 'ProviderCompanyController@generateNfe'));  
        Route::post('/nfe', array('as' => 'getNfe', 'uses' => 'ProviderCompanyController@getNfe'));
    });	
});
});

//Provider API
Route::group(['prefix' => '/api/v1/provider/company', 'middleware' => 'auth.provider_api:api'], function () {
    Route::get('/{id}', array('as' => 'adminGetProviderCompany', 'uses' => 'ProviderCompanyController@getProviderCompany'));   
    Route::post('/store/address', array('as' => 'adminCompanyStore', 'uses' => 'ProviderCompanyController@storeAddress'));
    Route::post('/store/info', array('as' => 'adminCompanyStore', 'uses' => 'ProviderCompanyController@storeInfo'));
    Route::post('/update', array('as' => 'adminCompanyUpdate', 'uses' => 'ProviderCompanyController@update'));

    Route::post('/auth/certified', array('as' => 'adminSetCompanyCertifie', 'uses' => 'ProviderCompanyController@setCompanyCertifie'));
    Route::post('/auth/login', array('as' => 'adminGetAuthType', 'uses' => 'ProviderCompanyController@authLogin'));  
});	