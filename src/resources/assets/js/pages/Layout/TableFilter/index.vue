<template>
  <div>
    <div class="col-lg-12">
      <div class="card card-outline-info">
        <!--/ CARD -->
        <div class="card-header">
            <slot name="card-title">
                   
            </slot>          
        </div>
        <!--/ Filter -->
        <div class="card-block">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-warning">
                <div class="box-header">                 
                    <slot name="filter-title">
                       
                    </slot>                  
                </div>
                <slot name="filter">
                   
                </slot>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ CONTENT -->
    <div class="col-lg-12">
      <div class="card tbl-box">
        <div class="card-block">
          <h3 class="card-title">
            <slot name="content-title">
                     
            </slot>
          </h3>
        </div>
        <div class="card-block">
            <slot name="content">
                       
            </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/**
    * @author Gustavo Silva  <gustavo.silva@codifica.com.br> 08/07/2020
    *
    * Rende onde table with filter content
    *
*/
export default {
  name: 'TableFilter',    
}
</script>

<style>

</style>
