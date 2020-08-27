<?php 
namespace Codificar\GatewayNfe\Lib;

interface NFEGatewayInterface
{
    
    /**
     * Create Company
     *
     * @param String   $document
     * @param String   $municipalRegistration      
     * @param String   $razaoSocial 
     * @param String   $nomeFantasia      
     * @param Array    $address [estate, city, place, number, neighborhood, zipcode, complement, ibgeCode]
     * @param Bool     $nationalSimpleOptant Default True     
     * @param Bool     $culturalPromoter   Default False
     * @param String   $estadualRegistration Default Null
     * @param Email    $commercialEmail Default Null
     * @param String   $commercialPhone Default null
     
     * 
     * @return Array ['success', 'data']
     * @return Array ['success', 'data', 'error]
    */        
    public function createCompany(Company $providerCompany);   

    /**
     * Update Company
     *
     * @param String   $gateway_company_id
     * @param String   $document
     * @param String   $municipalRegistration      
     * @param String   $razaoSocial 
     * @param String   $nomeFantasia      
     * @param Array    $address [estate, city, place, number, neighborhood, zipcode, complement, ibgeCode]
     * @param Bool     $nationalSimpleOptant Default True     
     * @param Bool     $culturalPromoter   Default False
     * @param String   $estadualRegistration Default Null
     * @param Email    $commercialEmail Default Null
     * @param String   $commercialPhone Default null
     
     * 
     * @return Array ['success', 'data']
     * @return Array ['success', 'data', 'error]
    */    
    public function updateCompany(Company $providerCompany); 

    /**
     * Get Company
     *
     * @param gateway_company_id   $gateway_company_id    
    
     * @return Array ['success', 'data']
     * @return Array ['success', 'data', 'error]
    */  
    public function getCompany($gateway_company_id);   

    /**
     * Generate NFE
     *
     * @param Number   $requestId
     * @param String   $companyId
     * @param Array   $client      
     * @param Number   $value   
     
     * 
     * @return Array ['success', 'data']
     * @return Array ['success', 'data', 'error]
    */    
    public function generateNfe($requestId, $companyId, $client, $service, $value); 

    /**
     * GET NFE data by Request ID
     *
     * @param Number   $requestId
     * @param String   $companyId
     
     * 
     * @return Array ['success', 'data']
     * @return Array ['success', 'data', 'error]
    */    
    public function getNfeByRequestId($companyId, $requestId); 
    
}

?>