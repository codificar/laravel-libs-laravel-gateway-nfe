<?php
namespace Codificar\GatewayNfe\Http\Controllers;

use App\Http\Controllers\Controller;

//Laravel uses
use View;
use Input;
use Redirect;
use URL;
use Illuminate\Http\Request;

//Internal Model
use Codificar\GatewayNfe\Models\NFESettings;
use Codificar\GatewayNfe\Models\Company;
use Codificar\GatewayNfe\Models\GatewayNFE;
use Codificar\GatewayNfe\Jobs\GenerateProviderNfeJob;

//External Packages
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;


class GatewaySettingsController extends Controller
{	

	public function create()
	{
		// Category 10			
		$setting = 10;
		$list = NFESettings::where('category', $setting)->get();
		$model = $this->getViewModel($list);
		
		$title = ucwords(trans('gatewayNfe::gateway_nfe.settings_title'));
	
		return View::make('gateway_nfe::settings.gateway') 			
			->with('title', $title)
			->with('page', 'settings')
			->with('model', $model);
	}

	/**
	 *  Save Or Update NFE Gateway Settings
	 */
	public function store($request = null)
	{
		
		$settingCategory = 10;
		$first_setting = NFESettings::first();
		
		foreach (($request ? $request : Input::all()) as $key => $item) {
			$temp_setting = NFESettings::find($key);
			if(!$temp_setting)
				$temp_setting =  NFESettings::where('key', '=', $key)->first();
			if ($temp_setting && isset($item)) {
				$temp_setting->value = $item;
				$temp_setting->save();
			} elseif (!is_numeric($key) && $first_setting && isset($item)) {
				$new_setting = new NFESettings();
				$new_setting->key = $key;
				$new_setting->value = $item;
				$new_setting->page = 1;
				$new_setting->category = $settingCategory;
				$new_setting->save();
			}
		}

		if($request)
			return true;

		$alert = array('class' => 'success', 'msg' => trans('gatewayNfe::gateway_nfe.save_settings'));

		return Redirect::to(URL::Route('NfeGatewaySettings'))->with('alert', $alert);
	}

	private function getViewModel($list)
	{
		$model = new ModelObjectSettings();
		foreach ($list as $item) {
			$modelApplication = new ApplicationSettingsViewModel();
			$modelApplication->id = $item['id'];
			$modelApplication->key = $item['key'];
			$modelApplication->value = $item['value'];
			$modelApplication->tool_tip = $item['tool_tip'];
			$modelApplication->page = $item['page'];
			$modelApplication->category = $item['category'];
			$modelApplication->sub_category = $item['sub_category'];

			$model->{$item['key']} = $modelApplication;			
		}
		
		return $model;
	}

	/**
	 *  Validate API KEY
	 */
	public function updateEnableGateway(Request $request)
	{			
		$settings = NFESettings::where('key', 'nfe_gateway_enable')->first();
		$url = 'https://api.enotasgw.com.br/v1/empresas?pageNumber=0&pageSize=5&searchBy=nome_fantasia&sortBy=nome_fantasia&sortDirection=asc';
		$client = new \GuzzleHttp\Client();
		$responseData = array('data' => $request->apiKey, 'success' => true, 'statusCode' => 200); 
		
		try {			
			$response = $client->request('GET', $url, [
				'headers' => [
					'Authorization'     =>  "Basic ".$request->apiKey,
					'Accept' => 'application/json',
				]
			]);			
			$statusCode = $response->getStatusCode();
			if($statusCode != 200){				
				$settings->value = 0;
				$settings->save();
				$responseData['success']	= false;
				$responseData['statusCode']	= 401;
			}
			
		} catch (RequestException $e) {		
			$settings->value = 0;
			$settings->save();
			$responseData['success']	= false;
			$responseData['statusCode']	= 401;
		}
		return response($responseData, $responseData['statusCode']);	

	}

}