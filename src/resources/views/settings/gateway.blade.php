@extends('layout.master')
@section('breadcrumbs')
<div class="row page-titles">
	<div class="col-md-6 col-8 align-self-center">
		<h3 class="text-themecolor m-b-0 m-t-0">{{ trans("setting.conf") }}</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="javascript:void(0)">{{ trans("dashboard.home") }}</a></li>
			<li class="breadcrumb-item active">{{ trans("setting.conf") }}</li>
			<li class="breadcrumb-item active">NF</li>
		</ol>
	</div>
</div>	
@stop

@section('content')
<div class="col-lg-12">
	<div class="card card-outline-info">
		<div class="card-header">
			<h4 class="m-b-0 text-white">Configurações Nota Fiscal</h4>
			</div>
			<div class="card-block">
		<form enctype="multipart/form-data" method="post" data-toggle="validator" action="{{ URL::Route('saveNfeSettings') }}">		
			<div class="row">
				<div class="col-md-5">
					<!--General Settings-->
					<div class="panel panel-primary" id="panel-zenvia">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label for="gateway">Gateway</label>
										<select class="select form-control select-gateway" name="nfe_gateway">
											<option value="enotas">
												eNotas
											</option>											
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="gateway">Habilitado</label>
										<select class="select form-control" name="nfe_gateway_enable">
											<option value="{{0}}" {{ $model->nfe_gateway_enable->value == "0" ? "selected" : "" }} >
												Não
											</option>		
											<option value="{{1}}" {{ $model->nfe_gateway_enable->value == "1" ? "selected" : ""}} >
												Sim
											</option>																					
										</select>
									</div>
								</div>
							</div>
						</div>
				</div>
				</div>
			</div>
			<!-- eNotas -->
			@if($model->nfe_gateway_enable->value)
			<div class="panel panel-default panel-gateway" id="enotas">
				<div class="panel-heading">
					<h3 class="panel-title">Configurações do eNotas</h3>
					<hr>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label for="usr">
									Chave da API
									<a href="#" class="question-field" data-toggle="tooltip" title="Chave de API disponível no painel do eNotas"><span class="mdi mdi-comment-question-outline"></span></a> <span class="required-field">*</span>
								</label>
								<input type="text" class="form-control" name="nfe_gateway_api_key" required data-error="{{trans('setting.field')}}" value="{{$model->nfe_gateway_api_key->value}}">
								<div class="help-block with-errors"></div>
							</div>
						</div>	
						
						<div class="col-lg-3">
							<div class="form-group">
								<label for="usr">
									Email para cópia das notas
									<a href="#" class="question-field" data-toggle="tooltip" title="Email para cópia das notas"><span class="mdi mdi-comment-question-outline"></span></a> <span class="required-field">*</span>
								</label>
								<input type="email" class="form-control" name="nfe_gateway_copy_email" required data-error="{{trans('setting.field')}}" value="{{$model->nfe_gateway_copy_email->value}}">
								<div class="help-block with-errors"></div>
							</div>
						</div>	
						<div class="col-lg-3">
							<div class="form-group">
								<label for="gateway">Ambiente do eNotas</label>
									<select class="select form-control" name="nfe_gateway_env">
										<option value="Producao" {{ $model->nfe_gateway_env->value == "Producao" ? "selected" : ""}} >
											Produção
										</option>	
										<option value="Homologacao" {{ $model->nfe_gateway_env->value == "Homologacao" ? "selected" : "" }} >
											Homologação
										</option>											
									</select>
							</div>
						</div>						
					</div>		

					<div class="row">
					<div class="col-lg-3">
							<div class="form-group">
								<label for="usr">
									Descrição do serviço na nota
									<a href="#" class="question-field" data-toggle="tooltip" title="Texto que vai ser usado como descrição do serviço na nota"><span class="mdi mdi-comment-question-outline"></span></a> <span class="required-field">*</span>
								</label>
								<input min="1" max="255" type="text" class="form-control" name="nfe_gateway_service_description" required data-error="{{trans('setting.field')}}" value="{{$model->nfe_gateway_service_description->value}}">
								<div class="help-block with-errors"></div>
							</div>
						</div>	
						<div class="col-lg-3">
							<div class="form-group">
								<label for="usr">
									Dia do mês da emissão de NFE dos Motoboys
									<a href="#" class="question-field" data-toggle="tooltip" title="Dia no mês de emissão das notas dos Motoboy"><span class="mdi mdi-comment-question-outline"></span></a> <span class="required-field">*</span>
								</label>
								<input min="1" max="31" type="number" class="form-control" name="nfe_gateway_provider_emission_day" required data-error="{{trans('setting.field')}}" value="{{$model->nfe_gateway_provider_emission_day->value}}">
								<div class="help-block with-errors"></div>
							</div>
						</div>		
						<div class="col-lg-3">
							<div class="form-group">
								<label for="usr">
									Dia do mês da emissão de NFE do Sistema
									<a href="#" class="question-field" data-toggle="tooltip" title="Dia no mês de emissão das notas do Sistema"><span class="mdi mdi-comment-question-outline"></span></a> <span class="required-field">*</span>
								</label>
								<input min="1" max="31" type="number" class="form-control" name="nfe_gateway_issuer_emission_day" required data-error="{{trans('setting.field')}}" value="{{$model->nfe_gateway_issuer_emission_day->value}}">
								<div class="help-block with-errors"></div>
							</div>
						</div>											
					</div>					
				</div>
			</div>	
			@endif		
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
	.panel-gateway{
		display:none;
	}
</style>
@stop

@section('javascripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('.select-gateway').change(function(){
            var painel = $(this).val();
			updatePainel(painel);
        });
		var updatePainel = function(painel){
			$('.panel-gateway').filter(function() {
                return $(this).css('display') == 'block';
            }).fadeOut(300,function(){
                console.log('#'+painel);
                $('#'+painel).fadeIn('slow');
            });
		};
		var selected_gateway = $('.select-gateway').val();
		$('#'+selected_gateway).fadeIn('slow');
    });
</script>
@stop