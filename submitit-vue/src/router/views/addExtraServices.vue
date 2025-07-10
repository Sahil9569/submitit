<template>
  <Layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader title="Add Extra Services" />
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class=" mb-3">
          <div class="row gx-2">
            <!-- <div class="col"><h3 class="my-1 font-size-18 text-dark-cstm">The Wreckage of Attraction</h3></div> -->
            <div class="col text-end">
              <div class="topBarCstmTable searchBarStyle position-relative">
                <input type="text" v-model="searchValueTable" class="form-control" placeholder="Search...">
                <span class="iconSearch bx bx-search-alt"></span>
              </div>
            </div>
          </div>
        </div>
        <EasyDataTable :headers="headers" :items="tableItems" :search-value="searchValueTable" border-cell
          theme-color="#3aafa9" :rows-per-page="25" buttons-pagination table-class-name="table-custom-style" show-index>
          <!-- <template #item-amount="{ amount }">
            <span class="ms-auto d-flex">${{ amount }}</span>
          </template> -->
          <template #item-story_id="{ story_id }">
            <span class="ms-auto d-flex">{{ story_id.storyTitle }}</span>
          </template>
          <template #item-user_id="{ user_id }">
            <span class="ms-auto d-flex">{{ user_id.firstName }} {{user_id.lastName}}</span>
          </template>
          <template #item-action="{ token, id }">
            <div class="ms-auto d-flex ">
              <button @click="copyUrl(token, id)" class="btn btn-sm btn-soft-info" title="Copy URL">
                <i class="mdi mdi-checkbox-multiple-blank-outline me-1"><sup v-if="show[id]">Copied To Clipboard</sup></i>
              </button>
            </div>
          </template>
        </EasyDataTable>
      </div>
    </div>
  </Layout>
</template>

<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import axios from 'axios';
// import CryptoJS from 'crypto-js';
import useVuelidate from "@vuelidate/core";
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import { required, helpers } from "@vuelidate/validators";
export default {
  page: {
    title: "Add Extra Services",
    meta: [
      {
        name: "description",
        content: "description",
      },
    ],
  },
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      title: "Add Extra Services",
      url: process.env.VUE_APP_URL,
      submitted: false,
      form: {
        service: "",
        amount: "",
      },
      show: [],
      tableItems: [],
      headers: [
        { text: "Service", value: "title" },
        { text: "Amount", value: "amount" },
        {text: "Story", value: "story_id"},
        {text: "Client", value: "user_id"},
        { text: "Status", value: "status" },
      ],
    };
  },
  validations: {
    form: {
      service: {
        required: helpers.withMessage("Service is required", required),
      },
      amount: {
        required: helpers.withMessage("Amount is required", required),
      },
    },
  },
  components: {
    PageHeader,
    Layout,
    EasyDataTable,
  },

  activated() {
    this.getServices();
    this.form.service = null
    this.form.amount = null
  },

  methods: {
    addExtraServices() {
      this.submitted = true;
      this.v$.$touch();
      if (this.v$.$invalid) {
        return false;
      } else {
        axios.post(this.url+'api/addExtraService', {title:this.form.service, amount:this.form.amount}).then(()=>{
          this.getServices()
        })
      }
    },
    getServices() {
      this.form.service = "";
      this.form.amount = "";
      axios.get(this.url + 'api/getServices').then(res => {
        this.tableItems = res.data;
      });
    },
    copyUrl(token, id)
    {
      const tempInput = document.createElement('input');
      document.body.appendChild(tempInput);
      tempInput.value = this.url+'payment/'+token;
      tempInput.select();
      try {
        document.execCommand('copy');
      } catch (err) {
          console.error('Unable to copy to clipboard', err);
      }
      document.body.removeChild(tempInput);
      this.show[id] = true
      setTimeout(()=>{
        this.show[id] = false
      }, 1000)
    }
  },
};
</script>
