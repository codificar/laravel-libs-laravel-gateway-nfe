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
}