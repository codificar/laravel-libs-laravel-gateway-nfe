window.vue = require('vue');
require('lodash');
import Vue from 'vue';
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

//Issuer Company
import AddIssuerCompany from './pages/issuer_company/add_company.vue';
import IssuerCompanyCertifie from './pages/issuer_company/company_certifie.vue';

//Provider Company
import AddProviderCompany from './pages/provider_company/add_company.vue';
import CompanyCertifie from './pages/provider_company/company_certifie.vue';


//Allows localization using trans()
Vue.prototype.trans = (key) => {
    return _.get(window.lang, key, key);
};
//Tells if an JSON parsed object is empty
Vue.prototype.isEmpty = (obj) => {
    return _.isEmpty(obj);
};


//Main vue instance
new Vue({
    el: '#VueJs',

    data: {
    },

    components: {
        add_provider_company: AddProviderCompany,
        provider_company_certifie: CompanyCertifie,        
        add_issuer_company: AddIssuerCompany,        
        issuer_company_certifie: IssuerCompanyCertifie,
    },

    created: function () {
        console.log("MOUNT VueJS");
    }
})