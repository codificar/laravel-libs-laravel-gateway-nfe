<?php

namespace Codificar\GatewayNfe\Models;

use Illuminate\Database\Eloquent\Relations\Model;
use Eloquent;
use Log;
//Internal Model
use Codificar\GatewayNfe\Models\NFERequests;

//Factory
use Codificar\GatewayNfe\Lib\NFEGatewayFactory;

class GatewayNFE extends Eloquent
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gateway_nfes';
	
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = true;

    protected $fillable = array('company_id','nfe_id', 'nfe_external_id', 'nfe_status', 'nfe_status_reason', 
	'nfe_pdf', 'nfe_xml', 'nfe_number', 'nfe_verification_code', 'nfe_rps_number', 'nfe_rps_serie', 'nfe_competence_date');
	
	//issuerType
	const issuerTypeProvider = "provider";
	const issuerTypeIssuer = "issuer";

	//clientType
	const clientTypeUser = "user";
	const clientTypeUserInstitution = "institution";

	/**
	 * Emmit NFE
	 * @param $id
	 * @param $gateweyData
	 * @param $issuerType
	 * @param $clientType
	 * @return \Models\GatewayNFE
	*/

	public function emmit($id, $gateweyData, $value, $issuerType, $clientType){
		$gatewayNFE = GatewayNFE::findOrFail($id);	
		$gatewayNFE->nfe_id = $gateweyData->id;
		$gatewayNFE->nfe_external_id = $gatewayNFE->id;
		$gatewayNFE->nfe_status = $gateweyData->status;
		$gatewayNFE->nfe_pdf = $gateweyData->linkDownloadPDF;
		$gatewayNFE->nfe_xml = $gateweyData->linkDownloadXML;
		$gatewayNFE->nfe_number = $gateweyData->numero;
		$gatewayNFE->nfe_verification_code = $gateweyData->codigoVerificacao;
		$gatewayNFE->nfe_rps_number = $gateweyData->numeroRps;
		$gatewayNFE->nfe_rps_serie = $gateweyData->serieRps;
		$gatewayNFE->nfe_competence_date = $gateweyData->dataCompetenciaRps;

		$gatewayNFE->value = $value;
		$gatewayNFE->issuer_type = $issuerType;
		$gatewayNFE->client_type = $clientType;
		
		$gatewayNFE->save();
		return $gatewayNFE;		
	}
	
	/**
	 * Store on database one NFE
	 * @param GatewayNFE
	 * @return \Models\GatewayNFE
	*/

	public function gatewayDataStore($id, $gateweyData, $value, $issuerType, $clientType){
		$gatewayNFE = GatewayNFE::findOrFail($id);	
		$gatewayNFE->nfe_id = $gateweyData->id;
		$gatewayNFE->nfe_external_id = $gatewayNFE->id;
		$gatewayNFE->nfe_status = $gateweyData->status;
		$gatewayNFE->nfe_pdf = $gateweyData->linkDownloadPDF;
		$gatewayNFE->nfe_xml = $gateweyData->linkDownloadXML;
		$gatewayNFE->nfe_number = $gateweyData->numero;
		$gatewayNFE->nfe_verification_code = $gateweyData->codigoVerificacao;
		$gatewayNFE->nfe_rps_number = $gateweyData->numeroRps;
		$gatewayNFE->nfe_rps_serie = $gateweyData->serieRps;
		$gatewayNFE->nfe_competence_date = $gateweyData->dataCompetenciaRps;

		$gatewayNFE->value = $value;
		$gatewayNFE->issuer_type = $issuerType;
		$gatewayNFE->client_type = $clientType;
		
		$gatewayNFE->save();
		return $gatewayNFE;		
	}


	/**
	 * Emmit a NFE from providers to users
	 * @param providers
	 * @param users
	 * @param service
	 * @param startDate
	 * @param endDate
	
	*/
	public function emmitProviderToUserNfe($providers, $users, $service, $startDate, $endDate){
		foreach ($users as $key => $user) {    
			foreach ($providers as $providerKey => $provider) {  	           
				$value = NFERequests::getProviderValueByUser($provider['provider_id'], $user['id'], $startDate, $endDate);	
				Log::debug("Provider ".$provider['provider_id']."To Institution".$user['id']." VALUE ".$value);
				if(isset($provider['gateway_company_id'])){
					$companyId = $provider['gateway_company_id'];							
					// $companyId = "ecda2c6f-333a-4130-8eba-0c01452f0600";
					// $value = 10;
					if($value > 0) self::emmitOnGateway($companyId, $user, $service, $value, GatewayNFE::issuerTypeProvider, GatewayNFE::clientTypeUser);
				} else {
					Log::debug("Provider to User ".$provider['provider_id']." no have valid company");
				}
			   
			}                
		}
	}	

	/**
	 * Emmit a NFE from providers to institution
	 * @param providers
	 * @param institutions
	 * @param service
	 * @param startDate
	 * @param endDate
	
	*/
	public function emmitProviderToInstitutionNfe($providers, $institutions, $service, $startDate, $endDate){
		foreach ($institutions as $key => $institution) {
			foreach ($providers as $providerKey => $provider) {  
				$value = NFERequests::getProviderValueByUser($provider['provider_id'], $institution['id'], $startDate, $endDate);
				Log::debug("Provider ".$provider['provider_id']."To Institution".$institution['id']." VALUE ".$value);	
				if(isset($provider['gateway_company_id'])){
					$companyId = $provider['gateway_company_id'];	
					// $companyId = "ecda2c6f-333a-4130-8eba-0c01452f0600";
					// $value = 20;
					if($value > 0) self::emmitOnGateway($companyId, $institution, $service, $value, GatewayNFE::issuerTypeProvider, GatewayNFE::clientTypeUserInstitution);
				}else {
					Log::debug("Provider To Institution ".$provider['provider_id']." no have valid company");
				}                  
			}
		}     
	}

	//Store NFE on Database and Gateway
	private static function emmitOnGateway($companyId, $client, $service, $value, $issuerType, $clientType){
        //Store on database
        $gatewayNFE = new GatewayNFE;
        $gatewayNFE->company_id = $companyId;
        $gatewayNFE->save();
        
        //Create on Gateway		
        $gateway = NFEGatewayFactory::createGateway();
		//Create NFE on Gateway
        $response = $gateway->generateNfe(strval($gatewayNFE->id), $companyId, $client, $service, $value);
	
        if($response['sucess']){             
            $response = $gateway->getNfeByRequestId($companyId, strval($gatewayNFE->id));
			$gatewayNFE = self::gatewayDataStore(strval($gatewayNFE->id), $response['data'], $value, $issuerType, $clientType);    			        
			Log::info("Generate NFE Sucess");       			          
        }else{
            Log::error("Generate NFE Error", $response);
        }
	}
	
	/**
	 * Filter provider in many ways
	 * @param $id
	 * @param $issuerType
	 * @param $clientType
	 * @param $startDate
	 * @param $endDate
	 * @param $order
	 * @return \Illuminate\Pagination\Paginator
	 */
	public static function search($id = null, $issuerType = null, $clientType = null, $startDate = null, $endDate = null, $order = null)
	{
		//Joins and Selects
		$query = self::select('*');

		//Where filters
		if ($id != null) $query->where('id', '=', $id);
		if ($issuerType != null) $query->where('issuer_type', '=', $issuerType);
		if ($clientType != null) $query->where('client_type', '=', $clientType);
		if ($startDate != null) $query->whereDate('created_at', '>=', $startDate);
		if ($endDate != null) $query->whereDate('created_at', '<=', $endDate);



		//Order By
		if ($order == null) {
			$query->orderBy('id', 'DESC');
		} else {
			if ($order == 0)
				$query->orderBy($type, 'asc');
			else if ($order == 1)
				$query->orderBy($type, 'desc');
		}

		return $query;
	}
	
}