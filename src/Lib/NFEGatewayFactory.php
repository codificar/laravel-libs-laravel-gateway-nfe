<?php
namespace Codificar\GatewayNfe\Lib;

//External Models
use Settings;

class NFEGatewayFactory {

    const PAYMENT_GATEWAY_ENOTAS = 'enotas';
   

    public static function createGateway() {
        switch (Settings::findByKey('nfe_gateway')) {
            case self::PAYMENT_GATEWAY_ENOTAS:
            default:
                return(new eNotasLib());           
        }
    }
}

?>
