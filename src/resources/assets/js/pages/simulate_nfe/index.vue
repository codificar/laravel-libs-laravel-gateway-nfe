<script>
import axios from "axios";

export default {
  props: [
    "routeSimulateProviderValue",
    "routeSimulateIssuerValue",
    "routeSimulateProviderJob",
    "routeSimulateIssuerJob",
  ],
  data() {
    return {
      providerValue: [],
      providerValueStart: "",
      providerValueEnd: "",

      issuerValue: [],
      issuerValueStart: "",
      issuerValueEnd: "",
    };
  },

  components: {},
  methods: {
    async getProviderValue() {
      try {
        const response = await axios.post(this.routeSimulateProviderValue, {
          start_date: this.providerValueStart.replace(/-/g, "/"),
          end_date: this.providerValueEnd.replace(/-/g, "/"),
        });
        
        this.providerValue = response.data;
      } catch (error) {
        console.log("getProviderValue error", error);
      }
    },

    async providerEmmitNfe() {
      try {
        const response = await axios.post(this.routeSimulateProviderJob, {
          start_date: this.providerValueStart.replace(/-/g, "/"),
          end_date: this.providerValueEnd.replace(/-/g, "/"),
        });
        alert("Notas Emitidas")
      } catch (error) {
        console.log("providerEmmitNfe error", error);
      }
    },

    async getIssuerValue() {
      try {
        const response = await axios.post(this.routeSimulateIssuerValue, {
          start_date: this.issuerValueStart.replace(/-/g, "/"),
          end_date: this.issuerValueEnd.replace(/-/g, "/"),
        });
      
        this.issuerValue = response.data;
      } catch (error) {
        console.log("getIssuerValue error", error);
      }
    },

    async issuerEmmitNfe() {
      try {
        const response = await axios.post(this.routeSimulateIssuerJob, {
          start_date: this.issuerValueStart.replace(/-/g, "/"),
          end_date: this.issuerValueEnd.replace(/-/g, "/"),
        });
        alert("Notas Emitidas")
      } catch (error) {
        console.log("issuerEmmitNfe error", error);
      }
    },
  },

  async mounted() {
    console.log("MOUNTED SIMULATE");
  },
};
</script>
<template>
  <div>
    <!-- Login e Senha -->
    <div class="col-lg-12">
      <div class="card card-outline-info">
        <div class="card-header">
          <h4 class="m-b-0 text-white">Simulador</h4>
        </div>
        <div class="card-block">
          <h4 class="m-b-0">Valores do Provider</h4>
          <div class="row">
            <div class="col-md-2 form-group">
              <label class="control-label">Data Inicial</label>
              <input
                v-model="providerValueStart"
                type="date"
                class="form-control"
              />
            </div>

            <div class="col-md-2 form-group">
              <label class="control-label">Data Final</label>
              <input
                v-model="providerValueEnd"
                type="date"
                class="form-control"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-md-1">
              <button
                v-on:click="getProviderValue()"
                type="button"
                class="btn btn-success"
              >
                Pesquisar
              </button>
            </div>
            <div class="col-md">
              <p>{{ JSON.stringify(this.providerValue) }}</p>
            </div>
          </div>
        </div>

        <div class="card-block">
          <h4 class="m-b-0">Emitir notas do provider</h4>
          <div class="row">
            <div class="col-md-2 form-group">
              <label class="control-label">Data Inicial</label>
              <input
                v-model="providerValueStart"
                type="date"
                class="form-control"
              />
            </div>

            <div class="col-md-2 form-group">
              <label class="control-label">Data Final</label>
              <input
                v-model="providerValueEnd"
                type="date"
                class="form-control"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <button
                v-on:click="providerEmmitNfe()"
                type="button"
                class="btn btn-success"
              >
                Emitir
              </button>
            </div>
            <div class="col-md">
              <!-- <p>{{ JSON.stringify(this.providerValue) }}</p> -->
            </div>
          </div>
        </div>

          <div class="card-block">
          <h4 class="m-b-0">Valores do Emitente</h4>
          <div class="row">
            <div class="col-md-2 form-group">
              <label class="control-label">Data Inicial</label>
              <input
                v-model="issuerValueStart"
                type="date"
                class="form-control"
              />
            </div>

            <div class="col-md-2 form-group">
              <label class="control-label">Data Final</label>
              <input
                v-model="issuerValueEnd"
                type="date"
                class="form-control"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-md-1">
              <button
                v-on:click="getIssuerValue()"
                type="button"
                class="btn btn-success"
              >
                Pesquisar
              </button>
            </div>
            <div class="col-md">
              <p>{{ JSON.stringify(this.issuerValue) }}</p>
            </div>
          </div>
        </div>

         <div class="card-block">
          <h4 class="m-b-0">Emitir notas do emitente</h4>
          <div class="row">
            <div class="col-md-2 form-group">
              <label class="control-label">Data Inicial</label>
              <input
                v-model="issuerValueStart"
                type="date"
                class="form-control"
              />
            </div>

            <div class="col-md-2 form-group">
              <label class="control-label">Data Final</label>
              <input
                v-model="issuerValueEnd"
                type="date"
                class="form-control"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <button
                v-on:click="issuerEmmitNfe()"
                type="button"
                class="btn btn-success"
              >
                Emitir
              </button>
            </div>
            <div class="col-md">
              <!-- <p>{{ JSON.stringify(this.providerValue) }}</p> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
