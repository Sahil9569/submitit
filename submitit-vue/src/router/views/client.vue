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
    title: "Client",
    meta: [
      {
        name: "description",
        content: 'description',
      },
    ],
  },

  props: {
    type: {
      type: String,
      default: 'text'
    },
    size: {
      type: String,
      default: '20'
    },
    characters: {
      type: String,
      default: 'a-z,A-Z,0-9,#'
    },
    placeholder: {
      type: String,
      default: 'Password'
    },
    auto: [String, Boolean],
    value: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      url: process.env.VUE_APP_URL,
      dataFetched : false,
      isshow: false,
      isShowData: false,
      title: "Client",
      autoClose: new Date(),
      searchDateClose: new Date(),
      clientNameOptions: [],
      clientEmailOptions: [],
      storyGensOption: ['Fiction', 'Nonfiction'],
      storyStatusOption: ['Due', 'Active', 'Rejected'],
      storyTitleOptions: [],
      isOpen: false,
      headers: [
        { text: "Name", value: "fullName" },
        { text: "Story Title", value: "storyTitle" },
        { text: "Email", value: "email" },
        { text: "Referral", value: "referral" },
        { text: "Status", value: "status" },
        { text: "Date", value: "date" },
        { text: "User-Type", value: "userType" },
      ],
      tableItems: [],
      tableData: [],
      searchValueTable: "",
      password: this.value,
      storyTitle: ""
    }
  },
  components: {
    Layout,
    PageHeader,
    EasyDataTable,
    Select2
  },
  beforeMount() {
    if(!this.dataFetched)
    {
      this.getClients();
    }
  },
  activated() {
    if(this.dataFetched)
    {
      this.getClients()
      this.$store.dispatch('getAllData') 
    }
  },
  methods: {
    mySelectEvent(eventType) {
      if (eventType == 'email') {
        this.clientNameOptions = this.tableData.filter(client => client.email == this.clientEmail).map(({ firstName, lastName }) => firstName + ' ' + lastName)
        let clientId = this.tableData.filter((client => client.email == this.clientEmail)).map(({ id }) => id).reduce((acc, el) => acc.concat(el), [])
        this.storyTitleOptions = this.tableData.map(({ stories }) => stories.filter(({ user_id }) => user_id == clientId).map(({ storyTitle }) => storyTitle)).reduce((acc, el) => acc.concat(el), [])
      }
      else if (eventType == 'name') {
        this.clientEmailOptions = this.tableData.filter(client => client.firstName == this.clientName.split(' ')[0]).map(({ email }) => email)
        let clientId = this.tableData.filter((client => client.firstName == this.clientName.split(' ')[0])).map(({ id }) => id).reduce((acc, el) => acc.concat(el), [])
        this.storyTitleOptions = this.tableData.map(({ stories }) => stories.filter(({ user_id }) => user_id == clientId).map(({ storyTitle }) => storyTitle)).reduce((acc, el) => acc.concat(el), [])
      }
      else if (eventType == 'storyTitle') {
        let story = this.tableData.map(({ stories }) => stories.filter(({ storyTitle }) => storyTitle == this.storyTitle).map(({ user_id }) => user_id)).reduce((acc, el) => acc.concat(el), [])
        this.clientNameOptions = this.tableData.filter(client => client.id == story).map(({ firstName, lastName }) => firstName + ' ' + lastName)
        this.clientEmailOptions = this.tableData.filter(client => client.id == story).map(({ email }) => email)
      }
      else {
        this.clientNameOptions = this.tableData.map(({ firstName, lastName }) => firstName + ' ' + lastName)
        this.clientEmailOptions = this.tableData.map(({ email }) => email)
        this.storyTitleOptions = this.tableData.map(({ stories }) => stories.map(({ storyTitle }) => storyTitle)).reduce((acc, el) => acc.concat(el), []);

      }
    },
    getClients() {
      this.clientName = "";
      this.clientEmail = "";
      this.storyTitle = "";
      axios.get(this.url + 'api/GetClients').then(res => {
        this.tableItems = res.data;
        this.tableData = res.data;
        this.clientNameOptions = this.tableItems.map(({ firstName, lastName }) => firstName + ' ' + lastName);
        this.clientEmailOptions = this.tableItems.map(({ email }) => email);
        this.storyTitleOptions = this.tableItems.map(({ stories }) => stories.map(({ storyTitle }) => storyTitle)).reduce((acc, el) => acc.concat(el), []);
        this.dataFetched = true
      })
    },
    filterData() {
      if (this.clientEmail) {
        this.tableItems = this.tableData.filter(client => client.email == this.clientEmail)
      }
      else if (this.clientName) {
        this.tableItems = this.tableData.filter(client => client.firstName == this.clientName.split(' ')[0])
      }
      else if (this.storyTitle) {
        let story = this.tableData.map(({ stories }) => stories.filter(({ storyTitle }) => storyTitle == this.storyTitle).map(({ user_id }) => user_id)).reduce((acc, el) => acc.concat(el), [])
        this.tableItems = this.tableData.filter(client => client.id == story)
      }
    },
    encode(id) {
      return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
    }
  }
};
</script>
<template>
  <layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader :title="title" />
      </div>
      <div class="col-auto">
        <!-- <a href="javascript:void(0)" class="btn btn-theme"><i class="bx bx-user-plus font-size-18 align-middle me-2"></i>Add Client</a> -->
        <router-link to="/addClient" class="btn btn-theme">
          <i class="bx bx-user-plus font-size-18 align-middle me-2"></i>
          Add Client
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
              <div class="col-auto">
                <button @click="isshow = !isshow" class="btn btn-theme">Advance Search</button>
              </div>
            </div>
          </div>
          <Transition name="fade">
            <div class="main-group-search p-3 border mb-3" v-if="isshow">
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label">Client Name</label>
                  <div class="single-select2-cstm">
                    <Select2 v-model="clientName" placeholder="Select Client Name" :options="clientNameOptions"
                      :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)"
                      @select="mySelectEvent('name')" />
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Story Title</label>
                  <!-- <div class="multipleSelectCstm"></div> -->
                  <div class="single-select2-cstm">
                    <Select2 v-model="storyTitle" placeholder="Select Story Title" :options="storyTitleOptions"
                      :settings="{ multiple: false }" @change="myChangeEvent($event)"
                      @select="mySelectEvent('storyTitle')" />
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Client Email</label>
                  <div class="single-select2-cstm">
                    <Select2 v-model="clientEmail" placeholder="Select Client Email" :options="clientEmailOptions"
                      :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)"
                      @select="mySelectEvent('email')" />
                  </div>
                </div>
                <div class="col-12">
                  <div class="row mt-3 gx-2 justify-content-end">
                    <div class="col-auto">
                      <button class="btn btn-light" @click="getClients()">Reset</button>
                    </div>
                    <div class="col-auto">
                      <button class="btn btn-theme" @click="filterData()">Apply</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </Transition>

          <EasyDataTable :headers="headers" :items="tableItems" :search-value="searchValueTable" border-cell
            theme-color="#3aafa9" :rows-per-page="25" buttons-pagination table-class-name="table-custom-style">
            <template #loading>
              <img src="https://i.pinimg.com/originals/94/fd/2b/94fd2bf50097ade743220761f41693d5.gif"
                style="width: 100px; height: 80px;" />
            </template>
            <template #item-fullName="{ firstName, lastName, id }">
              <router-link :to="`/single_client/${encode(id)}`" class="text-theme"> {{ firstName }}
                {{ lastName }}</router-link>
            </template>
            <template #item-storyTitle="{ stories, id }">
              <div class="row gx-2">
                <template v-if="stories.length != 0">
                  <div class="col-auto" v-for="(story, index) in stories" :key="story.id">
                    <template v-if="index<3">
                      <router-link :to="`/single_story/${encode(story.id)}`"
                        class="bg-theme bg-soft text-theme p-1 rounded line-height-1 font-size-11">{{
                          story.storyTitle }}</router-link>
                    </template>
                    <template v-else-if="index>2 && index<4">
                      <div class="col-auto">
                        <span>...</span>
                      </div>
                    </template>
                  </div>
                </template>
                <template v-else>
                  <div class="col-auto">
                    <router-link :to="`/addStory/${encode(id)}`" class="text-theme font-size-13"><u>Add
                        Story</u></router-link>
                  </div>
                </template>
              </div>
            </template>
            <template #item-userType="{roleAs}">
              <span v-if="roleAs !== 'normal'">Super</span>
              <span v-else>Normal</span>
            </template>
            <template #item-status="{ status }">
              <span :class="{
                'text-success': `${status}` === '1',
                'text-danger': `${status}` === '0',
                'text-warning': `${status}` === '-1',
              }">
                <p v-if="status == '1'">Active</p>
                <p v-else-if="status == '0'">Retired</p>
                <p v-else>Signed Up</p>
              </span>
            </template>
            <template #item-referral="{ referral }">
              <p class="text-theme"><span v-if="referral">{{ referral }}</span><span v-else>Empty</span></p>
            </template>
          </EasyDataTable>
        </div>
      </div>
    </div>
  </layout>
</template>
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