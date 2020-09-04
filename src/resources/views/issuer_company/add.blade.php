@extends('layout.master')

@section('breadcrumbs')
<div class="row page-titles">
	<div class="col-md-6 col-8 align-self-center">
		<h3 class="text-themecolor m-b-0 m-t-0">{{ trans("invoice.data") }}</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="javascript:void(0)">{{ trans("dashboard.home") }}</a></li>
			<li class="breadcrumb-item active">{{ trans("keywords.Settings") }}</li>
			<li class="breadcrumb-item active">{{ trans("invoice.data") }}</li>
		</ol>
	</div>
</div>
@stop

@section('content')
<div id="VueJs">

	<add_issuer_company
		Edit = "{{false}}"
		Enviroment="admin"
		Create-route = "{{ URL::Route('issuerCompanyStore') }}"
		Update-route = "{{ URL::Route('issuerCompanyUpdate') }}"
		Company = "{{ $company }}"		
	>
	</add_issuer_company>
	
	<issuer_company_certifie	
		Company = "{{ $company }}"			
		Login-auth-route = "{{ URL::Route('issuerLoginAuth') }}"
		Certifie-auth-route = "{{ URL::Route('issuerSetCompanyCertifie') }}"
	>
	</issuer_company_certifie>
	
</div>
@stop

@section('javascripts')
<script type="text/javascript">
    var translate = [];
    translate["reviews.loading"] = "{{trans('reviews.loading') }}";
    translate["user_provider_web.rate"] = "{{ trans('user_provider_web.rate') }}";
</script>

<script src="/libs/gateway_nfe/lang.trans/gateway_nfe"></script> 

<script src="{{ elixir('vendor/codificar/gateway_nfe/gateway_nfe.vue.js') }}"> </script> 
       
@stop

