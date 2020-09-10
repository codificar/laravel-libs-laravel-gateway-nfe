<?php
//Admin
Route::group(['middleware' => 'auth.admin'], function(){	
Route::group(['prefix' => '/admin', 'namespace' => 'Codificar\GatewayNfe\Http\Controllers'], function(){	
    Route::group(['prefix' => '/issuer/company'], function(){
         //Company      
         Route::get('/create', array('as' => 'issuerCreateProviderCompany', 'uses' => 'IssuerCompanyController@create'));
         Route::post('/store', array('as' => 'issuerCompanyStore', 'uses' => 'IssuerCompanyController@store'));                 
         Route::post('/update', array('as' => 'issuerCompanyUpdate', 'uses' => 'IssuerCompanyController@update')); 
 
         //Certifie
         Route::post('/certified', array('as' => 'issuerSetCompanyCertifie', 'uses' => 'IssuerCompanyController@setCompanyCertifie'));           
         Route::post('/login', array('as' => 'issuerLoginAuth', 'uses' => 'IssuerCompanyController@authLogin'));    
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
        Route::post('/login', array('as' => 'adminLoginAuth', 'uses' => 'ProviderCompanyController@authLogin'));       

        //NFE
        Route::post('/nfe/generate', array('as' => 'adminSetCompanyCertifie', 'uses' => 'ProviderCompanyController@generateNfe'));  
        Route::post('/nfe', array('as' => 'getNfe', 'uses' => 'ProviderCompanyController@getNfe'));
    });	
});
});

//Provider API
Route::group(['prefix' => '/libs/gatewaynfe/provider/company', 'middleware' => 'auth.provider_api:api', 'namespace' => 'Codificar\GatewayNfe\Http\Controllers'], function () {
    Route::get('/{id}', array('as' => 'providerApiGetProviderCompany', 'uses' => 'ProviderCompanyController@getProviderCompany'));   
    Route::post('/store/address', array('as' => 'providerApiCompanyStore', 'uses' => 'ProviderCompanyController@storeAddress'));
    Route::post('/store/info', array('as' => 'providerApiCompanyStore', 'uses' => 'ProviderCompanyController@storeInfo'));
    Route::post('/update', array('as' => 'providerApiCompanyUpdate', 'uses' => 'ProviderCompanyController@update'));

    Route::post('/auth/certified', array('as' => 'providerApiSetCompanyCertifie', 'uses' => 'ProviderCompanyController@setCompanyCertifie'));
    Route::post('/auth/login', array('as' => 'providerApiGetAuthType', 'uses' => 'ProviderCompanyController@authLogin'));  
});	

/**
 * Rota para permitir utilizar arquivos de traducao do laravel (dessa lib) no vue js
 */
Route::get('/libs/gateway_nfe/lang.trans/{file}', function () {
    $fileNames = explode(',', Request::segment(4));
    $lang = config('app.locale');
    $files = array();
    foreach ($fileNames as $fileName) {
        array_push($files, __DIR__.'/../resources/lang/' . $lang . '/' . $fileName . '.php');
    }
    $strings = [];
    foreach ($files as $file) {
        $name = basename($file, '.php');
        $strings[$name] = require $file;
    }

    header('Content-Type: text/javascript');
    return ('window.lang = ' . json_encode($strings) . ';');
    exit();
})->name('assets.lang');
