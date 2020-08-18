<script>
import axios from "axios";
import autocomplete from "vuejs-auto-complete";
import {
  required,
  minLength,
  maxLength,
  email,
  sameAs,
} from "vuelidate/lib/validators";

export default {
  props: ["Edit", "Enviroment", "ProviderId", "CreateRoute", "Company"],
  data() {
    return {     
      has_company: false,
      provider_id: null,
      company: {      
        provider_id: null,
        tipoAutenticacao: null,
        tipoAssinaturaDigital: null,
        //Dados empresas
        socialReason: null,
        fantasyName: null,
        document: null, //sem formatação
        municipalRegistration: null,
        estadualRegistration: null,
        commercialEmail: null,
        commercialPhone: null,
        nationalSimpleOptant: true,
        culturalPromoter: false,
        //Endereco
        address: {
          ibgeCode: null,
          estate: null,
          city: null,
          place: null,
          number: null,
          complement: null,
          neighborhood: null,
          zipcode: null,
        },
      },
      addressApi: "https://servicodados.ibge.gov.br/api/v1/localidades/estados",
      companyUf: null,
      ufList: [],
      comanyCity: null,
      cityList: [],
    };
  },
  validations: {
    company: {
      socialReason: { required },
      fantasyName: { required },
      document: { required },
      municipalRegistration: { required },
      commercialEmail: { required },
      commercialPhone: { required },
      address: {
        estate: { required },
        city: { required },
        zipcode: { required },
        place: { required },
        number: { required },
        neighborhood: { required },
      },
    },
  },
  components: {
    autocomplete,
  },
  methods: {
    async getAddressUf() {
      try {
        const result = await axios.get(this.addressApi);
        this.ufList = result.data;
      } catch (error) {
        console.log("getAddressUf Error", error);
      }
    },

    formatUfDisplay(result) {
      return `${result.sigla} - ${result.nome}`;
    },
    async setUfValue(result) {
      if (result) {
        this.company.address.estate = result.selectedObject.sigla;
        await this.getAddresCity(result.value);
      } else {
        this.companyUf = null;
      }
    },

    async getAddresCity(uf) {
      try {
        const result = await axios.get(`${this.addressApi}/${uf}/municipios`);
        this.cityList = result.data;
      } catch (error) {
        console.log("getAddresCity Error", error);
      }
    },

    formatCityDisplay(result) {
      return `${result.nome}`;
    },
    async setCityValue(result) {
      if (result) {
        this.company.address.ibgeCode = result.value;
        this.company.address.city = result.display;
      } else {
        this.company.address.ibgeCode = null;
        this.company.address.city = null;
      }
    },

    async searchZipCode() {
      try {
        const { data } = await axios.get(
          "https://api.postmon.com.br/v1/cep/" + this.company.address.zipcode
        );
        this.company.address.place = data.logradouro;
        this.company.address.neighborhood = data.bairro;
      } catch (error) {
        this.$swal({
          title: "CEP não econtrado",
          type: "error",
        });
        console.log("searchZipCode", error);
        return false;
      }
    },

    async updateCompany() {
        this.company.provider_id = this.provider_id
        console.log("company", this.company);
        const { data } = await axios.post("/admin/provider/company/update", this.company);
        console.log("data", data);
        if (data.sucess) {
          this.$swal({
            title: "Empresa Atualizada com sucesso",
            type: "success",
          });
          window.location.href =
            "/admin/provider/company/create/" + this.provider_id;
        } else {
          this.$swal({
            title: data.error_code,
            html: data.errors,
            type: "warning",
          });
        }
      
    },

    async submitForm() {
        this.company.provider_id = this.provider_id
        const { data } = await axios.post("/admin/provider/company/store", this.company);
      
        if (data.sucess) {
          this.$swal({
            title: "Empresa Registrada com sucesso",
            type: "success",
          });
          window.location.href =
            "/admin/provider/company/create/" + this.provider_id;
        } else {
          this.$swal({
            title: data.error_code,
            html: data.errors,
            type: "warning",
          });
        }
      
    },
    validateCnpj() {},    
  },
  async mounted() {
    console.log("MOUNTED");
    this.provider_id = this.ProviderId;
    await this.getAddressUf();
    
    if (JSON.parse(this.Company)) {  
      this.has_company = true;
      this.company = JSON.parse(this.Company);       
      this.company.nationalSimpleOptant ? this.company.nationalSimpleOptant = true : this.company.nationalSimpleOptant = false
      this.company.culturalPromoter ? this.company.culturalPromoter = true : this.company.culturalPromoter = false
    }
  },
};
</script>
<template>
  <div>
    <div class="col-lg-12">
      <div class="card card-outline-info">
        <div class="card-header">
          <h4 class="m-b-0 text-white">{{ trans('gateway_nfe.company') }}</h4>
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-warning">
                <div class="box-header">
                  <h3 class="box-title">{{ trans('gateway_nfe.company_locale') }}</h3>
                </div>
                <div class="box-body">
                  <!--/ START CONTENT -->
                  <div class="row">                 
                    <div class="col form-group">
                      <label class="control-label">{{ trans('gateway_nfe.estate') }}*</label>
                      <input
                        class="form-control input-lg"
                        v-if="this.has_company"
                        readonly
                        v-model="company.address.estate"
                      />
                      <autocomplete
                        v-else
                        ref="autocomplete"
                        :placeholder="trans('gateway_nfe.estate')"
                        :source="ufList"
                        input-class="form-control"
                        results-value="id"
                        :results-display="formatUfDisplay"
                        @selected="setUfValue"
                        @clear="companyUf = null"
                      >
                      </autocomplete>
                      <div
                        class="error"
                        v-if="!$v.company.address.estate.required"
                      >
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>

                    <div class="col">
                      <label class="control-label">{{trans('gateway_nfe.city')}}*</label>
                      <input
                        class="form-control input-lg"
                        v-if="this.has_company"
                        readonly
                        v-model="company.address.city"
                      />
                      <autocomplete
                        v-else
                        ref="autocomplete"
                        :placeholder="trans('gateway_nfe.city')"
                        :source="cityList"
                        input-class="form-control"
                        results-value="id"
                        :results-display="formatCityDisplay"
                        @selected="setCityValue"
                        @clear="comanyCity = null"
                      >
                      </autocomplete>
                      <div
                        class="error"
                        v-if="!$v.company.address.city.required"
                      >
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>

                    <div class="col form-group">
                      <label class="control-label">{{trans('gateway_nfe.zipcode')}}*</label>
                      <the-mask
                        v-model="company.address.zipcode"
                        :mask="'#####-###'"
                        @blur.native="searchZipCode()"
                        name="company_zipcode"
                        type="text"
                        id="company_zipcode"
                        maxlenght="255"
                        minlenght="3"
                        auto-focus=""
                        class="form-control input-lg"
                        :placeholder="trans('gateway_nfe.zipcode')"
                      />
                      <div
                        class="error"
                        v-if="!$v.company.address.zipcode.required"
                      >
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col form-group">
                      <label class="control-label">{{trans('gateway_nfe.place')}}*</label>
                      <input
                        v-model="company.address.place"
                        name="company_place"
                        type="text"
                        id="company_place"
                        maxlenght="255"
                        minlenght="3"
                        auto-focus=""
                        class="form-control input-lg"
                        :placeholder="trans('gateway_nfe.place')"
                      />
                      <div
                        class="error"
                        v-if="!$v.company.address.place.required"
                      >
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>

                    <div class="col">
                      <label class="control-label">{{trans('gateway_nfe.neighborhood')}}*</label>
                      <input
                        v-model="company.address.neighborhood"
                        name="company_neighborhood"
                        type="text"
                        id="company_neighborhood"
                        maxlenght="255"
                        minlenght="3"
                        auto-focus=""
                        class="form-control input-lg"
                        :placeholder="trans('gateway_nfe.neighborhood')"
                      />
                      <div
                        class="error"
                        v-if="!$v.company.address.neighborhood.required"
                      >
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>

                    <div class="col">
                      <label class="control-label">{{trans('gateway_nfe.number')}}*</label>
                      <input
                        v-model="company.address.number"
                        name="company_number"
                        type="text"
                        id="company_number"
                        maxlenght="255"
                        minlenght="3"
                        auto-focus=""
                        class="form-control input-lg"
                        :placeholder="trans('gateway_nfe.number')"
                      />
                      <div
                        class="error"
                        v-if="!$v.company.address.number.required"
                      >
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>

                    <div class="col">
                      <label class="control-label">{{trans('gateway_nfe.complement')}}</label>
                      <input
                        v-model="company.address.complement"
                        name="company_complement"
                        type="text"
                        id="company_complement"
                        auto-focus=""
                        class="form-control input-lg"
                        :placeholder="trans('gateway_nfe.complement')"
                      />
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="control-label mg-20">
                        {{trans('gateway_nfe.company_identification')}}
                      </h3>
                    </div>

                    <div class="col form-group">
                      <label class="control-label">{{trans('gateway_nfe.social_reason')}}*</label>
                      <input
                        v-model="company.socialReason"
                        name="company_socialReason"
                        type="text"
                        id="company_socialReason"
                        maxlenght="255"
                        minlenght="3"
                        auto-focus=""
                        class="form-control input-lg"
                        :placeholder="trans('gateway_nfe.social_reason')"
                      />
                      <div
                        class="error"
                        v-if="!$v.company.socialReason.required"
                      >
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>

                    <div class="col form-group">
                      <label class="control-label">{{trans('gateway_nfe.fantasy_name')}}*</label>
                      <input
                        v-model="company.fantasyName"
                        name="company_fantasyName"
                        type="text"
                        id="company_fantasyName"
                        maxlenght="255"
                        minlenght="3"
                        auto-focus=""
                        class="form-control input-lg"
                        :placeholder="trans('gateway_nfe.fantasy_name')"
                      />
                      <div
                        class="error"
                        v-if="!$v.company.fantasyName.required"
                      >
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>

                    <div class="col form-group">
                      <label class="control-label">CNPJ*</label>
                      <the-mask
                        v-model="company.document"
                        :mask="'##.###.###/###-#'"
                        @blur.native="validateCnpj()"
                        name="company_document"
                        type="text"
                        id="company_document"
                        maxlenght="255"
                        minlenght="3"
                        auto-focus=""
                        class="form-control input-lg"
                        placeholder="CNPJ"
                      />
                      <div class="error" v-if="!$v.company.document.required">
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>

                    <div class="col form-group">
                      <label class="control-label">{{trans('gateway_nfe.municipal_register')}}*</label>
                      <input
                        v-model="company.municipalRegistration"
                        name="company_municipalRegistration"
                        type="text"
                        id="company_municipalRegistration"
                        maxlenght="255"
                        minlenght="3"
                        auto-focus=""
                        class="form-control input-lg"
                        :placeholder="trans('gateway_nfe.municipal_register')"
                      />
                      <div
                        class="error"
                        v-if="!$v.company.municipalRegistration.required"
                      >
                        {{ trans('gateway_nfe.required_error') }}
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col form-group">
                      <label class="control-label">{{trans('gateway_nfe.estate_register')}}</label>
                      <input
                        v-model="company.estadualRegistration"
                        name="company_estadualRegistration"
                        type="text"
                        id="company_estadualRegistration"
                        maxlenght="255"
                        auto-focus=""
                        class="form-control input-lg"
                        :placeholder="trans('gateway_nfe.estate_register')"
                      />
                    </div>

                    <div class="col form-group">
                      <label class="control-label">Email</label>
                      <input
                        v-model="company.commercialEmail"
                        name="company_commercialEmail"
                        type="text"
                        id="company_commercialEmail"
                        maxlenght="255"
                        auto-focus=""
                        class="form-control input-lg"
                        placeholder="Email"
                      />
                    </div>

                    <div class="col form-group">
                      <label class="control-label">{{trans('gateway_nfe.commercial_phone')}}</label>
                      <the-mask
                        v-model="company.commercialPhone"
                        :mask="'(##) ####-####'"
                        name="company_commercialPhone"
                        type="text"
                        id="company_commercialPhone"
                        maxlenght="255"
                        auto-focus=""
                        class="form-control input-lg"
                        :placeholder="trans('gateway_nfe.commercial_phone')"
                      />
                    </div>
                    

                    <div class="col form-group">
                      <div class="form-inline">
                        <input
                          type="checkbox"
                          class="form-control"
                          id="company_nationalSimpleOptant"
                          name="company_nationalSimpleOptant"
                          v-model="company.nationalSimpleOptant"
                        />
                        <label> &nbsp;{{trans('gateway_nfe.nacional_optant')}}</label>
                      </div>

                      <div class="form-inline">
                        <input
                          type="checkbox"
                          class="form-control"
                          id="company_culturalPromoter"
                          name="company_culturalPromoter"
                          v-model="company.culturalPromoter"
                        />
                        <label> &nbsp;{{trans('gateway_nfe.cultural_promter')}}</label>
                      </div>
                    </div>
                  </div>
                  <!--/ END CONTENT -->
                </div>
              </div>
            </div>
          </div>
          <div class="box-footer">
            <div class="pull-right">
              <button
                v-if="has_company"
                v-on:click="updateCompany()"
                type="button"
                class="btn btn-warning"
              >
                {{trans('gateway_nfe.update_button')}}
              </button>
              <button
                v-else
                v-on:click="submitForm()"
                type="button"
                class="btn btn-success"
              >
                {{trans('gateway_nfe.register_button')}}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
