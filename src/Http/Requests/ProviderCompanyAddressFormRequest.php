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
			'provider_id.required' => trans('user_provider_controller.provider_id_required'),
			'ibgeCode.required' => "IBGE code Not Found",
			'neighborhood.required' => trans('user_provider_controller.address_neighbour_required'),
			'zipcode.required' => trans('user_provider_controller.zipcode'),
			'city.required' => trans('user_provider_controller.address_city_required'),			
			'place.required' => trans('user_provider_controller.address_neighbour_required'),
			'number.required' => trans('user_provider_controller.address_number_required'),
			'estate.required' => trans('user_provider_controller.address_state_required')
			
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