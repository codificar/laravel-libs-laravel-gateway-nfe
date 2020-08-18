<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Institution;

use Log;
use NFEGatewayFactory;
use GatewayNFE;
use Requests;
use Settings;
use User;
use \Carbon\Carbon;
use Company;

class GenerateIssuerNfeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
	{        
		
	}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $service = array(
                'descricao' => Settings::getNfeServiceDescription()
            );
            //Get Issuer Company
            $company = Company::getIssuerCompany();
            $companyId  = $company->gateway_company_id;
            //Get interval to search
            $now = Carbon::now()->format("yy/m/d");
            $latMonth = Carbon::now()->subMonth()->format("yy/m/d");

            $now = "2019/04/01";
            $latMonth = "2020/08/01";
            //Get users and institutions            
            $users = User::getUsersByRequestsInterval($now, $latMonth);
            $institutions = Institution::getInstitutionByRequestsInterval($now, $latMonth);

            //Users Generate NFE
            foreach ($users as $key => $user) {               
                $value = (Requests::getSumIssuerValue($user['id'], $now, $latMonth) * -1);
                $companyId = "ecda2c6f-333a-4130-8eba-0c01452f0600";
                if($value > 0) $this->emmit($companyId, $user, $service, $value, GatewayNFE::issuerTypeProvider, GatewayNFE::clientTypeUser);
                
            }
            //institutions Generate NFE
            foreach ($institutions as $key => $institution) {
                $value = (Requests::getSumIssuerValue($institution['id'], $now,  $latMonth) * -1);	
                $companyId = "ecda2c6f-333a-4130-8eba-0c01452f0600";
                if($value > 0) $this->emmit($companyId, $user, $service, $value, GatewayNFE::issuerTypeProvider, GatewayNFE::clientTypeUserInstitution);
            }    
                         
		} catch (Exception $e) {            
			Log::error("Get users and institutions ERROR");
		}
    } 

    private function emmit($companyId, $client, $service, $value, $issuerType, $clientType){
        //Store on database
        $gatewayNFE = new GatewayNFE;
        $gatewayNFE->company_id = $companyId;
        $gatewayNFE->save();
        
        //Create on Gateway		
        $gateway = NFEGatewayFactory::createGateway();
       
        //Create NFE on Gateway
        $response = $gateway->generateNfe(strval($gatewayNFE->id), $companyId, $client, $service, $value);

        if($response['sucess']){               
            $response = $gateway->getNfeByRequestId($companyId, $gatewayNFE->id);
            $gatewayNFE = GatewayNFE::gatewayDataStore($gatewayNFE->id, $response['data'], $value, $issuerType, $clientType);
            Log::info("Generate NFE Success");
        }else{
            Log::info("Generate NFE Error");
        }
    }
}