<template>
  <layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader :title="title" />
      </div>
      <div class="card">
        <div class="card-body">
          <div class="main-search-card ">
            <div class="mb-3">
              <div class="row gx-2">
                <div class="col">
                  <h3 class="my-1 font-size-18 text-dark-cstm">Journal Story</h3>
                </div>
                <div class="col-auto">
                  <div class="searchBarStyle topBarCstmTable  position-relative">
                    <input type="text" v-model="searchValueTable" class="form-control" placeholder="Search...">
                    <span class="iconSearch bx bx-search-alt"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <EasyDataTable :headers="headers" :items="tableItems" :search-value="searchValueTable" border-cell
            theme-color="#3aafa9" :rows-per-page="25" buttons-pagination table-class-name="table-custom-style" >
            <template #item-storyTitle="{ storyTitle }">
              <span class="text-theme">{{ storyTitle }}</span>
            </template>

            <template #item-isAnthologies={isAnthologies}>
              <span v-if="isAnthologies=='true'" class="text-success">True</span>
              <span v-else class="text-danger">False</span>
            </template>
            <template #item-anthology="{ isAnthologies, anthologies}">
              <span v-if="isAnthologies=='true'">{{anthologies}}</span>
              <span v-else>NULL</span>
            </template>
            <template #item-action="{ id }">
              <span class="ms-auto d-flex">
                <router-link :to="`/edit_journalStory/${encode(id)}`" class="btn btn-sm btn-soft-info">
                  <i class="mdi mdi-pencil-outline"></i>
                </router-link>
                <button class="btn btn-sm btn-soft-danger ms-1" @click="deleteJournalStory(id)">
                  <i class="mdi mdi-trash-can-outline"></i>
                </button>
              </span>
            </template>
          </EasyDataTable>
        </div>
      </div>
    </div>
  </layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import EasyDataTable from 'vue3-easy-data-table';
import axios from "axios";
import CryptoJS from 'crypto-js';
import 'vue3-easy-data-table/dist/style.css';

export default {
  page: {
    title: "Journal Story",
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
      headers: [
        { text: "Story Title", value: "storyTitle" },
        { text: "Year", value: "year" },
        { text: "Is-Anthology", value: "isAnthologies" },
        { text: "Anthology", value: "anthology" },
        { text: "Dummy Story", value: "dummyStory" },
        { text: "Quality", value: "quality" },
        { text: "Stylized", value: "stylized" },
        { text: "Difficulty", value: "difficulty" },
        { text: "Topical", value: "topical" },
        { text: "Experimental", value: "experimental" },
        { text: "Humor", value: "humor" },
        { text: "Strange", value: "strange" },
        { text: "Dark", value: "dark" },
        { text: "International", value: "international" },
        { text: "Margins", value: "margins" },
        { text: "Historical", value: "historical" },
        { text: "Long", value: "long" },
        { text: "Short", value: "short" },
        { text: "Action", value: "action" },
      ],
      tableItems: [],
      searchValueTable: "",
      cardShow: false,
      dummyStory: '',
      showForm: true,
    }
  },
  components: {
    Layout,
    PageHeader,
    EasyDataTable
  },
  beforeMount() {
    this.getJournalStories();
  },
  watch: {
    $route() {
      this.getJournalStories();
    }
  },
  methods: {
    getJournalStories() {
      if(this.$store.state.normalStore.journalStories.length)
      {
        this.tableItems = this.$store.state.normalStore.journalStories.filter(item=>item.journal_id==this.decode(this.$route.params.id))
      }
      else
      {
        axios.post(this.url + 'api/getJournalStory', { 'id': this.decode(this.$route.params.id) }).then((res) => {
          this.tableItems = res.data
        })
      }
    },
    deleteJournalStory(id) {
      axios.post(this.url + 'api/deleteJournalStory', { 'id': id }).then(() => {
        this.tableItems = this.tableItems.filter(item=>item.id != id)
        this.$store.dispatch('deleteJournalStories', id)
      })
    },

    addDummyStory() {
      this.cardShow = !this.cardShow
      axios.post(this.url + 'api/addDummyStory', { "id": this.decode(this.$route.params.id), "dummyStory": this.dummyStory })
    },
    encode(id) {
      return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
    },
    decode(id) {
      return decodeURIComponent(CryptoJS.AES.decrypt(String(id), "Secret Passphrase").toString(CryptoJS.enc.Utf8));
    },
  }

}
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