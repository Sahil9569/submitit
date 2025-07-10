<template>
  <layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader :title="title" />
      </div>
      <div class="col-auto">
        <router-link to="/addStory" class="btn btn-theme">
          <i class="mdi mdi-file-plus-outline font-size-14 align-middle me-2"></i>
          Add Story
        </router-link>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="main-search-card ">
          <div class="topBarCstmTable mb-3">
            <div class="row gx-2">
              <div class="col">
                <div class="searchBarStyle position-relative">
                  <input type="text" v-model="searchValueTable" class="form-control" placeholder="Search...">
                  <span class="iconSearch bx bx-search-alt"></span>
                </div>
              </div>
              <div class="col-sm-auto mt-3 mt-sm-0 text-end" >
                <button @click="isshow = !isshow" class="btn btn-theme">Advance Search</button>
              </div>
            </div>
          </div>
          <Transition name="fade">
            <div class="main-group-search p-3 border mb-3" v-if="isshow">
              <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                  <label class="form-label">Story Title</label>
                  <div class="single-select2-cstm">
                    <Select2 v-model="storyTitle" placeholder="Select Story Title" :options="storyTitleOptions"
                      :settings="{ multiple: false }" @change="myChangeEvent($event)"
                      @select="mySelectEvent('storyTitle')" />
                  </div>
                </div>
                <div class="col-md-6 mb-3 mb-md-0">
                  <label class="form-label">Client Name</label>
                  <div class="single-select2-cstm">
                    <Select2 v-model="clientName" placeholder="Select Client Name" :options="clientNameOptions"
                      :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)"
                      @select="mySelectEvent('name')" />
                  </div>
                </div>
                <div class="col-12">
                  <div class="row mt-3 gx-2 justify-content-end">
                    <div class="col-auto">
                      <button class="btn btn-light" @click="getAllStories()">Reset</button>
                    </div>
                    <div class="col-auto">
                      <button class="btn btn-theme" @click="filterData()">Apply</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </Transition>
        </div>
        <EasyDataTable :headers="headers" :items="tableItems" :search-value="searchValueTable" border-cell
          theme-color="#3aafa9" buttons-pagination :rows-per-page="25" table-class-name="table-custom-style"
          v-if="initialize">
          <template #item-storyTitle="{ id, storyTitle }">
            <router-link :to="`/single_story/${encode(id)}`" class="text-theme">{{ storyTitle }}</router-link>
          </template>

          <template #item-fullName="{ clientName, user_id }">
            <router-link :to="`/single_client/${encode(user_id)}`" class="text-theme">{{ clientName }} </router-link>
          </template>

          <template #item-lastTask="{ lastTask }">
            <p v-if="lastTask">{{ lastTask.title }}</p>
            <p v-else>Nothing</p>
          </template>
          <template #item-nextTask="{ nextTask }">
            <p v-if="nextTask">{{ nextTask.title }}</p>
            <p v-else>Nothing</p>
          </template>
          <template #item-status="{ status }">
            <span class="text-success" v-if="status == 1">Active</span>
            <span class="text-info" v-else-if="status == 2">Accepted</span>
            <span class="text-danger" v-else>Retired</span>
          </template>

          <template #item-action="{ id }">
            <span class="ms-auto d-flex">
              <router-link :to="`editStory/${encode(id)}`" class="btn btn-sm btn-soft-info">
                <i class="mdi mdi-pencil-outline"></i>
              </router-link>
              <button @click="deleteStory(id)" class="btn btn-sm btn-soft-danger ms-1">
                <i class="mdi mdi-trash-can-outline"></i>
              </button>
            </span>
          </template>
      
        </EasyDataTable>
      </div>
    </div>
  </layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import Select2 from 'vue3-select2-component';
import axios from 'axios';
import CryptoJS from 'crypto-js';


export default {
  page: {
    title: "Stories",
    meta: [
      {
        name: "description",
        content: 'description',
      },
    ],
  },
  data() {
    return {
      url: process.env.VUE_APP_URL,
      dataFetched : false,
      isshow: false,
      isShowData: false,
      title: "Stories",
      initialize: false,
      autoClose: new Date(),
      searchDateClose: new Date(),
      jornalNameSrch: [],
      tableItems: [],
      tableData: [],
      clientNameOptions: ['Erin Rose Belair', 'Gaurav Bhalla'],
      storyTitleOptions: [],
      isOpen: false,
      headers: [
        { text: "Story Title", value: "storyTitle" },
        { text: "Client Name", value: "fullName" },
        { text: "Status", value: "status" },
        { text: "Last Task", value: "lastTask" },
        { text: "Next Task", value: "nextTask" },
        { text: "Action", value: "action" },
      ],
      searchValueTable: "",
      password: this.value,
      storyTitle: "",
      stories: []
    }

  },
  components: {
    Layout,
    PageHeader,
    EasyDataTable,
    Select2
  },

  created() {
    if(!this.dataFetched)
    {
      this.getAllStories()
    }
  },
  activated() {
    if(this.dataFetched)
    {
      this.getAllStories()
    }
  },
  methods: {
    async getAllStories() {
      let stories = []
      if(this.$store.state.normalStore.stories.length)
      {
        stories = this.$store.state.normalStore.stories
      }
      else
      {
        const response = await axios.get(this.url + 'api/getAllStories')
        stories = response.data
      }
      this.clientName = "";
      this.storyTitle = "";
      this.tableItems = stories
      this.initialize = true
      this.tableData = stories;
      this.initialize = true
      this.clientNameOptions = this.tableData.map(({ clientName }) => clientName);
      this.storyTitleOptions = this.tableData.map(({ storyTitle }) => storyTitle);
      this.dataFetched = true
    },
    deleteStory(id) {
      axios.post(this.url + 'api/deleteStory', { 'id': id }).then(() => {
        this.tableData = this.tableData.filter((item)=>item.id!=id);
        this.tableItems = this.tableData;
        this.$store.state.isDeclined = true
        this.$store.state.notificationData = 'Story Deleted Successfully'
        setTimeout(()=>{
          this.$store.state.isDeclined = false
          this.$store.state.notificationData = null
        }, 2500)
      })
    },
    filterData() {
      if (this.clientName && this.storyTitle) {
        this.tableItems = this.tableData.filter(client => client.clientName == this.clientName && client.storyTitle == this.storyTitle)
      }
      else if (this.storyTitle) {
        this.tableItems = this.tableData.filter(client => client.storyTitle == this.storyTitle).reduce((acc, el) => acc.concat(el), []);
      }
      else if (this.clientName) {
        this.tableItems = this.tableData.filter(client => client.clientName == this.clientName);
      }
    },
    mySelectEvent(eventType) {
      if (eventType == 'name') {
        this.storyTitleOptions = this.tableData.filter(client => client.clientName == this.clientName).map(({ storyTitle }) => storyTitle);
      }
      else if (eventType == 'storyTitle') {
        this.clientNameOptions = this.tableData.filter(client => client.storyTitle == this.storyTitle).map(({ clientName }) => clientName).reduce((acc, el) => acc.concat(el), [])
      }
      else {
        this.clientNameOptions = this.tableData.map(({ clientName }) => clientName);
        this.storyTitleOptions = this.tableData.map(({ storyTitle }) => storyTitle);
      }
    },
    encode(id) {
      return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
    },
    formSubmit() {
      this.submitted = true;
      this.v$.$touch();
    },

  }
};
</script>
<style scoped>
.fade-enter-active {
  transition: all 0.3s ease-out;
}

.fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.fade-enter-from,
.fade-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}
</style>