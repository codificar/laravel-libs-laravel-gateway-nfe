<?php

namespace Codificar\GatewayNfe\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Log;
use \Carbon\Carbon;

//Factory
use Codificar\GatewayNfe\Lib\NFEGatewayFactory;
//Internal Model
use Codificar\GatewayNfe\Models\GatewayNFE;
use Codificar\GatewayNfe\Models\Company;
use Codificar\GatewayNfe\Models\NFEUser;
use Codificar\GatewayNfe\Models\NFEInstitution;
use Codificar\GatewayNfe\Models\NFERequests;
use Codificar\GatewayNfe\Models\NFESettings;

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
                'descricao' => NFESettings::getNfeServiceDescription()
            );
            //Get Issuer Company
            $company = Company::getIssuerCompany();
            if($company){
                $companyId  = $company->gateway_company_id;
                //Get interval to search
                $now = Carbon::now()->format("yy/m/d");
                $latMonth = Carbon::now()->subMonth()->format("yy/m/d");

                // $now = "2019/04/01";
                // $latMonth = "2020/08/01";
                //Get users and institutions            
                $users = NFEUser::getUsersByRequestsInterval($now, $latMonth);
                $institutions = NFEInstitution::getInstitutionByRequestsInterval($now, $latMonth);

                //Users Generate NFE
                foreach ($users as $key => $user) {               
                    // $value = (NFERequests::getSumIssuerValue($user['id'], $now, $latMonth) * -1);
                    $value = (NFERequests::getSumIssuerValue($user['id'], $now, $latMonth));
                    // $companyId = "ecda2c6f-333a-4130-8eba-0c01452f0600";
                    if($value > 0) $this->emmit($companyId, $user, $service, $value, GatewayNFE::issuerTypeProvider, GatewayNFE::clientTypeUser);
                    
                }
                //institutions Generate NFE
                foreach ($institutions as $key => $institution) {
                    // $value = (NFERequests::getSumIssuerValue($institution['id'], $now,  $latMonth) * -1);	
                    $value = (NFERequests::getSumIssuerValue($institution['id'], $now,  $latMonth));	
                    // $companyId = "ecda2c6f-333a-4130-8eba-0c01452f0600";
                    if($value > 0) $this->emmit($companyId, $user, $service, $value, GatewayNFE::issuerTypeProvider, GatewayNFE::clientTypeUserInstitution);
                }    
            }            
            Log::error("Not Register issuer company");          
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