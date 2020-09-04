<?php

namespace Codificar\GatewayNfe\Models;

use Illuminate\Database\Eloquent\Relations\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Eloquent;

/* External Models */
use App\Models\Institution;

class NFEInstitution extends Institution {
    /**
     * Get institution users id
     * 
     * @author gustavo.silva <gustavo.silva@codificar.com.br>
     * 
	 * @return Array
	*/
	public function getDefaultUsersIds(){
		return self::WhereNotNull('institution.default_user_id')
			->pluck('default_user_id')->toArray();
    }
    
    /**
     * Get Institutions by requests between 2 dates
     * 
     * @author gustavo.silva <gustavo.silva@codificar.com.br>
     * 
	 * @param  Date $dateStart state Name 
     * @param  Date $dateEnd state Name 
	 * @return Array
	*/

	public function getInstitutionByRequestsInterval($dateStart, $dateEnd) {
		$users = self::join('user', 'user.id', '=', 'institution.default_user_id')
			->join('address', 'institution.address_id', '=', 'address.id')
			->join('request', 'user.id', '=', 'request.user_id')
            ->whereBetween('request.created_at', array($dateStart, $dateEnd))
			->where('request.status', 1)
			->where('request.is_cancelled', 0) 
            ->select(
				'institution.id as institution_id', 
                'user.id', 
                'institution.name as first_name',
                'institution.social_reason as last_name',
                'user.email',
                'institution.document',
                'user.gender', 
                'address.state',
                'address.city as address_city',
                'address.street as address',
                'address.number as address_number',
                'address.district as address_neighbour',             
                'address.zip_code as zipcode')
            ->groupBy('user.id')
			->get();
			
		foreach ($users as $key => $value) {
			$gender = $value->gender == 'male' ? 'M' : 'F';
			$formatedUsers[$key] = array(
				'id' => $value->id,
				'type' => 2,
				'nome' => $value->first_name . " " . $value->last_name,
				'email' => $value->email,
				'cpfCnpj' => $value->document,
				'tipoPessoa' => "J",
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