<script>
import axios from "axios";
import {
  required,
  minLength,
  maxLength,
  email,
  sameAs,
} from "vuelidate/lib/validators";

export default {
  props: ["Company"],
  data() {
    return {
      //tipoAutenticacao
      tipoAutenticacao: {Nenhuma: 0, Certificado: 1, UsuarioESenha: 2, Token: 3},
      tipoAssinaturaDigital: {NaoUtiliza: 0, Opcional: 1, Obrigatorio: 2},
      
      company: null,
      has_company: false,

      auth: {
        login: null,
        password: null
      },

      certifie: {
        certified: null,
        company_id: null,
        pass: null,
        nome: null,
        dataVencimento: null,
      },
    };
  },
  validations: {
    certifie: {
      certified: required,
      pass: required,
    },
    auth: {
        login: required,
        password: required
    },
  },
  components: {},
  methods: {
    uploadFile(event) {
      const files = event.target.files || event.dataTransfer.files;
      if (files) {
        this.certifie.certified = files[0];
      } else {
        this.certifie.certified = null;
      }
    },
    async companyCertifie() {
      try {
        let formData = new FormData();
        formData.append('certified', this.certifie.certified);
        formData.append('pass', this.certifie.pass);
        const {data} = await axios.post("/admin/issuer/company/certified", formData);       
        if (data.sucess) {
          this.$swal({
            title: data.data,
            type: "success",
          });
        } else {
          this.$swal({
            title: "Valores invalidos",
            html: data.data,
            type: "warning",
          });
        }        
      } catch (error) {
        this.$swal({
          title: "Erro ao atualizar certificado",
          type: "error",
        });
        console.log("companyCertifie", error);
        return false;
      }
    },

   async loginCompany() {
      try {       
        const {data} = await axios.post("/admin/issuer/company/login", this.auth); 
       
        if (data.sucess) {
          this.$swal({
            title: "Empresa autenticada com sucesso",
            type: "success",
          });
          window.location.href = '/admin/issuer/company/create'
        } else {
          this.$swal({
            title: "Valores invalidos",
            html: data.data,
            type: "warning",
          });
        }        
      } catch (error) {
        this.$swal({
          title: "Erro ao atualizar certificado",
          type: "error",
        });
        console.log("companyCertifie", error);
        return false;
      }
    }
  },
  async mounted() {  
     if(JSON.parse(this.Company)){      
      this.company = JSON.parse(this.Company)
      this.has_company = true
      if(this.company.isDocAuth){
        this.certifie.nome = this.company.digitalCertificateName
        this.certifie.dataVencimento = this.company.digitalExpirationDate
      }     
    }    
  },
};
</script>
<template>
  <div v-if="has_company">
    <!-- Login e Senha -->
      <div class="col-lg-12">
      <div class="card card-outline-info">
        <div class="card-header">
           <h4 v-if="this.company.isLoginAuth" class="m-b-0 text-white">{{trans('gateway_nfe.valid_login')}}</h4>
          <h4 v-else class="m-b-0 text-white">{{trans('gateway_nfe.pendent_login')}}</h4>
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-warning">
                <div class="box-header">
                  <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                  <!--/ START CONTENT -->
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="control-label mg-20">
                        {{trans('gateway_nfe.acess_data')}}
                      </h3>
                    </div>
                    <div class="col-md-2 form-group">
                      <label class="control-label">{{trans('gateway_nfe.login')}}*</label>
                      <input
                        type="text"
                        id="login"
                        class="form-control"
                      />
                      <div class="error" v-if="!$v.auth.login.required">
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>

                    <div class="col-md-2 form-group">
                      <label class="control-label">{{trans('gateway_nfe.password')}}*</label>
                      <input type="password" id="auth_pass" v-model="certifie.pass" class="form-control" />
                      <div class="error" v-if="!$v.auth.password.required">
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>
                  </div>                 
                  <!--/ END CONTENT -->
                </div>
              </div>
            </div>
          </div>
          <div v-if="!this.company.isLoginAuth" class="box-footer">
            <div class="pull-right">
              <button
                v-on:click="loginCompany()"
                type="button"
                class="btn btn-success"
              >
                Vincular
              </button>
            </div>
          </div>
        </div>       
      </div>
    </div>



    <!-- Certificado -->
    <div class="col-lg-12">
      <div class="card card-outline-info">
        <div class="card-header">
          
          <h4 v-if="this.company.isDocAuth" class="m-b-0 text-white">{{trans('gateway_nfe.auth_doc')}}</h4>
          <h4 v-else class="m-b-0 text-white">{{trans('gateway_nfe.pendent_login')}}</h4>
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-warning">
                <div class="box-header">
                  <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                  <!--/ START CONTENT -->
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="control-label mg-20">
                       {{trans('gateway_nfe.digital_doc')}} - {{this.company.fantasyName}}
                      </h3>
                    </div>
                    <div class="col-md-2 form-group">
                      <label class="control-label">{{trans('gateway_nfe.doc_pfx')}}*</label>
                      <input
                        type="file"
                        id="file"
                        class="form-control"
                        accept=".pfx, .p12"
                        @change="uploadFile"
                      />
                      <div class="error" v-if="!$v.certifie.certified.required">
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>

                    <div class="col-md-2 form-group">
                      <label class="control-label">{{trans('gateway_nfe.password')}}</label>
                      <input type="password" id="pass" v-model="certifie.pass" class="form-control" />
                      <div class="error" v-if="!$v.certifie.pass.required">
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3 form-group">
                      <label class="control-label">{{trans('gateway_nfe.name')}}</label>
                      <input
                        v-model="certifie.nome"
                        disabled
                        type="text"
                        id="name"
                        class="form-control"
                      />                     
                    </div>
                    <div class="col-md-2 form-group">
                      <label class="control-label">{{trans('gateway_nfe.valid_date')}}</label>
                      <input
                        v-model="certifie.dataVencimento"
                        disabled
                        type="text"
                        class="form-control"                       
                      />                      
                    </div>
                  </div>
                  <!--/ END CONTENT -->
                </div>
              </div>
            </div>
          </div>
          <div v-if="!this.company.isDocAuth" class="box-footer">
            <div class="pull-right">
              <button
                v-on:click="companyCertifie()"
                type="button"
                class="btn btn-success"
              >
                Vincular
              </button>
            </div>
          </div>
        </div>       
      </div>
    </div>
  </div>
</template>
