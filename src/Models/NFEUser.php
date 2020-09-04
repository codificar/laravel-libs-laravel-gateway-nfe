<?php

namespace Codificar\GatewayNfe\Models;

use Illuminate\Database\Eloquent\Relations\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Eloquent;

/* External Models */
use User;
/* Internal Models */
use Codificar\GatewayNfe\Models\NFEInstitution;

class NFEUser extends User
{
	/**
     * Get Users by requests between 2 dates
     * 
     * @author gustavo.silva <gustavo.silva@codificar.com.br>
     *
	 * @param  Date $dateStart state Name 
     * @param  Date $dateEnd state Name 
	 * @return Array
	*/

    public static function getUsersByRequestsInterval($dateStart, $dateEnd) {
        $users = self::join('request', 'user.id', '=', 'request.user_id')
            ->whereNotIn('user.id', NFEInstitution::getDefaultUsersIds())
            ->whereBetween('request.created_at', array($dateStart, $dateEnd))
            ->where('request.status', 1)
            ->where('request.is_cancelled', 0) 
            ->select(
                'user.id', 
                'user.first_name',
                'user.last_name',
                'user.email',
                'user.document',
                'user.gender', 
                'user.state',
                'user.address_city',
                'user.address',
                'user.address_number',
                'user.address_neighbour',             
                'user.zipcode')
            ->groupBy('user.id')
            ->get();


         
        foreach ($users as $key => $value) {
            $formatedUsers[$key] = array(
                'id' => $value->id,
				'type' => 1,
                'nome' => $value->first_name . " " . $value->last_name,
                'email' => $value->email,
                'cpfCnpj' => $value->document,
                'tipoPessoa' => 'F',
                'endereco' => array(
                    'uf' => self::sanitizeState($value->state), 
                    'cidade' => $value->address_city,
                    'logradouro' => $value->address,
                    'numero' => strval($value->address_number),
                    'bairro' => $value->address_neighbour,
                    'cep' => $value->zipcode
                )
            );
        }
        return $formatedUsers;
    }

    /**
     * Clear a state address string to acronym
     * 
     * @author gustavo.silva <gustavo.silva@codificar.com.br>
     * 
	 * @param  String $string state Name 
	 * @return String
	*/
    private static function sanitizeState($string){
		$state = preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $string ) );
		if(strlen(trim($state)) == 2){
			return $state;
		}else{
			switch (trim($state)) {
				case 'Acre':
					return "AC";
				case 'Alagoas':
					return "AL";
				case 'Amapa':
					return "AP";
				case 'Bahia':
					return "BA";
				case 'Ceara':
					return "CE";
				case 'Distrito Federal':
					return "DF";
				case 'Espírito Santo':
					return "ES";
				case 'Goias':
					return "GO";
				case 'Maranhao':
					return "CE";
				case 'Mato Grosso':
					return "MT";
				case 'Mato Grosso do Sul':
					return "MS";
				case 'Minas Gerais':
					return "MG";
				case 'Para':
					return "PA";
				case 'Paraíba':
					return "PB";
				case 'Parana':
					return "PR";
				case 'Pernambuco':
					return "PE";
				case 'Piaui':
					return "PI";
				case 'Rio de Janeiro':
					return "RJ";
				case 'Rio Grande do Norte':
					return "RN";
				case 'Rio Grande do Sul':
					return "RS";
				case 'Rondonia':
					return "RO";
				case 'Roraima':
					return "RR";
				case 'Santa Catarina':
					return "SC";
				case 'Sao Paulo':
					return "SP";
				case 'Sergipe':
					return "SE";
				case 'Tocantins':
					return "TO";
			}
		}	
	}
}