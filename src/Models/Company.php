<?php

namespace Codificar\GatewayNfe\Models;

use Illuminate\Database\Eloquent\Relations\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Eloquent;

class Company extends Eloquent
{
	const ownerIssuer = 'issuer';
    const ownerProvider = 'provider';
	
	public $timestamps = false;
	protected $table = "companies";
	protected $fillable = ['gateway_company_id',"document",
	"ibge_code", "neighborhood", "zipcode", "city", "complement", "place", "number", 
	"estate", "cultural_promoter", "national_simple_optant", "estadual_registration", "municipal_registration", 
	"fantasy_name", "social_reason", "commercial_phone", "commercial_email", "is_auth", "provider_id", "digital_certificate_name", "digital_expiration_date", "owner"];
		
	/**
	 * Create
	 * @param Number   $provider_id
	 * 
	 * @param String   $document
     * @param String   $municipalRegistration      
     * @param String   $socialReason 
     * @param String   $fantasyName      
     * @param Array    $address [estate, city, place, number, neighborhood, zipcode, complement, ibgeCode]
     * @param Bool     $nationalSimpleOptant Default True     
     * @param Bool     $culturalPromoter   Default False
     * @param String   $estadualRegistration Default Null
     * @param Email    $commercialEmail Default Null
     * @param String   $commercialPhone Default null	
	 * 
	 * @param enum 		(provider, issuer)  $owner
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store($request, $owner = Company::ownerProvider){

		
		$providerCompany = new Company;
		$providerCompany->provider_id = $request->provider_id;
		
		$providerCompany->document = $request->document;
		$providerCompany->municipal_registration = $request->municipalRegistration;
		$providerCompany->social_reason = $request->socialReason;
		$providerCompany->fantasy_name = $request->fantasyName;

		$providerCompany->estate = $request->address['estate'];
		$providerCompany->city = $request->address['city'];
		$providerCompany->place = $request->address['place'];
		$providerCompany->number = $request->address['number'];
		$providerCompany->neighborhood = $request->address['neighborhood'];
		$providerCompany->zipcode = $request->address['zipcode'];
		$providerCompany->complement = $request->address['complement'];
		$providerCompany->ibge_code = $request->address['ibgeCode'];
		
		$providerCompany->national_simple_optant = $request->nationalSimpleOptant;
		$providerCompany->cultural_promoter = $request->culturalPromoter;
		$providerCompany->estadual_registration = $request->estadualRegistration;
		$providerCompany->commercial_email = $request->commercialEmail;
		$providerCompany->commercial_phone = $request->commercialPhone;

		$providerCompany->owner = $owner;

		$providerCompany->save();
		
		return $providerCompany;
	}

	/**
	 * Create Address
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
	public function storeAddress($request){
		$providerCompany = new Company;
		$providerCompany->provider_id = $request->provider_id;
		
		$providerCompany->estate = $request->estate;
		$providerCompany->city = $request->city;
		$providerCompany->place = $request->place;
		$providerCompany->number = $request->number;
		$providerCompany->neighborhood = $request->neighborhood;
		$providerCompany->zipcode = $request->zipcode;
		$providerCompany->complement = $request->complement;
		$providerCompany->ibge_code = $request->ibgeCode;

		$providerCompany->save();
		
		return $providerCompany;
	}

	/**
	 * Create Info
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
	public function storeInfo($request){
		$providerCompany = Company::where("provider_id", $request->provider_id)->first();
		
		$providerCompany->document = $request->document;
		$providerCompany->municipal_registration = $request->municipalRegistration;
		$providerCompany->social_reason = $request->socialReason;
		$providerCompany->fantasy_name = $request->fantasyName;
		
		$providerCompany->national_simple_optant = $request->nationalSimpleOptant;
		$providerCompany->cultural_promoter = $request->culturalPromoter;
		$providerCompany->estadual_registration = $request->estadualRegistration;
		$providerCompany->commercial_email = $request->commercialEmail;
		$providerCompany->commercial_phone = $request->commercialPhone;

		$providerCompany->save();
		
		
		return $providerCompany;
	}

	/**
	 * Update
	 * @param Number   $provider_id
	 * 
	 * @param String   $document
     * @param String   $municipalRegistration      
     * @param String   $socialReason 
     * @param String   $fantasyName      
     * @param Array    $address [estate, city, place, number, neighborhood, zipcode, complement, ibgeCode]
     * @param Bool     $nationalSimpleOptant Default True     
     * @param Bool     $culturalPromoter   Default False
     * @param String   $estadualRegistration Default Null
     * @param Email    $commercialEmail Default Null
     * @param String   $commercialPhone Default null	
	 * @return \Illuminate\Http\JsonResponse
	 */
	public static function updateCompany($request, $owner = Company::ownerProvider){
		
		if($owner == Company::ownerIssuer){
			$providerCompany = Company::where("owner", Company::ownerIssuer)->first();
		}else{			
			$providerCompany = Company::where("provider_id", $request->provider_id)->first();
		}
		
		
		$providerCompany->document = $request->document;
		$providerCompany->municipal_registration = $request->municipalRegistration;
		$providerCompany->social_reason = $request->socialReason;
		$providerCompany->fantasy_name = $request->fantasyName;

		$providerCompany->estate = $request->address['estate'];
		$providerCompany->city = $request->address['city'];
		$providerCompany->place = $request->address['place'];
		$providerCompany->number = $request->address['number'];
		$providerCompany->neighborhood = $request->address['neighborhood'];
		$providerCompany->zipcode = $request->address['zipcode'];
		$providerCompany->complement = $request->address['complement'];
		$providerCompany->ibge_code = $request->address['ibgeCode'];
		
		$providerCompany->national_simple_optant = $request->nationalSimpleOptant;
		$providerCompany->cultural_promoter = $request->culturalPromoter;
		$providerCompany->estadual_registration = $request->estadualRegistration;
		$providerCompany->commercial_email = $request->commercialEmail;
		$providerCompany->commercial_phone = $request->commercialPhone;

		$providerCompany->save();
		
		return $providerCompany;
	}

	/**
	 * GET BY $provider_id
	 * @param Number   $provider_id
	 *
	 * @return \Illuminate\Http\JsonResponse
		* @return String   $document
		* @return String   $municipalRegistration      
		* @return String   $socialReason 
		* @return String   $fantasyName      
		* @return Array    $address [estate, city, place, number, neighborhood, zipcode, complement, ibgeCode]
		* @return Bool     $nationalSimpleOptant Default True     
		* @return Bool     $culturalPromoter   Default False
		* @return String   $estadualRegistration Default Null
		* @return Email    $commercialEmail Default Null
		* @return String   $commercialPhone Default null		
	*/
	public function getProviderCompany($provider_id){
		$request = Company::where("provider_id", $provider_id)->first();
				
		if($request){			
			$providerCompany = new \stdClass; // Instantiate stdClass object	
			$providerCompany->id = $request->id;
			$providerCompany->gateway_company_id = $request->gateway_company_id;
			$providerCompany->document = $request->document;
			$providerCompany->municipalRegistration = $request->municipal_registration;
			$providerCompany->socialReason = $request->social_reason;
			$providerCompany->fantasyName = $request->fantasy_name;

			$providerCompany->address['estate'] = $request->estate;
			$providerCompany->address['city'] = $request->city;
			$providerCompany->address['place'] = $request->place;
			$providerCompany->address['number'] = $request->number;
			$providerCompany->address['neighborhood'] = $request->neighborhood;
			$providerCompany->address['zipcode'] = $request->zipcode;
			$providerCompany->address['complement'] = $request->complement;
			$providerCompany->address['ibgeCode'] = $request->ibge_code;

			$providerCompany->nationalSimpleOptant = $request->national_simple_optant;
			$providerCompany->culturalPromoter = $request->cultural_promoter;
			$providerCompany->nationalSimpleOptant = $request->estadual_registration;
			$providerCompany->commercialEmail = $request->commercial_email;
			$providerCompany->commercialPhone = $request->commercial_phone;
			
			$providerCompany->isDocAuth = $request->is_doc_auth;
			$providerCompany->isLoginAuth = $request->is_login_auth;

			$providerCompany->digitalCertificateName = $request->digital_certificate_name;
			$providerCompany->digitalExpirationDate = $request->digital_expiration_date;

			return $providerCompany;
		}else {
			return false;
		}		
	}

	/**
	 * GET Issuer Company
	 *
	 * @return \Illuminate\Http\JsonResponse
		* @return String   $document
		* @return String   $municipalRegistration      
		* @return String   $socialReason 
		* @return String   $fantasyName      
		* @return Array    $address [estate, city, place, number, neighborhood, zipcode, complement, ibgeCode]
		* @return Bool     $nationalSimpleOptant Default True     
		* @return Bool     $culturalPromoter   Default False
		* @return String   $estadualRegistration Default Null
		* @return Email    $commercialEmail Default Null
		* @return String   $commercialPhone Default null		
	*/
	public static function getIssuerCompany(){
		$request = Company::where("owner", self::ownerIssuer)->first();
				
		if($request){			
			$providerCompany = new \stdClass; // Instantiate stdClass object	
			$providerCompany->id = $request->id;
			$providerCompany->gateway_company_id = $request->gateway_company_id;
			$providerCompany->document = $request->document;
			$providerCompany->municipalRegistration = $request->municipal_registration;
			$providerCompany->socialReason = $request->social_reason;
			$providerCompany->fantasyName = $request->fantasy_name;

			$providerCompany->address['estate'] = $request->estate;
			$providerCompany->address['city'] = $request->city;
			$providerCompany->address['place'] = $request->place;
			$providerCompany->address['number'] = $request->number;
			$providerCompany->address['neighborhood'] = $request->neighborhood;
			$providerCompany->address['zipcode'] = $request->zipcode;
			$providerCompany->address['complement'] = $request->complement;
			$providerCompany->address['ibgeCode'] = $request->ibge_code;

			$providerCompany->nationalSimpleOptant = $request->national_simple_optant;
			$providerCompany->culturalPromoter = $request->cultural_promoter;
			$providerCompany->nationalSimpleOptant = $request->estadual_registration;
			$providerCompany->commercialEmail = $request->commercial_email;
			$providerCompany->commercialPhone = $request->commercial_phone;
			
			$providerCompany->isDocAuth = $request->is_doc_auth;
			$providerCompany->isLoginAuth = $request->is_login_auth;

			$providerCompany->digitalCertificateName = $request->digital_certificate_name;
			$providerCompany->digitalExpirationDate = $request->digital_expiration_date;

			return $providerCompany;
		}else {
			return false;
		}		
	}
}