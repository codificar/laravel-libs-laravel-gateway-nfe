<?php

namespace Codificar\GatewayNfe\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class ProviderCompanyFormRequest
 *
 * @package MotoboyApp
 *
 * @author  Gustavo Silva <gustavo.silva@codificar.com.br>
 */
class ProviderCompanyFormRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		$this->provider = \Provider::find($this->provider_id);
		$return = [			
			"provider_id"	=> ['required'],		
			"document" => [
				"required",
				Rule::unique('companies')->ignore($this->id, 'id')
			],			
			"municipalRegistration" => ['required'],		
			"socialReason" => ['required'],
			"fantasyName" =>  ['required'],
			"nationalSimpleOptant" => ['required'],					
			"culturalPromoter" => ['required'],
			
			"address"	=> ['required'],
		];
		
		return $return;
	}

	public function messages() {
		$return = [
			'provider_id.required' => trans('gatewayNfe::gateway_nfe.provider_id_required'),
			
			'document.required' => trans('gatewayNfe::gateway_nfe.document_company_required') ,	
			'document.unique' => trans('gateway_nfe.document_company_unique'),	

			'municipalRegistration.required' => trans('gatewayNfe::gateway_nfe.municipal_register_required'),
			'fantasyName.required' => trans('gatewayNfe::gateway_nfe.fantasy_name_required'),
			'nationalSimpleOptant.required' => trans('gatewayNfe::gateway_nfe.nacional_optant_required'),
			'culturalPromoter.required' => trans('gatewayNfe::gateway_nfe.cultural_promter_required'),
			'socialReason.required' => trans('gatewayNfe::gateway_nfe.social_reason_required'),						
			'address.required' => trans('gatewayNfe::gateway_nfe.address_title_required')		
		];

		return $return;
	}

	/**
	 * Retorna um json caso a validação falhe.
	 * 
	 * @throws HttpResponseException
	 * @return json
	 */
	protected function failedValidation(Validator $validator) {
		throw new HttpResponseException(
			response()->json([
				'success' => false,
				'errors' => $validator->errors()->all(),
				'error_code' => \ApiErrors::REQUEST_FAILED
			])
		);
	}

}