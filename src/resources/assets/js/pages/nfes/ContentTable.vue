<template>
<div>
  <table class="table table-hover">
    <tr>
      <th>ID</th> 
      <!-- <th>Gateway ID da Empresa</th>    -->
      <th>Gateway ID NFE</th>      
      <th>STATUS</th> 
      <th>{{trans('gateway_nfe.status_reason')}}</th> 
      
      <th>{{trans('gateway_nfe.emmit_by_title')}}</th> 
      <th>{{trans('gateway_nfe.emmit_to_title')}}</th>   
      <th>{{trans('gateway_nfe.value')}}</th>   
      <th>{{trans('gateway_nfe.emission_date')}}</th> 
      <th>PDF</th>  
    </tr>
    <tr v-for="nfe in nfeList.data" :key="nfe.id">
      <td>{{ nfe.id }}</td> 
      <!-- <A\  td>{{ nfe.company_id }}</A>  -->
      <td>{{ nfe.nfe_id ? nfe.nfe_id : trans('gateway_nfe.unassigned') }}</td>   
      <td>{{ nfe.nfe_status ? nfe.nfe_status : trans('gateway_nfe.unassigned') }}</td> 
      <td>{{ nfe.nfe_status_reason ? nfe.nfe_status_reason : trans('gateway_nfe.unassigned')}}</td> 
      <td>{{ nfe.issuerType  == "issuer" ? trans('gateway_nfe.issuer') : "Motoboy" }}</td>    
      <td>{{ nfe.clientType == "user" ? trans('gateway_nfe.user') : trans('gateway_nfe.institution') }}</td>    
      <td>{{ nfe.value ? `R$ ${nfe.value}` : trans('gateway_nfe.unassigned') }}</td>    
      <td>{{ nfe.created_at }}</td>  
      <td v-if="nfe.nfe_pdf" > 
        <button
            @click="openPdf(nfe.nfe_pdf)"
            class="btn btn-info"
            type="button"
        >
          {{trans('gateway_nfe.view_pdf')}}
        </button>
      </td>   
       <td v-else> 
        {{trans('gateway_nfe.unassigned')}}
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
