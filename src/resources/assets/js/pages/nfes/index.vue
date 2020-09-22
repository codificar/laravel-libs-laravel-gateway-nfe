<script>
import axios from "axios";

export default {
  props: ["indexRoute"],
  data() {
    return {
      nfeList: [],
    };
  },
  methods: {
      async getNFEs(){
          const response = await axios.post(this.indexRoute)
          console.log("RESPONSE", response);
      }
  },
  async mounted() {
    await this.getNFEs()
  },
};
</script>
<template>
  <div>
    <!-- Login e Senha -->
    <div class="col-lg-12">
      <div class="card card-outline-info">
        <div class="card-header">
          <h4 class="m-b-0 text-white">NFEs</h4>
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-md-12">DATA</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
