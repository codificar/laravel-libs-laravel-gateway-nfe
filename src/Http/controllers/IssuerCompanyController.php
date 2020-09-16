<?php
namespace Codificar\GatewayNfe\Http\Controllers;

use App\Http\Controllers\Controller;
//Factory
use Codificar\GatewayNfe\Lib\NFEGatewayFactory;

//FormRequest
use Codificar\GatewayNfe\Http\Requests\IssuerCompanyFormRequest;

//Laravel uses
use View;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Queue;

//Externals Models
use Auth;

//Internal Model
use Codificar\GatewayNfe\Models\Company;
use Codificar\GatewayNfe\Models\NFERequests;
use Codificar\GatewayNfe\Models\NFEProvider;
use Codificar\GatewayNfe\Models\NFEUser;
use Codificar\GatewayNfe\Models\NFEInstitution;

//Internal Model
use Codificar\GatewayNfe\Models\NFESettings;

use Codificar\GatewayNfe\Jobs\SimulateGenerateIssuerNfeJob;
class IssuerCompanyController extends Controller
{	
	
	/**
	 * View to Create
	 * 
	 * @return View
	*/
	 	
	public function create()
	{			
		$settings = NFESettings::getIssuerData();
		$company = Company::getIssuerCompany();	
		if($company) $company->site = $settings['site'];
		
		return View::make('gateway_nfe::issuer_company.add')
			->with('company', json_encode($company));
	}

	/**
	 * Store API
	 * @param String   $document
     * @param String   $municipalRegistration      
     * @param String   $razaoSocial 
     * @param String   $fantasyName      
     * @param Array    $address [estate, city, place, number, neighborhood, zipcode, complement, ibgeCode]
     * @param Bool     $nationalSimpleOptant Default True     
     * @param Bool     $culturalPromoter   Default False
     * @param String   $estadualRegistration Default Null
     * @param Email    $commercialEmail Default Null
     * @param String   $commercialPhone Default null
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(IssuerCompanyFormRequest $request){	
		//Save on database
		
		$company = Company::store($request, Company::ownerIssuer);	
		
		//Save Settings
		NFESettings::setIssuerName($request->fantasyName);
		NFESettings::setIssuerDocument($request->document);
		NFESettings::setIssuerZipcode($request->address['zipcode']);
		NFESettings::setIssuerAddress($request->address['place']);
		NFESettings::setIssuerEmail($request->commercialEmail);
		NFESettings::setIssuerSite($request->site);
		NFESettings::setIssuerPhone($request->commercialPhone);
	
		//Create Gateway		
		$gateway = NFEGatewayFactory::createGateway();

		//Save on Gateway
		$gatewayResponse = $gateway->createCompany($company);

		return response()->json($gatewayResponse);
	}


	/**
	 * Update API
	 * @param String   $document
     * @param String   $municipalRegistration      
     * @param String   $socialReason 
     * @param String   $nomeFantasia      
     * @param Array    $address [estate, city, place, number, neighborhood, zipcode, complement, ibgeCode]
     * @param Bool     $nationalSimpleOptant Default True     
     * @param Bool     $culturalPromoter   Default False
     * @param String   $estadualRegistration Default Null
     * @param Email    $commercialEmail Default Null
     * @param String   $commercialPhone Default null
	 * 
	 * @return \Illuminate\Http\JsonResponse
	*/
	public function update(IssuerCompanyFormRequest $request)
	{
		//Update on database
		$company = Company::updateCompany($request, Company::ownerIssuer);	
		//Save Settings
		NFESettings::setIssuerName($request->fantasyName);
		NFESettings::setIssuerDocument($request->document);
		NFESettings::setIssuerZipcode($request->address['zipcode']);
		NFESettings::setIssuerAddress($request->address['place']);
		NFESettings::setIssuerEmail($request->commercialEmail);
		NFESettings::setIssuerSite($request->site);
		NFESettings::setIssuerPhone($request->commercialPhone);
	
		//Create Gateway		
		$gateway = NFEGatewayFactory::createGateway();

		//Update on Gateway
		$gatewayResponse = $gateway->updateCompany($company);

		return response()->json($gatewayResponse);
	}	

	/**
	 *  * @param $login
	 *  * @param $password
	 * @return \Illuminate\Http\JsonResponse
	*/
	public function authLogin(Request $request){	
		$responseArray = array(
			'data' => null,
			'sucess' => true,			
		);
		try {
			$provider_id = $request->provider_id;	
			$login = $request->login;
			$password = $request->password;

			//Get Company
			$company = Company::where("owner", Company::ownerIssuer)->first();

			//Create Gateway		
			$gateway = NFEGatewayFactory::createGateway();

			//GET on Gateway
			$responseArray = $gateway->loginAuthCompany($company, $login, $password);
			
			if($responseArray['sucess']){
				//Set Login Auth True
				$company->is_login_auth = true;
				$company->save();
			}else {				
				$company->is_login_auth = false;
				$company->save();				
			}				
		} catch (Exception $error) {
			$responseArray["data"] = $error->getMessage();
			$responseArray["sucess"] = false;
		}	
		return $responseArray;
	}
	
	/**
	 * @param file $certified
	 * @param text $pass
	 * @return \Illuminate\Http\JsonResponse	 
	*/
	public function setCompanyCertifie(Request $request){					
		$responseData = array('data' => [], 'sucess' => true); 	
		try {			
			if($request->hasFile('certified')){
				$file_name = time();
				$file_name .= rand();
				$ext = $request->file('certified')->getClientOriginalExtension();			
				$request->file('certified')->move(base_path()."/company_certifie/", $file_name . "." . $ext);
				$certifie_file_path = base_path()."/company_certifie/".$file_name . "." . $ext;
				
				$pass = $request->input('pass');
				
				//Get Company
				$company = Company::where("owner", Company::ownerIssuer)->first();

				//Create Gateway		
				$gateway = NFEGatewayFactory::createGateway();

				$responseData =  $gateway->setCompanyCertifie($company, $certifie_file_path, $pass);	
				//Update On Database
				
				if($responseData['sucess']){
					if($responseData['xml']){
						$company->digital_certificate_name = $responseData['xml']->nome;	
						$company->digital_expiration_date = $responseData['xml']->dataVencimento;	
						$company->is_doc_auth = true;	
						$company->save();
					}					
				}
									
			}else{
				$responseData['sucess'] = false;
				$responseData['data'] = "Escolha seu certificado";			
			}				
		} catch (\Throwable $th) {
			$responseData['sucess'] = false;
			$responseData['data'] = $th;			
		}	
		
		return $responseData;
	}

	public function simulateValue(Request $request){
		$now = $request->start_date;
		$latMonth = $request->end_date;

		//Get users and institutions
		$providers = NFEProvider::getProvidersByRequestsInterval($now, $latMonth);
		$users = NFEUser::getUsersByRequestsInterval($now, $latMonth);
		$institutions = NFEInstitution::getInstitutionByRequestsInterval($now, $latMonth);
	
		$company = Company::getIssuerCompany();
		$responseArray = [];
		foreach ($users as $key => $user) {   			           
				$value = (NFERequests::getSumIssuerValue($user['id'], $now, $latMonth));				
				$responseObject = (object) [
					'type' => "user",					
					'user_id' => $user['id'],				
					'value' => $value,
					'gateway_company_id' => $company->gateway_company_id
				];
				if($value > 0) array_push($responseArray, $responseObject);				   
			                
		}

		foreach ($institutions as $key => $institution) {   			           
			$value = (NFERequests::getSumIssuerValue($institution['id'], $now, $latMonth));				
			$responseObject = (object) [
				'type' => "institution",					
				'institution_id' => $institution['id'],				
				'value' => $value,
				'gateway_company_id' => $company->gateway_company_id
			];
			if($value > 0) array_push($responseArray, $responseObject);				   
						
	}
		
		return $responseArray;
	}

	

	public function simulateJob(Request $request){	
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		
		Queue::push(new SimulateGenerateIssuerNfeJob($startDate, $endDate));
	}
}