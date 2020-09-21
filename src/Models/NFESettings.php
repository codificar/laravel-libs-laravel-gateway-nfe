<?php

namespace Codificar\GatewayNfe\Models;

use Illuminate\Database\Eloquent\Relations\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Eloquent;

use Settings;
class NFESettings extends Settings
{

	/**
	 * Get Emmit Nfe enable
	 *
	 * @return string
	 */
	public static function getNfeGatewayEnable()
	{
		$settings = Settings::where('key', 'nfe_gateway_enable')->first();

		if ($settings){
			$cleanValue =  str_replace(" ", "", $settings->value);
			$cleanValue == '1' ? $cleanValue  = true : $cleanValue = false;
			return $cleanValue;
		}else
			return false;
	}

	/**
	 * Get eNotas API key Gateway
	 *
	 * @return string
	 */
	public static function getEnotasApiKey()
	{
		$settings = Settings::where('key', 'nfe_gateway_api_key')->first();

		if ($settings)
			return str_replace(" ", "", $settings->value);
		else
			return null;
	}

	/**
	 * Get eNotas Env
	 *
	 * @return string
	 */
	public static function getEnotasEnv()
	{
		$settings = Settings::where('key', 'nfe_gateway_env')->first();

		if ($settings)
			return str_replace(" ", "", $settings->value);
		else
			return null;
	}

	/**
	 * Get WeebHook Key
	 *
	 * @return string
	 */
	public static function getNfeWeebHookKey()
	{
		$settings = Settings::where('key', 'nfe_gateway_weebhook_key')->first();

		if ($settings)
			return str_replace(" ", "", $settings->value);
		else
			return null;
	}

	/**
	 * Get Copy email
	 *
	 * @return string
	 */
	public static function getNfeCopyEmail()
	{
		$settings = Settings::where('key', 'nfe_gateway_copy_email')->first();

		if ($settings)
			return str_replace(" ", "", $settings->value);
		else
			return "teste@codificar.com.br";
	}

	/**
	 * Get Emmit Provider NFE Day
	 *
	 * @return string
	 */
	public static function getNfeProviderEmissionDay()
	{
		$settings = Settings::where('key', 'nfe_gateway_provider_emission_day')->first();

		if ($settings)
			return str_replace(" ", "", $settings->value);
		else
			return 1;
	}

	/**
	 * Get Emmit Issuer NFE Day
	 *
	 * @return string
	 */
	public static function getNfeIssuerEmissionDay()
	{
		$settings = Settings::where('key', 'nfe_gateway_issuer_emission_day')->first();

		if ($settings)
			return str_replace(" ", "", $settings->value);
		else
			return 1;
	}

	/**
	 * Get NFE Service Description
	 *
	 * @return string
	 */
	public static function getNfeServiceDescription()
	{
		$settings = Settings::where('key', 'nfe_gateway_service_description')->first();

		if ($settings)
			return $settings->value;
		else
			return "Serviço de Motoboy";
	}


	public function getIssuerData()
	{
		return [
			'name' => self::findByKey('issuer_name'),
			'document' => self::findByKey('issuer_document'),
			'zipcode' => self::findByKey('issuer_zipcode'),
			'address' => self::findByKey('issuer_address'),
			'email' => self::findByKey('issuer_email'),
			'site' => self::findByKey('issuer_site'),
			'phone' => self::findByKey('issuer_phone'),
		];
	}
	// Issuer Set Settings
	public function setIssuerName($value)
	{
		Settings::where('key', 'issuer_name')->update(['value' => $value]);
	}

	public function setIssuerDocument($value)
	{
		Settings::where('key', 'issuer_document')->update(['value' => $value]);
	}

	public function setIssuerZipcode($value)
	{
		Settings::where('key', 'issuer_zipcode')->update(['value' => $value]);
	}

	public function setIssuerAddress($value)
	{
		Settings::where('key', 'issuer_address')->update(['value' => $value]);
	}

	public function setIssuerEmail($value)
	{
		Settings::where('key', 'issuer_email')->update(['value' => $value]);
	}

	public function setIssuerSite($value)
	{
		Settings::where('key', 'issuer_site')->update(['value' => $value]);
	}

	public function setIssuerPhone($value)
	{
		Settings::where('key', 'issuer_phone')->update(['value' => $value]);
	}
}