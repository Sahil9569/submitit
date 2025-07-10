<template>
  <layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader :title="title" />
      </div>
      <div class="col-auto">
        <!-- <a href="javascript:void(0)" class="btn btn-theme"><i class="bx bx-user-plus font-size-18 align-middle me-2"></i>Add Client</a> -->
        <router-link to="/add_story" class="btn btn-theme">
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
              <div class="col-auto">
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
                  <label class="form-label">Story Genre</label>
                  <div class="single-select2-cstm">
                    <Select2 v-model="genreMain" placeholder="Select Story Genre" :options="genreOptions"
                      :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)"
                      @select="mySelectEvent('storyGenre')" />
                  </div>
                </div>
                <!-- <div class="col-md-4 mb-3">
                        <label class="form-label">Journals</label>
                        <div class="single-select2-cstm">
                          <Select2  v-model="jornalNameSrch" placeholder="Select Client Name" :options="journalNameOptions" :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)" @select="mySelectEvent('name')" />
                        </div>
                      </div> -->
                <!-- <div class="col-md-4 mb-3">
                        <label class="form-label">Submission  Strategy</label>
                        <div class="single-select2-cstm">
                          <Select2  v-model="clientEmail" placeholder="Select Strategy" :options="strategyOptions" :settings="{ settingOption: value, settingOption: value }"  />
                        </div>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label">Story Genre</label>
                        <div class="single-select2-cstm">
                          <Select2  v-model="storyGenre" placeholder="Select Story Genre" :options="storyGensOption" :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)" @select="mySelectEvent('storyGenre')" />
                        </div>
                      </div> -->
                <!-- <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <div class="single-select2-cstm">
                          <Select2  placeholder="Select Status" :options="storyStatusOption" :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)" @select="mySelectEvent('status')" />
                        </div>
                      </div> -->
                <!-- <div class="col-md-4 mb-3">
                        <label class="form-label">Date</label>
                          <div class="input-group w-100 inputFieldDate">
                            <datepicker v-model="searchDateClose" :first-day-of-week="1" lang="en" class="form-control"></datepicker>
                            <span class="input-group-text bg-theme"><i class="mdi mdi-calendar text-white"></i></span>
                          </div>
                      </div> -->
                <!-- <div class="col-md-4 mb-3">
                        <label class="form-label">Word Count</label>
                        <input type="text" class="form-control" placeholder="Type word count"/>
                      </div> -->

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
          theme-color="#3aafa9" buttons-pagination :rows-per-page="25" table-class-name="table-custom-style">
          <template #item-storyTitle="{ id, storyTitle }">
            <router-link :to="`/story_information/${encode(id)}`" class="text-theme">{{ storyTitle }}</router-link>
          </template>
          <template #item-storyGenre="{ storyGenre }">
            <div class="text-theme">{{ storyGenre }}</div>
          </template>

          <template #item-journalList="{ journalName, journalUrl }">
            <div class="row gx-2">
              <div class="col-auto" v-for="(journalNm, index) in journalName.length" :key="index">
                <router-link :to="journalUrl" class="bg-theme bg-soft text-theme p-1 rounded line-height-1 font-size-11">
                  {{ journalName[index] }}
                </router-link>
              </div>
              <div class="col-auto">...</div>
            </div>
          </template>

          <!-- <template #item-storyStatus="{storyStatus}">
                    <span :class="{
                      'text-danger': `${storyStatus}` === 'Retired',
                      'text-success': `${storyStatus}` === 'Active',
                      'text-warning': `${storyStatus}` === 'Accepted',
                    }">{{storyStatus}}</span>
                  </template> -->

          <!-- <template #item-document="{ documentIcon, documentUrl }">
                    <div class="">
                      <div class="mainGroupDoc">
                        <div class="innerDocGroup" v-for="(document, index) in documentUrl.length" :key="index">
                          <router-link :to="documentUrl[index]" title="Document Name" class="text-theme"><i :class="`${documentIcon} text-theme`"></i> </router-link>
                        </div>
                      </div>
                    </div>
                  </template> -->

          <!-- <template #item-lastTask="{lastTaskTitle, lastTaskTime}">
                    <span class="d-block text-muted"><strong class="font-weight-500 text-dark-cstm">{{lastTaskTitle}}</strong></span>
                    <span class="d-flex text-muted"><i class="mdi mdi-calendar me-1"></i> {{lastTaskTime}}</span>
                  </template>
                  <template #item-nextTask="{nextTaskTitle, nextTaskTime}">
                    <span class="d-block text-muted"><strong class="font-weight-500 text-dark-cstm">{{nextTaskTitle}}</strong></span>
                    <span class="d-flex text-muted"><i class="mdi mdi-calendar me-1"></i> {{nextTaskTime}}</span>
                  </template> -->

          <!-- <template #item-status="{stories}">
                    <span v-if="stories.length!=0">
                      <span v-for="story in stories" :key="story.id">
                        <span v-if="story.status==1">Active</span>
                        <span v-else> Due</span>
                      </span>
                    </span>
                    <span v-else>No Status</span>
                  </template> -->
        </EasyDataTable>
      </div>
    </div>
  </layout>
</template>
<script>
import Layout from "../../router/layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import Select2 from 'vue3-select2-component';
import axios from "axios";
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
      isshow: false,
      isShowData: false,
      title: "Stories",
      autoClose: new Date(),
      searchDateClose: new Date(),
      jornalNameSrch: [],
      genreOptions: ['Fiction', 'Nonfiction'],
      storyTitleOptions: [],
      isOpen: false,
      headers: [
        { text: "Story Title", value: "storyTitle" },
        { text: "Story Genre", value: "storyGenre" },
        { text: "Word Count", value: "wordCount" },
        { text: "Journals Submitted", value: "submitted" },
        { text: "Journals Rejected", value: "rejected" },
        { text: "Journals Withdrawn", value: "withdrawn" },
        { text: "Journals Accepted", value: "accepted" },
      ],
      tableItems: [],
      tableData:[],
      searchValueTable: "",
      password: this.value,
      storyTitle: ""
    }

  },
  components: {
    Layout,
    PageHeader,
    EasyDataTable,
    // Datepicker,
    Select2
  },
  mounted() {
    this.getAllStories()
  },
  activated() {
    this.getAllStories()
  },
  created(){
    this.getAllStories()

  },
  methods: {
    getAllStories() {
      this.storyTitle = "";
      this.genreMain = "";
      axios.post(this.url + 'api/clientStories', { id: atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId')))) }).then(res => {
        this.tableItems = res.data
        this.tableData = res.data
        this.genreOptions = ['fiction', 'nonfiction'];
        this.storyTitleOptions = this.tableData.map(({ storyTitle }) => storyTitle);
      })
    },
    mySelectEvent(eventType) {
      if (eventType == 'storyTitle') {
        this.genreOptions = this.tableData.filter(({ storyTitle }) => storyTitle == this.storyTitle).map(({ storyGenre }) => storyGenre);
      } else if (eventType == 'storyGenre') {
        this.storyTitleOptions = this.tableData.filter(({ storyGenre }) => storyGenre == this.genreMain).map(({ storyTitle }) => storyTitle);
      } else {
        this.storyTitleOptions = this.tableData.map(({ storyTitle }) => storyTitle);
        this.genreOptions = this.tableData.map(({ storyGenre }) => storyGenre);
      }
    },
    filterData() {
      if (this.storyTitle) {
        this.tableItems = this.tableData.filter(({ storyTitle }) => storyTitle == this.storyTitle);
      } else if (this.genreMain) {
        this.tableItems = this.tableData.filter(({ storyGenre }) => storyGenre == this.genreMain);
      }
    },
    encode(id) {
      return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
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