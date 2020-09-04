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
}