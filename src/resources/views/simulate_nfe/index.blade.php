<?php $layout = '.master'; ?>
       
@extends('layout'.$layout)

@section('content')
	<div id="VueJs">
		
	<simulate_nfe
		route-simulate-provider-value = "{{ URL::Route('nfeProviderValue') }}"
		route-simulate-issuer-value   = "{{ URL::Route('nfeIssuerValue') }}"
		route-simulate-provider-job   = "{{ URL::Route('nfeProviderJob') }}"
		route-simulate-issuer-job     = "{{ URL::Route('nfeIssuerJob') }}"
	>
	</simulate_nfe>

	</div>		

	</div>

@stop

@section('javascripts')
<script type="text/javascript">
    var translate = [];
    translate["reviews.loading"] = "{{trans('reviews.loading') }}";
    translate["user_provider_web.rate"] = "{{ trans('user_provider_web.rate') }}";
</script>

<script src="/libs/gateway_nfe/lang.trans/gateway_nfe"></script> 

<script src="{{ asset('vendor/codificar/gateway_nfe/gateway_nfe.vue.js') }}"> </script> 
       
@stop
