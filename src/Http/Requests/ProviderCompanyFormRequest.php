<?php

namespace App\Http\Requests;

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
			'provider_id.required' => trans('user_provider_controller.provider_id_required'),
			'document.required' => trans('user_provider_controller.document_company_required'),	
			'document.unique' => trans('user_provider_controller.document_company_unique'),	

			'municipalRegistration.required' => trans('user_provider_controller.municipal_registration'),
			'fantasyName.required' => trans('user_provider_controller.nome_fantasia'),
			'nationalSimpleOptant.required' => trans('user_provider_controller.optante_nacional'),
			'culturalPromoter.required' => trans('user_provider_controller.culturalPromoter'),
			'socialReason.required' => trans('user_provider_controller.razao_social'),						
			'address.required' => trans('user_provider_controller.address_neighbour_required')			
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