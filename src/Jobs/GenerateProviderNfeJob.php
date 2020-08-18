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
use User;
use \Carbon\Carbon;
use Settings;
use Provider;

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
                'descricao' => Settings::getNfeServiceDescription()
            );
            //Get interval to search
            $now = Carbon::now()->format("yy/m/d");
            $latMonth = Carbon::now()->subMonth()->format("yy/m/d");
            $now = "2019/04/01";
            $latMonth = "2020/08/01";

            //Get users and institutions
            $providers = Provider::getProvidersByRequestsInterval($now, $latMonth);
            $users = User::getUsersByRequestsInterval($now, $latMonth);
            $institutions = Institution::getInstitutionByRequestsInterval($now, $latMonth);

            //Users Generate NFE
            GatewayNFE::emmitProviderToUserNfe($providers, $users, $service, $now, $latMonth);
            
            //institutions Generate NFE
            GatewayNFE::emmitProviderToUserNfe($providers, $institutions, $service, $now, $latMonth);

		} catch (Exception $e) {            
			Log::error("Get users and institutions ERROR");
		}
    } 
    
}
