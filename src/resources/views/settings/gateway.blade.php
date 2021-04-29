@extends('layout.master')
@section('breadcrumbs')
<div class="row page-titles">
	<div class="col-md-6 col-8 align-self-center">
		<h3 class="text-themecolor m-b-0 m-t-0">{{ trans("setting.conf") }}</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="javascript:void(0)">{{ trans("dashboard.home") }}</a></li>
			<li class="breadcrumb-item active">{{ trans("setting.conf") }}</li>
			<li class="breadcrumb-item active">NFE</li>
		</ol>
	</div>
</div>	
@stop

@section('content')
<div class="col-lg-12">
	<div class="card card-outline-info">
		<div class="card-header">
			<h4 class="m-b-0 text-white">{{ trans("gatewayNfe::gateway_nfe.nfe_settings") }}</h4>
			</div>
			<div class="card-block">
		<form enctype="multipart/form-data" method="post" data-toggle="validator" action="{{ URL::Route('saveNfeSettings') }}">		
			<div class="row">
				<div class="col-md-5">
					<!--General Settings-->
					<div class="panel panel-primary" id="panel-zenvia">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="gateway">Gateway</label>
										<select class="select form-control select-gateway" name="nfe_gateway">
											<option value="enotas">
												eNotas
											</option>											
										</select>
									</div>
								</div>

								<div class="col-md-8">
									<div class="form-group">
										<label for="usr">
											{{ trans("gatewayNfe::gateway_nfe.api_key") }}
											<a href="#" class="question-field" data-toggle="tooltip" title="Chave de API disponível no painel do eNotas"><span class="mdi mdi-comment-question-outline"></span></a> <span class="required-field">*</span>
										</label>
										<input  id="gatewayApiKey" onblur="validateKey(this.value)" type="text" class="form-control" name="nfe_gateway_api_key" required data-error="{{trans('setting.field')}}" value="{{$model->nfe_gateway_api_key->value}}">
										<div class="help-block with-errors"></div>
									</div>									
								</div>
							</div>
						</div>
				</div>
				</div>
			
				<div id="isLoading">
					<svg class="circular" viewBox="25 25 50 50">
						<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
					</svg>
				</div>
				
			</div>			
			
			
			<div class="panel panel-default panel-gateway" style="display: none;" id="enotasSettingPainel">		
				
			<div class="panel-heading">
					<h2 class="panel-title">{{ trans("gatewayNfe::gateway_nfe.enotas_settings") }}</h2>
					<hr>
				</div>
				<h4 class="panel-title">{{ trans("gatewayNfe::gateway_nfe.webhook_rote") }}: {{ URL::Route('nfeWebHook') }}</h4>
				<hr>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-3">
							<div class="form-group">
								<label for="gateway">{{ trans("gatewayNfe::gateway_nfe.enable") }}</label>
								<select class="select form-control" name="nfe_gateway_enable">
									<option value="{{0}}" {{ $model->nfe_gateway_enable->value == "0" ? "selected" : "" }} >
										{{ trans("gatewayNfe::gateway_nfe.no") }}
									</option>		
									<option value="{{1}}" {{ $model->nfe_gateway_enable->value == "1" ? "selected" : ""}} >
										{{ trans("gatewayNfe::gateway_nfe.yes") }}
									</option>																					
								</select>
							</div>
						</div>	

						<div class="col-lg-3">
							<label for="gatewayWeebHookKey">{{ trans("gatewayNfe::gateway_nfe.webhook_key") }}</label>	
							<div class="input-group">												
								<input id="gatewayWeebHookKey" type="email" class="form-control" name="nfe_gateway_weebhook_key" required data-error="{{trans('setting.field')}}"
								value="{{$model->nfe_gateway_weebhook_key->value}}" readonly>
								<div class="input-group-prepend">
									<button onClick="generateHash()" class="p-2 btn btn-info" type="button">{{ trans("gatewayNfe::gateway_nfe.generate_key") }}</button>
								</div>
							</div>
						</div>	
						
						<div class="col-lg-3">
							<div class="form-group">
								<label for="usr">
									{{ trans("gatewayNfe::gateway_nfe.mail_copy") }}
									<a href="#" class="question-field" data-toggle="tooltip" title="Email para cópia das notas"><span class="mdi mdi-comment-question-outline"></span></a> <span class="required-field">*</span>
								</label>
								<input type="email" class="form-control" name="nfe_gateway_copy_email" required data-error="{{trans('setting.field')}}" value="{{$model->nfe_gateway_copy_email->value}}">
								<div class="help-block with-errors"></div>
							</div>
						</div>	
						<div class="col-lg-3">
							<div class="form-group">
								<label for="gateway">{{ trans("gatewayNfe::gateway_nfe.enotas_env") }}</label>
									<select class="select form-control" name="nfe_gateway_env">
										<option value="Producao" {{ $model->nfe_gateway_env->value == "Producao" ? "selected" : ""}} >
											{{ trans("gatewayNfe::gateway_nfe.prod") }}
										</option>	
										<option value="Homologacao" {{ $model->nfe_gateway_env->value == "Homologacao" ? "selected" : "" }} >
											{{ trans("gatewayNfe::gateway_nfe.hom") }}
										</option>											
									</select>
							</div>
						</div>						
					</div>		

					<div class="row">
					<div class="col-lg-3">
							<div class="form-group">
								<label for="usr">
									{{trans('gatewayNfe::gateway_nfe.note_description') }}
									<a href="#" class="question-field" data-toggle="tooltip" title="{{trans('gatewayNfe::gateway_nfe.note_description_title') }}"><span class="mdi mdi-comment-question-outline"></span></a> <span class="required-field">*</span>
								</label>
								<input min="1" max="255" type="text" class="form-control" name="nfe_gateway_service_description" required data-error="{{trans('setting.field')}}" value="{{$model->nfe_gateway_service_description->value}}">
								<div class="help-block with-errors"></div>
							</div>
						</div>	
						<div class="col-lg-3">
							<div class="form-group">
								<label for="usr">
									{{trans('gatewayNfe::gateway_nfe.motoboy_nfe_emmit_date') }}
									<a href="#" class="question-field" data-toggle="tooltip" title="{{trans('gatewayNfe::gateway_nfe.motoboy_nfe_emmit_date_title') }}"><span class="mdi mdi-comment-question-outline"></span></a> <span class="required-field">*</span>
								</label>
								<input min="1" max="31" type="number" class="form-control" name="nfe_gateway_provider_emission_day" required data-error="{{trans('setting.field')}}" value="{{$model->nfe_gateway_provider_emission_day->value}}">
								<div class="help-block with-errors"></div>
							</div>
						</div>		
						<div class="col-lg-3">
							<div class="form-group">
								<label for="usr">
									{{trans('gatewayNfe::gateway_nfe.system_nfe_emmit_date') }}
									<a href="#" class="question-field" data-toggle="tooltip" title="{{trans('gatewayNfe::gateway_nfe.system_nfe_emmit_date_title') }}"><span class="mdi mdi-comment-question-outline"></span></a> <span class="required-field">*</span>
								</label>
								<input min="1" max="31" type="number" class="form-control" name="nfe_gateway_issuer_emission_day" required data-error="{{trans('setting.field')}}" value="{{$model->nfe_gateway_issuer_emission_day->value}}">
								<div class="help-block with-errors"></div>
							</div>
						</div>											
					</div>					
				</div>
			</div>	
				
		<div class="form-group text-right">
			<button type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{trans('keywords.save')}}
			</button>
		</div>
			</form>
		</div>
	</div>
</div>
@stop

@section('styles')
<style>
.loader {
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
@stop

@section('javascripts')
<script src="/libs/gateway_nfe/lang.trans/gateway_nfe"> </script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/md5.js"></script>
<script type="text/javascript">
	function generateHash() {		
		const aleatoryString = Math.random().toString(36).substring(7); 	
		const hash = CryptoJS.MD5(aleatoryString);
   		document.getElementById('gatewayWeebHookKey').value = hash
	}

	async function validateKey(key, firstCall = false){
		const isLoading = document.getElementById('isLoading')
		isLoading.style.display = 'block'

		const enableGateway = document.getElementById('enotasSettingPainel')		
		const url = '/admin/libs/settings/nfe_gateway/enable'		
		const header = {
			method: 'POST',			
			body: JSON.stringify({apiKey: key}),
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json',
			}   
    	}		
	
		const response = await fetch(url, header)
		const {success} = await response.json()
	
		if(success){
			enableGateway.style.display = 'block'
		}else {
			if(!firstCall)alert("Chave de API invalida")
			enableGateway.style.display = 'none'		
		}
		isLoading.style.display = 'none'		
	}
	const gatewayKey = document.getElementById('gatewayApiKey').value
	validateKey(gatewayKey, true).then();
	
   
</script>
@stop