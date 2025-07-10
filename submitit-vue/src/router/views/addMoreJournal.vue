<template>
  <layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader :title="title" />
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="main-search-card ">
          <div class="mb-3">
            <div class="row gx-2">
              <div class="col">
                <h3 class="my-1 font-size-18 text-dark-cstm">Add More Journals</h3>
              </div>
              <div class="col">
                <div class="searchBarStyle topBarCstmTable  position-relative">
                  <input type="text" v-model="searchValueTable" class="form-control" placeholder="Search...">
                  <span class="iconSearch bx bx-search-alt"></span>
                </div>
              </div>
              <div class="col-auto">
                <button @click="isshow = !isshow" class="btn btn-theme">Advance Search</button>
              </div>
            </div>
          </div>
        </div>
        <Transition name="fade">
          <div class="main-group-search p-3 border mb-3" v-if="isshow">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Journals Rating</label>
                <div class="multipleSelectCstm">
                  <Select2 v-model="journalRatingMain" placeholder="Select Rating" :settings="{ multiple: true }"
                    :options="journalRatingOptions" />
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Acceptance Percent</label>
                <div class="multipleSelectCstm">
                  <Select2 v-model="AcceptPreMain" placeholder="Select Acceptance Percent" :settings="{ multiple: true }"
                    :options="AcceptPreOptions" />
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Volume</label>
                <div class="single-select2-cstm">
                  <Select2 v-model="volumeMain" placeholder="Select Volume" :options="volumeOptions" />
                </div>
              </div>
              <div class="col-12">
                <div class="col-md-6 mb-3">
                  <label class="labelCheckboxCstm">
                    <input type="checkbox" name="storyCheckbox" :true-value="true" :false-value="false"
                      v-model="showAll" @change="update"/>
                    <span class="checkmark pe-1">
                      <i class="mdi mdi-checkbox-blank-outline font-size-20 text-theme"></i>
                      <i class="mdi mdi-checkbox-marked-outline font-size-20 text-theme"></i>
                    </span>
                    Show all open Journals
                  </label>
                </div>
                <div class="row mt-3 gx-2 justify-content-end">
                  <div class="col-auto">
                    <button class="btn btn-light" @click="getJournal()">Reset</button>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-theme" @click="filterData()">Apply</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Transition>
        <EasyDataTable ref="myTable" @click="copyTable" v-model:items-selected="itemsSelected" show-index
          :headers="headers" :items="tableItems" :search-value="searchValueTable" border-cell theme-color="#3aafa9"
          :rows-per-page="25" buttons-pagination table-class-name="table-custom-style">
          <template #item-journalMain="{ jrnlName, rating, matchScore }">
            <span class="badge badge-soft-success">Match Percent: {{ matchScore }}%</span>
            <span class="text-dark-cstm d-block">
              {{ jrnlName }}
            </span>
            <div class="d-flex">
              <template v-for="jRating in parseInt(rating.slice(0, 1))" :key="jRating">
                <i :class="`mdi mdi-star font-size-13 text-warning`"></i>
              </template>
              <template v-for="jRating in 5 - parseInt(rating.slice(0, 1))" :key="jRating">
                <i :class="`mdi mdi-star-outline font-size-13 text-warning`"></i>
              </template>
            </div>

          </template>
          <template #item-acceptance="{ acceptance }">
            <p>{{ (acceptance * 100).toFixed(2) }}%</p>
          </template>
          <template #item-possibleThemes="{ possibleThemes }">
            <p v-if="possibleThemes==1">Yes</p>
            <p v-else>No</p>
          </template>

        </EasyDataTable>
        <div class="mt-3 text-end">
          <button type="button" class="btn btn-theme" @click="addInList">Add to List</button>
        </div>
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
import CryptoJS from "crypto-js";

export default ({
  page: {
    title: "Add More Journal",
    meta: [
      {
        name: "description",
        content: 'description',
      },
    ],
  },

  data() {

    return {
      title: "",
      showAll: false,
      url: process.env.VUE_APP_URL,
      searchValueTable: "",
      isshow: false,
      journalRatingMain: [],
      volumeMain: "",
      AcceptPreMain: "",
      AcceptPreOptions: ['<1%', '1-3%', '3-5%', '>5%'],
      journalRatingOptions: ['1 Stars', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
      volumeOptions: ['1', '2', '3', '4', '5'],
      itemsSelected: [],
      headers: [
        { text: "Journals", value: "journalMain" },
        { text: "Year", value: "yearOfInception" },
        { text: "Accep. %", value: "acceptance" },
        { text: "Volume", value: "volume" },
        { text: "Style", value: "permanentThemes" },
        { text: "Flash", value: "flash" },
        { text: "Possible Themes", value: "possibleThemes" },
        { text: "Themes", value: "theme" },
      ],
      tableItems: [],
      unmutableTableItems: [],
    }
  },
  components: {
    Layout,
    PageHeader,
    EasyDataTable,
    Select2
  },
  activated() {
    // this.itemsSelected = this.$store.state.selectedJournals
    // this.tableItems.sort((a, b) => (a.matchScore < b.matchScore) ? 1 : -1)
    // this.tableItems = this.$store.state.otherJournals
    this.unmutableTableItems = this.$store.state.otherJournals
    this.unmutableTableItems.sort((a, b) => (a.matchScore < b.matchScore) ? 1 : -1)
    // this.getJournal();
  },
  beforeUpdate() {
    // this.tableItems = this.$store.state.otherJournals
    // this.tableItems.sort((a, b) => (a.matchScore < b.matchScore) ? 1 : -1)
    this.unmutableTableItems = this.$store.state.otherJournals
    this.unmutableTableItems.sort((a, b) => (a.matchScore < b.matchScore) ? 1 : -1)
  },
  // add New functionality
  watch: {
    $route() {
      this.resetInputField();
    }
  },
  methods: {
    getJournal() {
      this.journalRatingMain = [];
      this.AcceptPreMain = "";
      this.volumeMain = "";
      let journalData = this.$store.state.otherJournals;
      journalData.sort((a, b) => (a.matchScore < b.matchScore) ? 1 : -1)
      this.tableItems = journalData;
    },
    update()
    {
      const rejectJournals = this.$store.state.rejectJournals
      const allJournals = this.$store.state.otherJournals
      if(this.showAll)
      {
        const newJournals = allJournals.concat(rejectJournals) 
        const uniqueData = Array.from(newJournals.reduce((map, item) => {
            map.set(item.id, item);
            return map;
          }, new Map()).values());
        this.tableItems = uniqueData;
        this.unmutableTableItems = uniqueData;
      }
      else
      {
        this.tableItems = allJournals
        this.unmutableTableItems = allJournals
      }
      this.tableItems.sort((a, b) => (a.matchScore < b.matchScore) ? 1 : -1)
      this.unmutableTableItems.sort((a, b) => (a.matchScore < b.matchScore) ? 1 : -1)
      this.filterData()
    },
    addInList() {
      if (this.itemsSelected.length > 0) {
        axios.post(this.url + 'api/addMoreJournal', this.itemsSelected).then(res => {
          this.itemsSelected = [];
          const story_id = res.data.flat().map(story => story.story_id);
          this.$router.push({ path: `/single_story/${this.encode(story_id[0])}` });
        })
      } else {
        this.$router.go(-1)
      }
      // this.$store.state.selectedJournals = this.itemsSelected
      // this.$router.go(-1)
    },
    filterData() {
      // this.tableItems = this.unmutableTableItems
      let items = this.unmutableTableItems
      if (this.journalRatingMain.length >0 ) {
        items = items.filter(journal => this.journalRatingMain.includes(journal.rating));
       
      }
      if (this.AcceptPreMain) {
          items = items.filter(journal =>
          {
            return this.AcceptPreMain.some(acceptance => {
              if(acceptance==='<1%')
              {
                return (journal.acceptance * 100).toFixed(2) < 1
              }
              if(acceptance==='1-3%')
              {
                return (journal.acceptance * 100).toFixed(2) >= 1 && (journal.acceptance * 100).toFixed(2) <3
              }
              if(acceptance=='3-5%')
              {
                return (journal.acceptance * 100).toFixed(2) >= 3 && (journal.acceptance * 100).toFixed(2) <= 5
              }
              if(acceptance=='>5%')
              {
                return (journal.acceptance * 100).toFixed(2) > 5
              }
              return false;
            })
          } )
       

      }
      if(this.volumeMain)
      {
        
          items = items.filter(journal => journal.submissionVolume == this.volumeMain)
      }
      // else if (this.journalRatingMain && this.AcceptPreMain) {
      //   this.journalRatingMain.forEach(function (rating) {
      //     data.push(items.filter(journal => journal.rating == rating))
      //   });
      //   let datas = data.reduce((acc, el) => acc.concat(el), [])
      //   this.tableItems = datas.filter(journal => journal.acceptancePercent >= 3 && journal.acceptancePercent <= 5)

      // } else if (this.AcceptPreMain && this.volumeMain) {
      //   this.tableItems = this.tableItems.filter(journal => journal.acceptancePercent >= 3 && journal.acceptancePercent <= 5 && journal.submissionVolume == this.volumeMain);
      // } else if (this.journalRatingMain && this.volumeMain) {
      //   this.journalRatingMain.forEach(function (rating) {
      //     data.push(items.filter(journal => journal.rating == rating))
      //   });
      //   let datas = data.reduce((acc, el) => acc.concat(el), [])
      //   this.tableItems = datas.filter(journal => journal.submissionVolume == this.volumeMain)

      // } else if (this.journalRatingMain) {
      //   this.journalRatingMain.forEach(function (rating) {
      //     data.push(items.filter(journal => journal.rating == rating))
      //   });
      //   this.tableItems = data.flat(1)

      // } else if (this.AcceptPreMain == '<1%') {
      //   this.AcceptPreMain.forEach(function () {
      //     data.push(items.filter(journal => journal.acceptancePercent <= 1))
      //   });
      //   this.tableItems = data.flat(1)
      //   // this.tableItems = this.tableItems.filter(journal => journal.acceptancePercent >= 3 && journal.acceptancePercent <= 5)


      // } else if (this.volumeMain) {
      //   this.tableItems = this.tableItems.filter(journal => journal.submissionVolume == this.volumeMain)

      // }
      this.tableItems = items
    },
    encode(id) {
      return encodeURIComponent(
        CryptoJS.AES.encrypt(String(id), "Secret Passphrase")
      );
    },
    resetInputField() {
      this.itemsSelected = []
      this.journalRatingMain = []
      this.AcceptPreMain = []
      this.volumeMain = ''
      this.tableItems = []
    },
  }

})
</script>