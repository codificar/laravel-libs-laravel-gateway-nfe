<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGateweyNfes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateway_nfes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_id')->nullable();    
            $table->string('company_id')->nullable();   

            $table->string('nfe_id')->nullable();      
            $table->string('nfe_external_id')->nullable();      
            $table->string('nfe_status')->nullable();    
            
            $table->string('nfe_status_reason')->nullable();                
            $table->string('nfe_pdf')->nullable();      
            $table->string('nfe_xml')->nullable();      
            $table->string('nfe_number')->nullable();  
            
            $table->string('nfe_verification_code')->nullable();      
            $table->string('nfe_rps_number')->nullable();      
            $table->string('nfe_rps_serie')->nullable();  
            $table->date('nfe_competence_date')->nullable(); 

            $table->decimal('value', 8, 2)->nullable();

            $table->enum('issuer_type', ['issuer', 'provider'])->nullable();
            $table->enum('client_type', ['user', 'institution'])->nullable();
             
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
        Schema::drop('gateway_nfes');
    }
}
