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


class SimulateGenerateProviderNfeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $startDate;
	protected $endDate;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($startDate, $endDate)
	{        
        $this->startDate = $startDate;
		$this->endDate = $endDate;
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
            $now = $this->startDate;
            $latMonth = $this->endDate;

            //Get users and institutions
            $providers = NFEProvider::getProvidersByRequestsInterval($now, $latMonth);
            $users = NFEUser::getUsersByRequestsInterval($now, $latMonth);
            $institutions = NFEInstitution::getInstitutionByRequestsInterval($now, $latMonth);
            
            //Users Generate NFE
            GatewayNFE::emmitProviderToUserNfe($providers, $users, $service, $now, $latMonth);
            
            //institutions Generate NFE
            GatewayNFE::emmitProviderToInstitutionNfe($providers, $institutions, $service, $now, $latMonth);

		} catch (Exception $e) {            
			Log::error("Get users and institutions ERROR");
		}
    } 
    
}
