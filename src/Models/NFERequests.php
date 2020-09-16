<?php

namespace Codificar\GatewayNfe\Models;

use Illuminate\Database\Eloquent\Relations\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Eloquent;

/* External Models */
use Requests;

class NFERequests extends Requests {
	/**
	 * Method of calculating the total value of the system administrator between 2 data
	 * 
     * @author gustavo.silva <gustavo.silva@codificar.com.br>     
    */
    public static function getSumIssuerValue($user_id, $dateStart, $dateEnd) {              
        $requests = self::whereBetween('request.created_at', array($dateStart, $dateEnd))
            ->where('request.status', self::Completed)
            ->where('user_id', $user_id)
            ->get();
        $sumCompanyValue = 0; 
        //percorre corridas
        foreach ($requests as $request) {
            //busca fatura consolidad
            $invoice = (array) json_decode($request->invoice);            
            //atribui e soma valores           
            if(count($invoice)){  
                $sumCompanyValue = $sumCompanyValue + $invoice['company_value'];                 
            }
        }

        return $sumCompanyValue;
    }

    /**
     * @author Gustavo Silva
     * Método que calcula o valor total do provider entre 2 datas, por técnico e empresa
     */
    public static function getProviderValueByUser($provider_id, $user_id, $dateStart, $dateEnd)
    {   
        $requests = self::whereBetween('request.created_at', array($dateStart, $dateEnd))
            ->where('request.status', self::Completed)
            ->where('request.confirmed_provider', $provider_id)
            ->where('request.is_cancelled', 0) 
            ->where('request.user_id', $user_id)
            ->join('provider', 'request.confirmed_provider', '=', 'provider.id')
            ->leftJoin('companies', 'provider.id', '=', 'companies.provider_id')
            ->select(
                'request.invoice'
            )
            ->get();
          
        $sumProviderValue = 0; 
        //percorre corridas
        foreach ($requests as $request) {
            //busca fatura consolidad
            $invoice = (array) json_decode($request->invoice);            
            //atribui e soma valores
            if(count($invoice)){  
                $sumProviderValue = $sumProviderValue + $invoice['provider_value'];                 
            }
        }

        return $sumProviderValue;
    }
}