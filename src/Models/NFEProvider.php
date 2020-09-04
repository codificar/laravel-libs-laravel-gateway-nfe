<?php

namespace Codificar\GatewayNfe\Models;

use Illuminate\Database\Eloquent\Relations\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Eloquent;

/* External Models */
use Provider;
/* Internal Models */

class NFEProvider extends Provider
{
	/**
     * * @author gustavo.silva <gustavo.silva@codificar.com.br>
     * * @description Get Providers by requests between 2 dates
	 * * @param  Date $dateStart state Name 
     * * @param  Date $dateEnd state Name 
	 * * @return Array
	*/

    public static function getProvidersByRequestsInterval($dateStart, $dateEnd) {
        $providers = self::join('request', 'provider.id', '=', 'request.confirmed_provider')
            ->join('companies', 'provider.id', '=', 'companies.provider_id')
            ->whereBetween('request.created_at', array($dateStart, $dateEnd))
            ->where('request.status', 1)
            ->where('request.is_cancelled', 0) 
            ->select(
                'provider.id as provider_id', 
                'companies.gateway_company_id'
            )
            ->groupBy('provider.id')
            ->get();

        return $providers;
    }
}