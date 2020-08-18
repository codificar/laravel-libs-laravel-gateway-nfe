<?php
namespace api\v1;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
//FormRequest
use App\Http\Requests\GatewayNFEFormRequest;
//Moedls
use GatewayNFE;
use Log;
class GatewayNFEController extends Controller {   
  
     /**
		* @api{post}/api/v1/gatewey/invoice
		* @apiDescription Cria ou atualiza uma NFs
		* @author  Gustavo Silva <gustavo.silva@codificar.com.br>

		* @param empresaId
		* @param nfeId
		* @param nfeIdExterno
		* @param nfeStatus
		* @param nfeMotivoStatus
		* @param nfeLinkPdf
		* @param nfeLinkXml
		* @param nfeNumero
		* @param nfeCodigoVerificacao
		* @param nfeNumeroRps
		* @param nfeSerieRps
		* @param nfeDataCompetencia

		* @return Json
     */
	public function WeebHookStore(Request $request){	
        $responseData = array('data' => [], 'sucess' => true); 
        try {
			$empresaId = $request->empresaId;
			$nfeId = $request->nfeId;
			$nfeIdExterno = $request->nfeIdExterno;
			$nfeStatus = $request->nfeStatus;
			$nfeMotivoStatus = $request->nfeMotivoStatus;
			$nfeLinkPdf = $request->nfeLinkPdf;
			$nfeLinkXml = $request->nfeLinkXml;
			$nfeNumero = $request->nfeNumero;
			$nfeCodigoVerificacao = $request->nfeCodigoVerificacao;
			$nfeNumeroRps = $request->nfeNumeroRps;
			$nfeSerieRps = $request->nfeSerieRps;
			$nfeDataCompetencia = $request->nfeDataCompetencia;			
			$responseData['data'] = GatewayNFE::updateOrCreate(
					['nfe_id' => $request->nfeId],
					[
						'company_id' => $empresaId,
						'nfe_id' => $nfeId,
						'request_id' => $nfeIdExterno,
						'nfe_external_id' => $nfeIdExterno,
						'nfe_status' => $nfeStatus,
						'nfe_status_reason' => $nfeMotivoStatus,
						'nfe_pdf' => $nfeLinkPdf,
						'nfe_xml' => $nfeLinkXml,
						'nfe_number' => $nfeNumero,
						'nfe_verification_code' => $nfeCodigoVerificacao,
						'nfe_rps_number' => $nfeNumeroRps,
						'nfe_rps_serie' => $nfeSerieRps,
						'nfe_competence_date' => $nfeDataCompetencia,					
					]
			);			
			Log::info('nfeId:'.$nfeId.' \ nfeStatus:'.$nfeStatus.' \ nfeIdExterno:'.$nfeIdExterno);
        } catch (\Throwable $th) {
			Log::error('nfeId:'.$nfeId.' \ nfeStatus:'.$nfeStatus.' \ nfeIdExterno:'.$nfeIdExterno);
            $responseData['sucess'] = false;
			$responseData['errors'] = $th;
			$responseData['error_code'] = 500;
			return response()->json($responseData)->setStatusCode(500);		
        }	
		return response()->json($responseData);	
		
	}
}