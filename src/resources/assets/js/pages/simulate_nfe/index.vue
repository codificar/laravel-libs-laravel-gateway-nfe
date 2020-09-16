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
          <h4 class="m-b-0">Emitente</h4>
          <div class="row">
            <div class="col-md-2 form-group">
              <label class="control-label">Data Inicial</label>
              <input type="date" class="form-control" />
            </div>

            <div class="col-md-2 form-group">
              <label class="control-label">Data Final</label>
              <input type="date" class="form-control" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
