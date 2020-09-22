<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class insertSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Settings::updateOrCreate(
            ['category' => 10, 'key' => 'nfe_gateway_api_key'],
            [
                'key' => 'nfe_gateway_api_key',
                'value' => "",
                'tool_tip' => "Nfe Gateway Api Key",
                'page' => 1,
                'category' => '10',
                'sub_category' => '0'
            ]
        );

        Settings::updateOrCreate(
            ['category' => 10, 'key' => 'nfe_gateway_env'],
            [
                'key' => 'nfe_gateway_env',
                'value' => "",
                'tool_tip' => "Nfe Gateway ENV",
                'page' => 1,
                'category' => '10',
                'sub_category' => '0'
            ]
        );

        Settings::updateOrCreate(
            ['category' => 10, 'key' => 'nfe_gateway_env'],
            [
                'key' => 'nfe_gateway_env',
                'value' => "",
                'tool_tip' => "Nfe Gateway ENV",
                'page' => 1,
                'category' => '10',
                'sub_category' => '0'
            ]
        );

        Settings::updateOrCreate(
            ['category' => 10, 'key' => 'nfe_gateway_service_description'],
            [
                'key' => 'nfe_gateway_service_description',
                'value' => "",
                'tool_tip' => "Nfe Gateway Service Description",
                'page' => 1,
                'category' => '10',
                'sub_category' => '0'
            ]
        );

        Settings::updateOrCreate(
            ['category' => 10, 'key' => 'nfe_gateway_provider_emission_day'],
            [
                'key' => 'nfe_gateway_provider_emission_day',
                'value' => "",
                'tool_tip' => "Nfe Gateway ProviderEmmit Date",
                'page' => 1,
                'category' => '10',
                'sub_category' => '0'
            ]
        );

        Settings::updateOrCreate(
            ['category' => 10, 'key' => 'nfe_gateway_issuer_emission_day'],
            [
                'key' => 'nfe_gateway_issuer_emission_day',
                'value' => "",
                'tool_tip' => "Nfe Gateway Issuer Emmit Date",
                'page' => 1,
                'category' => '10',
                'sub_category' => '0'
            ]
        );

        Settings::updateOrCreate(
            ['category' => 10, 'key' => 'nfe_gateway_copy_email'],
            [
                'key' => 'nfe_gateway_copy_email',
                'value' => "",
                'tool_tip' => "Nfe Gateway Copy email",
                'page' => 1,
                'category' => '10',
                'sub_category' => '0'
            ]
        );

        Settings::updateOrCreate(
            ['category' => 10, 'key' => 'nfe_gateway_enable'],
            [
                'key' => 'nfe_gateway_enable',
                'value' => "0",
                'tool_tip' => "Enable Emmit Nfe",
                'page' => 1,
                'category' => '10',
                'sub_category' => '0'
            ]
        );

        Settings::updateOrCreate(
            ['category' => 10, 'key' => 'nfe_gateway_weebhook_key'],
            [
                'key' => 'nfe_gateway_weebhook_key',
                'value' => "false",
                'tool_tip' => "Enable Emmit Nfe",
                'page' => 1,
                'category' => '10',
                'sub_category' => '0'
            ]
        );
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
