<template>
  <div>
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">        
          <label for="giveName"> ID :</label>
          <input
            v-model="filter.id"
            type="text"
            class="form-control"
            placeholder="ID da NFE"
            maxlength="10"
          />
        </div>
      </div>  

      <div class="col-md-4">
        <div class="form-group">        
            <label for="giveName"> {{trans('gateway_nfe.emmit_by')}} :</label>
            <select v-model="filter.issuerType" name="issuerType" class="select form-control">
                <option :value="null">{{trans('gateway_nfe.select')}}</option>
                <option value="issuer">{{trans('gateway_nfe.issuer')}}</option>
                <option value="provider">Motoboy</option>                
            </select>
        </div>
      </div> 

      <div class="col-md-4">
        <div class="form-group">        
          <label for="giveName">{{trans('gateway_nfe.emmit_to')}} :</label>
           <select v-model="filter.clientType" name="clientType" class="select form-control">
                <option :value="null">{{trans('gateway_nfe.select')}}</option>
                <option value="user">{{trans('gateway_nfe.user')}}</option>
                <option value="institution">{{trans('gateway_nfe.institution')}}</option>                
            </select>
        </div>
      </div> 

      <div class="col-md-2">
        <div class="form-group">        
          <label for="giveName">{{trans('gateway_nfe.start_date')}}</label>
          <input
            v-model="filter.startDate"
            type="date"
            class="form-control"
            :placeholder="trans('gateway_nfe.start_date')"
          />    
          <br/>  
          <span>{{trans('gateway_nfe.btween')}}</span>
          <br/>
          <label for="giveName">{{trans('gateway_nfe.end_date')}}</label>
           <input
            v-model="filter.endDate"
            type="date"
            class="form-control"
            :placeholder="trans('gateway_nfe.end_date')"
          />
        </div>
      </div>   
     
    </div>

    <div class="box-footer pull-left">
      <button
        @click="cleanFilters"
        class="btn btn-danger right"
        type="button"
        value="clean"
      >
        <i class="fa fa-trash"></i>{{trans('gateway_nfe.clean_filters')}}
      </button>
    </div>

    <div class="box-footer pull-right">
      <button
        @click="filterData"
        class="btn btn-success right"
        type="button"
        value="Filter_Data"
      >
        <i class="fa fa-search"></i>{{trans('gateway_nfe.search')}}
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default { 
  props: {
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
    page: {
      type: Number,
      default: 1
    }
  },  
  methods: {
    async cleanFilters() {     
      this.$emit('clean-filter')
    },    
    filterData() {
      this.$emit('filter-data', this.page, this.filter)
    }   
  },
  async created() {},
};
</script>
