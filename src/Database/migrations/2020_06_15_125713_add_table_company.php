<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablecompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gateway_company_id'); 


            $table->string('municipal_registration')->nullable();  
            $table->string('document')->nullable();
            $table->string('fantasy_name')->nullable(); 
            $table->string('social_reason')->nullable();

            $table->string('ibge_code')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();         
            $table->string('place')->nullable();
            $table->string('number')->nullable(); 
            $table->string('estate')->nullable();
            $table->string('complement')->nullable(); 

            
            $table->boolean('cultural_promoter')->nullable()->default(false);
            $table->boolean('national_simple_optant')->nullable()->default(true);

            $table->string('estadual_registration')->nullable();             
            $table->string('commercial_phone')->nullable();  
            $table->string('commercial_email')->nullable(); 
            
            $table->boolean('is_auth')->default(false);
            $table->boolean('is_doc_auth')->default(false);
            $table->string('digital_certificate_name')->default(false);
            $table->string('digital_expiration_date')->default(false);

            $table->boolean('is_login_auth')->default(false);
            $table->integer('auth_type')->nullable();

            $table->integer('provider_id')->unsigned()->nullable();  
            $table->foreign('provider_id')->references('id')->on('provider');     

            $table->enum('owner', ['issuer', 'provider'])->default('provider');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('companies');
    }
}
