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
use Codificar\GatewayNfe\Models\NFEProvider;


class GenerateProviderNfeJob implements ShouldQueue
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
            //Get interval to search
            $now = Carbon::now()->format("yy/m/d");
            $latMonth = Carbon::now()->subMonth()->format("yy/m/d");
            $now = "2019/04/01";
            $latMonth = "2020/08/01";

            //Get users and institutions
            $providers = NFEProvider::getProvidersByRequestsInterval($now, $latMonth);
            $users = NFEUser::getUsersByRequestsInterval($now, $latMonth);
            $institutions = NFEInstitution::getInstitutionByRequestsInterval($now, $latMonth);

            //Users Generate NFE
            GatewayNFE::emmitProviderToUserNfe($providers, $users, $service, $now, $latMonth);
            
            //institutions Generate NFE
            GatewayNFE::emmitProviderToUserNfe($providers, $institutions, $service, $now, $latMonth);

		} catch (Exception $e) {            
			Log::error("Get users and institutions ERROR");
		}
    } 
    
}
