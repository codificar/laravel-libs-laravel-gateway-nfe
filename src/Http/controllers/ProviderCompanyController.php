<?php
namespace Codificar\GatewayNfe\Http\Controllers;

use App\Http\Controllers\Controller;
//Factory
use Codificar\GatewayNfe\Lib\NFEGatewayFactory;
//FormRequest
use Codificar\GatewayNfe\Http\Requests\ProviderCompanyFormRequest;
//Laravel uses
use View;
use Carbon\Carbon;
use Illuminate\Http\Request;
//Externals Models
use Auth;
//Internal Model
use Codificar\GatewayNfe\Models\Company;

class ProviderCompanyController extends Controller
{	
	/**
	 * View to Create
	 * 
	 * @return View
	*/
	 	
	public function create($id = null){
		// if(Auth::guard('web')->user()->type = 'admin'){
		// 	$enviroment = 'admin';
		// }else{
		// 	$enviroment = 'provider';
		// 	$id = Auth::guard('web')->user()->id;
		// }
		$enviroment = 'admin';
		//Has provider Company	
		$providerCompany = Company::getProviderCompany($id);		
		
		return View::make('gateway_nfe::provider_company.add')
			->with('enviroment', $enviroment)
			->with('providerId', $id)
			->with('company', json_encode($providerCompany));
	}

	/**
	 * Store API
	 * @param String   $document
     * @param String   $municipalRegistration      
     * @param String   $razaoSocial 
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
	public function store(ProviderCompanyFormRequest $request){	
		//Save on database
		$providerCompany = Company::store($request);	
		
		//Create Gateway		
		$gateway = NFEGatewayFactory::createGateway();

		//Save on Gateway
		$gatewayResponse = $gateway->createCompany($providerCompany);

		return response()->json($gatewayResponse);
	}

	/**
	 * Save Address
	 * @param Number   $provider_id
	 * 
	 * @param String   $estate
     * @param String   $city      
     * @param String   $place 
     * @param String   $number      
     * @param String   $neighborhood 
     * @param String   $zipcode  
     * @param String   $complement Default Null
     * @param String   $ibgeCode 
	 * @return \Illuminate\Http\JsonResponse
	*/
	public function storeAddress(ProviderCompanyAddressFormRequest $request){	
		//Save on database
		
		$providerCompany = Company::storeAddress($request);	

		return response()->json($providerCompany);
	}

	/**
	 * Create Info And Register on Gateway
	 * @param Number   $provider_id
	 * 
	 * @param String   $document
     * @param String   $municipalRegistration      
     * @param String   $socialReason 
     * @param String   $fantasyName      
     * @param Bool     $nationalSimpleOptant Default True     
     * @param Bool     $culturalPromoter   Default False
     * @param String   $estadualRegistration Default Null
     * @param Email    $commercialEmail Default Null
     * @param String   $commercialPhone Default null	
	 * @return \Illuminate\Http\JsonResponse
	*/
	public function storeInfo(ProviderCompanyInfoFormRequest $request){	
		//Save on database
		$providerCompany = Company::storeInfo($request);	
	
		//Create Gateway		
		$gateway = NFEGatewayFactory::createGateway();

		//Save on Gateway
		$gatewayResponse = $gateway->createCompany($providerCompany);

		return response()->json($gatewayResponse);
	}

	/**
	 * Update API
	 * @param String   $document
     * @param String   $municipalRegistration      
     * @param String   $razaoSocial 
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
	public function update(ProviderCompanyFormRequest $request){
		//Update on database
		$providerCompany = Company::updateCompany($request);	
	
		//Create Gateway		
		$gateway = NFEGatewayFactory::createGateway();

		//Update on Gateway
		$gatewayResponse = $gateway->updateCompany($providerCompany);

		return response()->json($gatewayResponse);
	}

	/**
	 * @param number $provider_id
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
				$provider_id = $request->input('provider_id');		
				$providerCompany = Company::where('provider_id', $provider_id)->first();
				$eNotas = new eNotasLib;
				$responseData =  $eNotas->setCompanyCertifie($certifie_file_path, $pass, $providerCompany->gateway_company_id);	
				//Update On Database
				if($responseData['sucess']){
					if($responseData['xml']){
						$providerCompany->digital_certificate_name = $responseData['xml']->nome;	
						$providerCompany->digital_expiration_date = $responseData['xml']->dataVencimento;	
						$providerCompany->is_doc_auth = true;	
						$providerCompany->save();
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

	/**
	 * @param $provider_id
	 * @return \Illuminate\Http\JsonResponse
	*/
	public function getProviderCompany($id = null){		
		$response = array(
			'response' => null,
			'sucess' => true,			
		);							
				
			$providerCompany = Company::getProviderCompany($id);			
			$response['response'] = $providerCompany;		
		
		return response()->json($response);
	}

	/**
	 * @param $provider_id
	 * @return \Illuminate\Http\JsonResponse
	*/
	public function getProviderCompanyOnGateway($id = null){		
		$response = array(
			'response' => null,
			'sucess' => true,			
		);							
				
		//Create Gateway		
		$gateway = NFEGatewayFactory::createGateway($id);

		//GET on Gateway
		$providerCompany = Company::getProviderCompany($id);
		$gatewayProviderCompany = $gateway->getCompany($providerCompany->gateway_company_id);
		
		$response['response'] = $gatewayProviderCompany;		
		
		return response()->json($response);
	}

	/**
	 * @param $ibgeCode
	 * @return \Illuminate\Http\JsonResponse
	*/
	public function getAuthType(){		
		$responseArray = array(
			'data' => null,
			'sucess' => true,			
		);		
		try {
			$ibgeCode = Input::get('ibgeCode');		
			$eNotas = new eNotasLib;
			$responseArray = $eNotas->getAuthType($ibgeCode);				
		} catch (Exception $error) {
			$responseArray["data"] = $error->getMessage();
			$responseArray["sucess"] = false;
		}			
		return $responseArray;
	}

	/**
	 * @param $provider_id
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
			$company = Company::where('provider_id', $provider_id)->first();

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
	 * Run a generateNfe NFS-e JOB
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function generateNfe(){
		Queue::push(new GenerateProviderNfeJob());
	}
	
	/**
	 * Run a generateNfe NFS-e JOB
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getNfe(){
		//Create Gateway		
		$gateway = NFEGatewayFactory::createGateway();

		//GET NFE on Gateway
		$gatewayResponse = $gateway->getNfeByRequestId("ecda2c6f-333a-4130-8eba-0c01452f0600", 142);	
		
		return response()->json($gatewayResponse);
	}


	/**
	 * View to Create
	 * 
	 * @return View
	*/
	 	
	public function issuerCreate()
	{			
		$settings = Settings::getIssuerData();
		$company = Company::getIssuerCompany();	
		$company->site = $settings['site'];	
		return View::make('invoice.issue')
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
	public function issuerStore(IssuerCompanyFormRequest $request){	
		//Save on database
		$company = Company::store($request, Company::ownerIssuer);	
		//Save Settings
		Settings::setIssuerName($request->fantasyName);
		Settings::setIssuerDocument($request->document);
		Settings::setIssuerZipcode($request->address['zipcode']);
		Settings::setIssuerAddress($request->address['place']);
		Settings::setIssuerEmail($request->commercialEmail);
		Settings::setIssuerSite($request->site);
		Settings::setIssuerPhone($request->commercialPhone);
	
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
	public function issuerUpdate(IssuerCompanyFormRequest $request)
	{
		//Update on database
		$company = Company::updateCompany($request, Company::ownerIssuer);	
		//Save Settings
		Settings::setIssuerName($request->fantasyName);
		Settings::setIssuerDocument($request->document);
		Settings::setIssuerZipcode($request->address['zipcode']);
		Settings::setIssuerAddress($request->address['place']);
		Settings::setIssuerEmail($request->commercialEmail);
		Settings::setIssuerSite($request->site);
		Settings::setIssuerPhone($request->commercialPhone);
	
		//Create Gateway		
		$gateway = NFEGatewayFactory::createGateway();

		//Update on Gateway
		$gatewayResponse = $gateway->updateCompany($company);

		return response()->json($gatewayResponse);
	}

	/**
	 * @param file $certified
	 * @param text $pass
	 * @return \Illuminate\Http\JsonResponse	 
	*/
	public function setIssuerCompanyCertifie(Request $request){					
		$responseData = array('data' => [], 'sucess' => true); 	
		try {			
			if($request->hasFile('certified')){
				$file_name = time();
				$file_name .= rand();
				$ext = $request->file('certified')->getClientOriginalExtension();			
				$request->file('certified')->move(base_path()."/company_certifie/", $file_name . "." . $ext);
				$certifie_file_path = base_path()."/company_certifie/".$file_name . "." . $ext;

				$pass = $request->input('pass');		
				$company = Company::getIssuerCompany();	
				$eNotas = new eNotasLib;
				$responseData =  $eNotas->setCompanyCertifie($certifie_file_path, $pass, $company->gateway_company_id);	
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

	/**
	 *  * @param $login
	 *  * @param $password
	 * @return \Illuminate\Http\JsonResponse
	*/
	public function issuerAuthLogin(){	
		$responseArray = array(
			'data' => null,
			'sucess' => true,			
		);
		try {
			$provider_id = $request->provider_id;	
			$login = $request->login;
			$password = $request->password;

			//Get Company
			$company = Company::getIssuerCompany();	

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
	}
}