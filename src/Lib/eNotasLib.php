<?php

namespace Codificar\GatewayNfe\Lib;

require base_path('vendor/enotas/php-client/src/eNotasGW.php');


use eNotasGW;
use eNotasGW\Api\Exceptions as Exceptions;
use eNotasGW\Api\fileParameter as fileParameter;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

//Internal Model
use Codificar\GatewayNfe\Models\NFESettings;
use Codificar\GatewayNfe\Models\Company;
/**
 * Classe usada para implementar o https://github.com/eNotasGW/php-client
 *
 * @package MotoboyApp
 *
 * @author Gustavo Silva
 */

class eNotasLib
{ 
    //Vars
    private $eNotas;

    //tipoAutenticacao
    const Nenhuma = 0;
    const Certificado = 1;
    const UsuarioESenha = 2;
    const Token = 3;
    //tipoAssinaturaDigital
    const NaoUtiliza = 0;
    const Opcional = 1;
    const Obrigatorio = 2;

    /**
	 * @author Gustavo Silva <gustavo.silva@codificar.com.br>
     * Constructor - New     
     * @param string $environment = Homologacao' OR 'Producao'
     */
    public function __construct()
    {
        //Trazer da tabela settings
        $this->eNotas = eNotasGW::configure(array(
            'apiKey' => NFESettings::getEnotasApiKey()
        ));		
		$this->environment = NFESettings::getEnotasEnv();
	}
	

	public function createCompany(Company $company){		
		$responseData = array('data' => [], 'sucess' => true); 
		try {			
			$caracteristicasPrefeitura = eNotasGW::$PrefeituraApi->consultar($company->ibge_code);		
			$dadosEmpresa = array(						
				'cnpj' => $company->document,
				'inscricaoMunicipal' => $company->municipal_registration,
				'inscricaoEstadual' => $company->estadual_registration,
				'razaoSocial' => $company->social_reason,
				'nomeFantasia' => $company->fantasy_name,
				'optanteSimplesNacional' => $company->national_simple_optant == 1 ? true : false,
				'incentivadorCultural' => $company->cultural_promoter == 1 ? true : false,			
				'email' => $company->commercial_email,
				'telefoneComercial' => $company->commercial_phone,
				'endereco' => array(
					'uf' => $company->estate, 
					'cidade' => $company->city,
					'logradouro' => $company->place,
					'numero' => $company->number,
					'complemento' => $company->complement,
					'bairro' => $company->neighborhood,
					'cep' => $company->zipcode
				),
				'regimeEspecialTributacao' => '0', 
				'codigoServicoMunicipal' => '1234', 
				'descricaoServico' => 'SERVICO DE SERIGRAFIA / SILK-SCREEN',
				'aliquotaIss' => 2.00,
				'configuracoesNFSeProducao' => array(
					'sequencialNFe' => 1,
					'serieNFe' => '2',
					'sequencialLoteNFe' => 1
				),
				'configuracoesNFSeHomologacao' => array(
					'sequencialNFe' => 1,
					'serieNFe' => '2',
					'sequencialLoteNFe' => 1
				)
			);			
			
			if($caracteristicasPrefeitura->usaCNAE) {
				$dadosEmpresa['cnae'] = '1813099';
			}
			
			if($caracteristicasPrefeitura->usaItemListaServico) {
				$dadosEmpresa['itemListaServicoLC116'] = '13.05';
			}
			
			//Register Company
			$response = eNotasGW::$EmpresaApi->inserirAtualizar($dadosEmpresa);	
			//Save On Database
			$company->gateway_company_id = $response->empresaId;
			$company->save();

			$responseData['data'] = $response->empresaId;
		}
		catch(Exceptions\invalidApiKeyException $ex) {
			$responseData['error_code'] = 'Erro de autenticação: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['errors'] = $ex->getMessage(); 
		}
		catch(Exceptions\unauthorizedException $ex) {
			$responseData['error_code'] = 'Acesso negado: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['errors'] = $ex->getMessage(); 
		}
		catch(Exceptions\apiException $ex) {
			$responseData['error_code'] = 'Erro de validação: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['errors'] = $ex->getMessage(); 
		}
		catch(Exceptions\requestException $ex) {
			$responseData['error_code'] = 'Erro na requisição web: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['errors'] = $ex->getMessage(); 			
		}
		return $responseData;
	}

	public function updateCompany(Company $company){	
		$responseData = array('data' => [], 'sucess' => true); 
		try {			
			$caracteristicasPrefeitura = eNotasGW::$PrefeituraApi->consultar($company->ibge_code);		
			$dadosEmpresa = array(		
				'id' => $company->gateway_company_id,			
				'cnpj' => $company->document,
				'inscricaoMunicipal' => $company->municipal_registration,
				'inscricaoEstadual' => $company->estadual_registration,
				'razaoSocial' => $company->social_reason,
				'nomeFantasia' => $company->fantasy_name,
				'optanteSimplesNacional' => $company->national_simple_optant == 1 ? true : false,
				'incentivadorCultural' => $company->cultural_promoter == 1 ? true : false,			
				'email' => $company->commercial_email,
				'telefoneComercial' => $company->commercial_phone,
				'endereco' => array(
					'uf' => $company->estate, 
					'cidade' => $company->city,
					'logradouro' => $company->place,
					'numero' => $company->number,
					'complemento' => $company->complement,
					'bairro' => $company->neighborhood,
					'cep' => $company->zipcode
				),
				'regimeEspecialTributacao' => '0', 
				'codigoServicoMunicipal' => '1234', 
				'descricaoServico' => 'SERVICO DE SERIGRAFIA / SILK-SCREEN',
				'aliquotaIss' => 2.00,
				'configuracoesNFSeProducao' => array(
					'sequencialNFe' => 1,
					'serieNFe' => '2',
					'sequencialLoteNFe' => 1
				),
				'configuracoesNFSeHomologacao' => array(
					'sequencialNFe' => 1,
					'serieNFe' => '2',
					'sequencialLoteNFe' => 1
				)
			);			
		
			if($caracteristicasPrefeitura->usaCNAE) {
				$dadosEmpresa['cnae'] = '1813099';
			}
			
			if($caracteristicasPrefeitura->usaItemListaServico) {
				$dadosEmpresa['itemListaServicoLC116'] = '13.05';
			}			
			//Register Company
			$response = eNotasGW::$EmpresaApi->inserirAtualizar($dadosEmpresa);	
			
			$responseData['data'] = $response->empresaId;
		}
		catch(Exceptions\invalidApiKeyException $ex) {
			$responseData['error_code'] = 'Erro de autenticação: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['errors'] = $ex->getMessage(); 
		}
		catch(Exceptions\unauthorizedException $ex) {
			$responseData['error_code'] = 'Acesso negado: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['errors'] = $ex->getMessage(); 
		}
		catch(Exceptions\apiException $ex) {
			$responseData['error_code'] = 'Erro de validação: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['errors'] = $ex->getMessage(); 
		}
		catch(Exceptions\requestException $ex) {
			$responseData['error_code'] = 'Erro na requisição web: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['errors'] = $ex->getMessage(); 			
		}
		return $responseData;
	}

	public function getCompany($gateway_company_id) { 	
		$client = new \GuzzleHttp\Client();		
		try {
			//cria instância para chamada			
			$response = $client->request("GET", "https://api.enotasgw.com.br/v1/empresas/".$gateway_company_id, [
			'headers' => [
				'Authorization'     =>  "Basic ".NFESettings::getEnotasApiKey()
				]
			]);					
			$xml = $response->getBody()->getContents();
			$data = simplexml_load_string($xml);
			return $data;	
		} catch (RequestException $e) {
			if ($e->hasResponse()) {
				return Psr7\str($e->getResponse());
			}
		}	     
		
	}
	
	public function getAuthType($ibgecode) { 
		$responseData = array('data' => [], 'sucess' => true); 
		try {
			$responseData['data'] = eNotasGW::$PrefeituraApi->consultar($ibgecode);
		} catch (\Throwable $th) {
			$responseData['data'] = $th; 
			$responseData['sucess'] = false; 			
		}
		return $responseData;
	}

	
	public function loginAuthCompany(Company $company, $login, $password) { 			
		$responseData = array('data' => [], 'sucess' => true); 		
        try {	
				
			$dadosEmpresa['id'] = $company->gateway_company_id;			
			$dadosEmpresa['configuracoesNFSeProducao']['usuarioAcessoProvedor'] = $login;
			$dadosEmpresa['configuracoesNFSeProducao']['senhaAcessoProvedor'] = $password;			
			//opcional, preencher apenas se for emitir em ambiente de homologação
			$dadosEmpresa['configuracoesNFSeHomologacao']['usuarioAcessoProvedor'] = $login;
			$dadosEmpresa['configuracoesNFSeHomologacao']['senhaAcessoProvedor'] = $password; 				
			
			//Register Company
			$responseData['data'] = eNotasGW::$EmpresaApi->inserirAtualizar($dadosEmpresa);	
		}
		catch(Exceptions\invalidApiKeyException $ex) {
			$responseData['data'] = 'Erro de autenticação: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['error'] = $ex->getMessage(); 
		}
		catch(Exceptions\unauthorizedException $ex) {
			$responseData['data'] = 'Acesso negado: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['error'] = $ex->getMessage(); 
		}
		catch(Exceptions\apiException $ex) {
			$responseData['data'] = 'Erro de validação: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['error'] = $ex->getMessage(); 
		}
		catch(Exceptions\requestException $ex) {
			$responseData['data'] = 'Erro na requisição web: </br></br>'; 
			$responseData['sucess'] = false; 
			$responseData['error'] = $ex->getMessage(); 			
		}
		return $responseData;
	}	

	public function setCompanyCertifie(Company $company, $certifie_file_path, $password) { 			
		$client = new \GuzzleHttp\Client();
		$responseData = array('data' => [], 'sucess' => true); 
		try {
			
			$response = $client->request('POST', 'https://api.enotasgw.com.br/v1/empresas/'.$company->gateway_company_id.'/certificadoDigital', [
				'headers' => [
					'Authorization'     =>  "Basic ".NFESettings::getEnotasApiKey()
				],
				'multipart' => [
					[
						'name' => 'empresaId',
						'contents' => $company->gateway_company_id,
						
					],
					[					
						'name' => 'senha',
						'contents' => $password
					],
					[						
						'name' => 'arquivo',
						'contents' => fopen($certifie_file_path, "r")
					],
				]			
				]);			
				
			$xml = simplexml_load_string($response->getBody()->getContents());
			$responseData["xml"] = $xml;
			$responseData["data"] = "Certificado Atualizado";
		} catch (RequestException $e) {
			$responseData["sucess"] = false;
			if ($e->hasResponse()) {
				$responseData["data"] = $this->getTextBetweenTags(Psr7\str($e->getResponse()), 'mensagem');
			}
		}	
		return $responseData;		
	}
	private function getTextBetweenTags($string, $tagname) {
		$pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
		preg_match($pattern, $string, $matches);
		if(isset($matches[1])) {
			return $matches[1];
		}else{
			return "Erro interno";
		}
		 
		
	}
	
    public function generateNfe($requestId, $companyId, $client, $service, $value) {       
		$responseData = array('data' => [], 'sucess' => true);       
        try{					
            $nfeId = eNotasGW::$NFeApi->emitir($companyId, array(
                'tipo' => 'NFS-e',
                'idExterno' => $requestId,
                'ambienteEmissao' => $this->environment, 		
                'cliente' => $client,
                'servico' => $service,
				'valorTotal' => $value,
				"enviarPorEmail" => true,
				"dadosAdicionaisEmail" => array(
					"outrosDestinatarios" => NFESettings::getNfeCopyEmail()
				),
            ));		
            $responseData['data'] = $nfeId;
        }
        catch(Exceptions\invalidApiKeyException $ex) {
			$responseData['sucess'] = false;
            $responseData['error'] = $ex->getMessage();
            $responseData['data'] = 'Erro de autenticação: </br></br>';
        }
        catch(Exceptions\unauthorizedException $ex) {
			$responseData['sucess'] = false;
            $responseData['error'] = $ex->getMessage();
            $responseData['data'] = 'Acesso negado: </br></br>';
        }
        catch(Exceptions\apiException $ex) {
			$responseData['sucess'] = false;
            $responseData['error'] = $ex->getMessage();
            $responseData['data'] = 'Erro de validação: </br></br>';
        }
        catch(Exceptions\requestException $ex) {
			$responseData['sucess'] = false;
			$responseData['error'] = $ex->getMessage();
            $responseData['data'] = 'Erro na requisição web';
        }
        return $responseData;
	}
	
	public function getNfeByRequestId($companyId, $requestId) { 	
		
		$client = new \GuzzleHttp\Client();
		$responseData = array('data' => [], 'sucess' => true); 
		try {
			
			$response = $client->request('GET', 'https://api.enotasgw.com.br/v1/empresas/'.$companyId.'/nfes/porIdExterno/'.$requestId, [
				'headers' => [
					'Authorization'     =>  "Basic ".NFESettings::getEnotasApiKey()
				]
			]);			
				
			$json = simplexml_load_string($response->getBody()->getContents());
			$responseData["data"] = $json;
		} catch (RequestException $e) {
			$responseData["sucess"] = false;
			if ($e->hasResponse()) {
				$responseData["data"] = $this->getTextBetweenTags(Psr7\str($e->getResponse()), 'mensagem');
			}
		}	
		return $responseData;		
	}
	
	/** 
        * @author Gustavo Silva <gustavo.silva@codificar.com.br>
        * 
        * @param integer $ibgeCode
        
        * @return Status
    */

    public function getPrefeiturasSettings($ibgeCode) {
		$responseData = array('data' => [], 'sucess' => true); 		
        try {			
			$responseData['data'] = eNotasGW::$PrefeituraApi->consultar($ibgeCode);
		} catch (Exception $error) {
			$responseArray["data"] = $error->getMessage();
			$responseArray["sucess"] = false;
		}
		return $responseData;
	} 	

	/** 
        * @author Gustavo Silva <gustavo.silva@codificar.com.br>
        * 
        * @param integer $ibgeCode
        
        * @return Status
    */

    public function getPrefeiturasServicos($ibgeCode) {
		$responseData = array('data' => [], 'sucess' => true); 		
        try {			
			$responseData['data'] = eNotasGW::$PrefeituraApi->consultar($ibgeCode);
		} catch (Exception $error) {
			$responseArray["data"] = $error->getMessage();
			$responseArray["sucess"] = false;
		}
		return $responseData;
	} 	
    
   
}