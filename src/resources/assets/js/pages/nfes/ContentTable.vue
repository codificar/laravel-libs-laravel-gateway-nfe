<template>
<div>
  <table class="table table-hover">
    <tr>
      <th>ID</th> 
      <!-- <th>Gateway ID da Empresa</th>    -->
      <th>Gateway ID da Nota</th>      
      <th>STATUS</th> 
      <th>MOTIVO DO STATUS</th> 
      
      <th>EMITIDA DE</th> 
      <th>EMITIDA PARA</th>   
      <th>VALOR</th>   
      <th>DATA DE EMISSÃO</th> 
      <th>PDF</th>  
    </tr>
    <tr v-for="nfe in nfeList.data" :key="nfe.id">
      <td>{{ nfe.id }}</td> 
      <!-- <A\  td>{{ nfe.company_id }}</A>  -->
      <td>{{ nfe.nfe_id ? nfe.nfe_id :"Não Atribuído" }}</td>   
      <td>{{ nfe.nfe_status ? nfe.nfe_status :"Não Atribuído" }}</td> 
      <td>{{ nfe.nfe_status_reason ? nfe.nfe_status_reason :"Não Atribuído" }}</td> 
      <td>{{ nfe.issuerType  == "issuer" ? "Emitente" : "Motoboy" }}</td>    
      <td>{{ nfe.clientType == "user" ? "Usuario" : "Instituição" }}</td>    
      <td>{{ nfe.value ? `R$ ${nfe.value}` : "Não Atribuído" }}</td>    
      <td>{{ nfe.created_at }}</td>  
      <td v-if="nfe.nfe_pdf" > 
        <button
            @click="openPdf(nfe.nfe_pdf)"
            class="btn btn-info"
            type="button"
        >
           Visualizar PDF
        </button>
      </td>   
       <td v-else> 
        Não Atribuído
      </td>
    </tr>    
  </table>
  <Pagination
      :data="nfeList"
      @pagination-change-page="changePage"
    ></Pagination>
  </div>
</template>

<script>
import Pagination from "laravel-vue-pagination"
import axios from "axios";
export default {
  components: {
    Pagination
  },
  props: {
    nfeList: {
      type: Object,
      default: {},
    },    
    filter: {
      type: Object,
      default: {
        filter :{
          id: null,
          issuerType: null,
          clientType: null,
          startDate: null,
          endDate: null          
        },
      }
    },
  },
  methods: { 
    changePage(page = 1) {
      this.$emit('filter-data', page, this.filter)      
    },
    openPdf(link) {
      window.open(link)  
    }       
  },
  async created() {
    
  },
};
</script>
