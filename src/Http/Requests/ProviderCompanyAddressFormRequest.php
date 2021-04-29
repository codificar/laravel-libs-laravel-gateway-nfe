<?php

namespace Codificar\GatewayNfe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class ProviderCompanyAddressFormRequest
 *
 * @package MotoboyApp
 *
 * @author  Gustavo Silva <gustavo.silva@codificar.com.br>
 */
class ProviderCompanyAddressFormRequest extends FormRequest {
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

			"ibgeCode"	=> ['required'],
			"neighborhood" => ['required'],
			"zipcode" =>  ['required'],
			"city" => ['required'],				
			"place" =>  ['required'],
			"number" => ['required'],
			"estate" =>  ['required'],
		];

		return $return;
	}

	public function messages() {
		$return = [
			'provider_id.required' => trans('gatewayNfe::gateway_nfe.provider_id_required') ,	

			'ibgeCode.required' => trans('gatewayNfe::gateway_nfe.ibge_code_not_found'),
			'neighborhood.required' => trans('gatewayNfe::gateway_nfe.address_neighbour_required'),
			'zipcode.required' => trans('gatewayNfe::gateway_nfe.zipcode'),
			'city.required' => trans('gatewayNfe::gateway_nfe.address_city_required'),			
			'place.required' => trans('gatewayNfe::gateway_nfe.address_neighbour_required'),
			'number.required' => trans('gatewayNfe::gateway_nfe.address_number_required'),
			'estate.required' => trans('gatewayNfe::gateway_nfe.address_state_required')	
			
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