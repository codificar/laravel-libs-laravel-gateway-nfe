<script>
import axios from "axios";
import TableFilter from "../Layout/TableFilter";
import ContentTable from "./ContentTable";
import ContentFilter from "./ContentFilter";

export default {
  props: ["indexRoute"],
  components: {
    TableFilter,
    ContentFilter,
    ContentTable,
  },
  data() {
    return {
      nfeList: {},
      page: 1,
      filter: {
        id: null,
        issuerType: null,
        clientType: null,
        startDate: null,
        endDate: null
      },
    };
  },
  methods: {
    async filterData(page = 1, filter) {     
      const { data } = await axios.post(this.indexRoute, {
        id: filter.id,
        issuerType: filter.issuerType,
        clientType: filter.clientType,
        startDate: filter.startDate,
        endDate: filter.endDate,
        page: page,
        itemsperpage: 20,
      });
      this.nfeList = data.nfeList;
    },
    async cleanFilters() {
      this.filter = await {
        id: null,
      };
      this.filterData(this.page, this.filter);
    },
  },
  async mounted() {
    await this.filterData(this.page, this.filter);
  },
};
</script>
<template>
  <TableFilter>
    <h4 slot="card-title" class="m-b-0 text-white">Filtros</h4>

    <h3 slot="filter-title" class="box-title">Filtrar NFEs</h3>
    <div slot="filter">
      <ContentFilter
        :filter="filter"
        :page="page"
        @filter-data="filterData"
        @clean-filter="cleanFilters"
      />
    </div>

    <h3 slot="content-title" class="box-title">NFEs</h3>
    <div slot="content">
      <ContentTable
        :filter="filter"
        @filter-data="filterData"
        :nfeList="nfeList"
      />
    </div>
  </TableFilter>
</template>
