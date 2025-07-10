<template>
  <layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader :title="title" />
      </div>
      <div class="col-auto">
        <!-- <a href="javascript:void(0)" class="btn btn-theme"><i class="bx bx-user-plus font-size-18 align-middle me-2"></i>Add Client</a> -->
        <router-link to="/addJournal" class="btn btn-theme">
          <i class="mdi mdi-file-document-outline font-size-14 align-middle me-2"></i>
          Add Journal
        </router-link>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="main-search-card ">
          <div class="mb-3">
            <div class="row gx-3">
              <div class="col">
                <div class="row gx-2 mb-md-0 mb-3">
                  <div class="col-md-4 col-lg-3 col-6">
                    <label class="form-label mb-0">Rating</label>
                    <select class="form-select" @change="sortByRating" v-model="rating" >
                      <option disabled selected="selected" value="null">Rating</option>
                      <option value="1" selected>Ascending to Desceding</option>
                      <option value="0">Desceding to Ascending</option>
                    </select>
                  </div>
                  <div class="col-md-4 col-lg-3 col-6">
                    <label class="form-label mb-0">Volume</label>
                    <select class="form-select" v-model="volume" @change="sortByVolume">
                      <option disabled  selected="selected" value="null">Volume</option>
                      <option value="1" selected>Ascending to Desceding</option>
                      <option value="0">Desceding to Ascending</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-auto">
                <div class="row gx-2">
                  <div class="col-sm-auto">
                    <div class="topBarCstmTable minWdthMbl searchBarStyle position-relative">
                      <input type="text" v-model="searchValueTable" class="form-control" placeholder="Search...">
                      <span class="iconSearch bx bx-search-alt"></span>
                    </div>
                  </div>
                  <div class="col-sm-auto mt-2 mt-sm-0 text-end text-md-start">
                    <button @click="isshow = !isshow" class="btn btn-theme">Advance Search</button>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <Transition name="fade">
          <div class="main-group-search p-3 border mb-3" v-if="isshow">
            <div class="row">
              <div class="col-md-3 mb-3">
                <label class="form-label">Journals Name</label>
                <div class="single-select2-cstm">
                  <Select2 v-model="journal.jrnlName" placeholder="Select Name" :options="journalNameOptions"
                    :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)"
                    @select="mySelectEvent('jrnlName')" />
                </div>
              </div>
              <div class="col-sm-6 col-lg-3 mb-3">
                <label class="form-label">Journals Rating</label>
                <div class="single-select2-cstm">
                  <Select2 v-model="journal.rating" placeholder="Select Rating" :options="journalRatingOptions"
                    :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)"
                    @select="mySelectEvent('rating')" />
                </div>
              </div>
              <div class="col-sm-6 col-lg-3 mb-3">
                <label class="form-label">Flash</label>
                <div class="single-select2-cstm">
                  <Select2 v-model="journal.flash" placeholder="Select Flash" :options="flashOption"
                    :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)"
                    @select="mySelectEvent('flash')" />
                </div>
              </div>
              <div class="col-sm-6 col-lg-3 mb-3">
                <label class="form-label">Nonfiction</label>
                <div class="single-select2-cstm">
                  <Select2 v-model="journal.nonFictionOnly" placeholder="Select Nonfiction" :options="nonFictionOptions"
                    :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)"
                    @select="mySelectEvent('nonFictionOnly')" />
                </div>
              </div>
              <div class="col-sm-6 col-lg-3 mb-3">
                <label class="form-label">Volume</label>
                <!-- <div class="single-select2-cstm">
                          <Select2 v-model="journal.nonFiction" placeholder="Select Nonfiction" :options="nonFictionOptions"/>
                        </div> -->
                <input type="number" v-model="journal.volume" class="form-control" placeholder="0">
              </div>
              <div class="col-sm-6 col-lg-3 mb-3">
                <label class="form-label">Minimum Length</label>
                <!-- <div class="single-select2-cstm">
                          <Select2 v-model="journal.nonFiction" placeholder="Select Nonfiction" :options="nonFictionOptions"/>
                        </div> -->
                <input type="number" v-model="journal.minimumLength" class="form-control" placeholder="0">
              </div>
              <div class="col-sm-6 col-lg-3 mb-3">
                <label class="form-label">Maximum Length</label>
                <!-- <div class="single-select2-cstm">
                          <Select2 v-model="journal.nonFiction" placeholder="Select Nonfiction" :options="nonFictionOptions"/>
                        </div> -->
                <input type="number" v-model="journal.maximumLength" class="form-control" placeholder="0">
              </div>
              <div class="col-sm-6 col-lg-3 mb-3">
                <label class="form-label">Year</label>
                <div class="single-select2-cstm">
                  <Select2 v-model="journal.yearOfInception" placeholder="Select Year" :options="yearOptionSelect"
                    :settings="{ settingOption: value, settingOption: value }" @change="myChangeEvent($event)"
                    @select="mySelectEvent('yearOfInception')" />
                </div>
              </div>
              <div class="col-12">
                <div class="row mt-3 gx-2 justify-content-end">
                  <div class="col-auto">
                    <button class="btn btn-light" @click="getJournals()">Reset</button>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-theme" @click="filterData()">Apply</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Transition>
        <EasyDataTable :headers="headers" :items="tableItems" :search-value="escapedSearchValue" :sort-by="['yearOfInception']"
        :sort-type="['asc']" border-cell
          theme-color="#3aafa9" :rows-per-page="25" buttons-pagination table-class-name="table-custom-style">
          <template #item-jrnlName="{ id, jrnlName, rating }">
            <router-link :to="`/single_journal/${encode(id)}`" class="text-theme">
              {{ jrnlName }}
            </router-link>
            <div class="d-flex">
              <template v-for="jRating in parseInt(rating.slice(0, 1))" :key="jRating">
                <i :class="`mdi mdi-star font-size-13 text-warning`"></i>
              </template>
              <template v-for="jRating in 5 - parseInt(rating.slice(0, 1))" :key="jRating">
                <i :class="`mdi mdi-star-outline font-size-13 text-warning`"></i>
              </template>
            </div>
          </template>
          <template #item-nonFictionOnly="{ nonFictionOnly }">
            <p v-if="nonFictionOnly">True</p>
            <p v-else>False</p>
          </template>
          <template #item-acceptance="{ acceptance }">
            <p>{{(acceptance*100).toFixed(2)}}%</p>
          </template>
          <template #item-actionJournal="{ id }">
            <span class="ms-auto d-flex">
              <router-link :to="`/edit_journal/${encode(id)}`" class="btn btn-sm btn-soft-info">
                <i class="mdi mdi-pencil-outline"></i>
              </router-link>
              <button class="btn btn-sm btn-soft-danger ms-1" @click="deleteJournal(id)">
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
import axios from "axios";
import Select2 from 'vue3-select2-component';
import CryptoJS from 'crypto-js';
// import Select2 from 'vue3-select2-component';


export default {
  page: {
    title: "Journal Information",
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
      title: "Journal Information",
      journal: {
        jrnlName: null,
        rating: null,
        flash: null,
        nonFictionOnly: null,
        volume: null,
        minimumLength: null,
        maximumLength: null,
        yearOfInception: null
      },
      journalNameOptions: [],
      journalRatingOptions: [],
      flashOption: [],
      nonFictionOptions: [{ id: 1, text: 'True' }, { id: 0, text: 'False' }],
      yearOptionSelect: [],
      volumeMain: [],
      minimumMain: [],
      maximumMain: [],
      headers: [
        { text: "Journal Name", value: "jrnlName" },
        { text: "Year", value: "yearOfInception" },
        { text: "Acceptance %", value: "acceptance" },
        { text: "Volume", value: "volume" },
        { text: "Min", value: "minimumLength" },
        { text: "Max", value: "maximumLength" },
        { text: "Style", value: "permanentThemes" },
        { text: "Flash", value: "flash" },
        { text: "Nonfiction", value: "nonFictionOnly" },
        { text: "Action", value: "actionJournal" },
      ],
      tableItems: [],
      tableData: [],
      searchValueTable: "",
      rating : null,
      volume : null
      //   password: this.value,
      //   storyTitle : ""
    }
  },
  components: {
    Layout,
    PageHeader,
    EasyDataTable,
    // Datepicker,
    Select2
  },
  beforeMount() {
    if(!this.dataFetched)
    {
      this.getJournals()
      this.$store.dispatch('getAllData');
    }
  },
  activated() {
    if(this.dataFetched)
    {
      this.getJournals()
      this.$store.dispatch('getAllData');
    }
  },
  methods: {

    mySelectEvent(eventType) {
      if (eventType == 'jrnlName') {
        this.journalRatingOptions = this.tableData.filter(journal => journal.jrnlName == this.journal.jrnlName).map(({ rating }) => rating);
        this.flashOption = this.tableData.filter(journal => journal.jrnlName == this.journal.jrnlName).map(({ flash }) => flash);
        this.yearOptionSelect = this.tableData.filter(journal => journal.jrnlName == this.journal.jrnlName).map(({ yearOfInception }) => yearOfInception);

      } else if (eventType == 'rating') {
        this.journalNameOptions = this.tableData.filter(journal => journal.rating == this.journal.rating).map(({ jrnlName }) => jrnlName);
        this.flashOption = this.tableData.filter(journal => journal.rating == this.journal.rating).map(({ flash }) => flash);
        this.yearOptionSelect = this.tableData.filter(journal => journal.rating == this.journal.rating).map(({ yearOfInception }) => yearOfInception);

      } else if (eventType == 'flash') {
        this.journalNameOptions = this.tableData.filter(journal => journal.flash == this.journal.flash).map(({ jrnlName }) => jrnlName);
        this.journalRatingOptions = this.tableData.filter(journal => journal.flash == this.journal.flash).map(({ rating }) => rating);
        this.yearOptionSelect = this.tableData.filter(journal => journal.flash == this.journal.flash).map(({ yearOfInception }) => yearOfInception);

      } else if (eventType == 'nonFictionOnly') {
        this.journalNameOptions = this.tableData.filter(journal => journal.nonFictionOnly == this.journal.nonFictionOnly).map(({ jrnlName }) => jrnlName);
        this.journalRatingOptions = this.tableData.filter(journal => journal.nonFictionOnly == this.journal.nonFictionOnly).map(({ rating }) => rating);
        this.flashOption = this.tableData.filter(journal => journal.nonFictionOnly == this.journal.nonFictionOnly).map(({ flash }) => flash);
        this.yearOptionSelect = this.tableData.filter(journal => journal.nonFictionOnly == this.journal.nonFictionOnly).map(({ yearOfInception }) => yearOfInception);

      } else if (eventType == 'yearOfInception') {
        this.journalNameOptions = this.tableData.filter(journal => journal.yearOfInception == this.journal.yearOfInception).map(({ jrnlName }) => jrnlName);
        this.journalRatingOptions = this.tableData.filter(journal => journal.yearOfInception == this.journal.yearOfInception).map(({ rating }) => rating);
        this.flashOption = this.tableData.filter(journal => journal.yearOfInception == this.journal.yearOfInception).map(({ flash }) => flash);
      } else {
        this.journalNameOptions = this.tableData.filter(journal => journal.jrnlName == this.journal.jrnlName).map(({ jrnlName }) => jrnlName);
        this.journalRatingOptions = this.tableData.filter(journal => journal.rating == this.journal.rating).map(({ rating }) => rating);
        this.flashOption = this.tableData.filter(journal => journal.flash == this.journal.flash).map(({ flash }) => flash);
        this.yearOptionSelect = this.tableData.filter(journal => journal.yearOfInception == this.journal.yearOfInception).map(({ yearOfInception }) => yearOfInception);

      }
    },

    getJournals() {
      this.journal.jrnlName = "";
      this.journal.rating = "";
      this.journal.flash = "";
      this.journal.nonFictionOnly = "";
      this.journal.yearOfInception = "";
      this.journal.submissionVolume = "";
      this.journal.minimumLength = "";
      this.journal.maximumLength = "";
      axios.get(this.url + 'api/getJournals').then(res => {
        this.tableItems = res.data;
        this.tableData = res.data;
        this.journalNameOptions = this.tableData.map(({ jrnlName }) => jrnlName);
        this.journalRatingOptions = this.tableData.map(({ rating }) => rating);
        this.flashOption = this.tableData.map(({ flash }) => flash);
        this.yearOptionSelect = this.tableData.map(({ yearOfInception }) => yearOfInception);
        this.dataFetched = true
      })
    },


    filterData() {
      let nonEmpty = Object.keys(this.journal).filter(key => this.journal[key]);

      const combinations = this.generateCombinations(nonEmpty);
      combinations.forEach((element) => {
        switch (element.length) {
          case 1:
            this.tableItems = this.tableData.filter(journal => {
              return journal[element] == this.journal[element]
            });
            break;
          case 2:
            this.tableItems = this.tableData.filter(journal => {
              return journal[element[0]] == this.journal[element[0]] && journal[element[1]] == this.journal[element[1]]
            });
            break;
          case 3:
            this.tableItems = this.tableData.filter(journal => {
              return journal[element[0]] == this.journal[element[0]] && journal[element[1]] == this.journal[element[1]] && journal[element[2]] == this.journal[element[2]]
            });
            break;
          case 4:
            this.tableItems = this.tableData.filter(journal => {
              return journal[element[0]] == this.journal[element[0]] && journal[element[1]] == this.journal[element[1]] && journal[element[2]] == this.journal[element[2]] && journal[element[3]] == this.journal[element[3]]
            });
            break;
          case 5:
            this.tableItems = this.tableData.filter(journal => {
              return journal[element[0]] == this.journal[element[0]] && journal[element[1]] == this.journal[element[1]] && journal[element[2]] == this.journal[element[2]] && journal[element[3]] == this.journal[element[3]] && journal[element[4]] == this.journal[element[4]]
            });
            break;
          case 6:
            this.tableItems = this.tableData.filter(journal => {
              return journal[element[0]] == this.journal[element[0]] && journal[element[1]] == this.journal[element[1]] && journal[element[2]] == this.journal[element[2]] && journal[element[3]] == this.journal[element[3]] && journal[element[4]] == this.journal[element[4]] && journal[element[5]] == this.journal[element[5]]
            });
            break;
          case 7:
            this.tableItems = this.tableData.filter(journal => {
              return journal[element[0]] == this.journal[element[0]] && journal[element[1]] == this.journal[element[1]] && journal[element[2]] == this.journal[element[2]] && journal[element[3]] == this.journal[element[3]] && journal[element[4]] == this.journal[element[4]] && journal[element[5]] == this.journal[element[5]] && journal[element[6]] == this.journal[element[6]]
            });
            break;
          case 8:
            this.tableItems = this.tableData.filter(journal => {
              return journal[element[0]] == this.journal[element[0]] && journal[element[1]] == this.journal[element[1]] && journal[element[2]] == this.journal[element[2]] && journal[element[3]] == this.journal[element[3]] && journal[element[4]] == this.journal[element[4]] && journal[element[5]] == this.journal[element[5]] && journal[element[6]] == this.journal[element[6]] && journal[element[7]] == this.journal[element[7]]
            });
            break;
        }
      })
    },
    generateCombinations(arr) {
      if (arr.length === 0) {
        return [[]];
      }

      const first = arr[0];
      const rest = arr.slice(1);

      const withoutFirst = this.generateCombinations(rest);
      const withFirst = withoutFirst.map(combination => [first, ...combination]);

      return [...withoutFirst, ...withFirst];
    },
    deleteJournal(id) {
      let data = new FormData()
      data.append('id', id)
      axios.post(this.url + 'api/deleteJournal', data).then(() => {
        this.getJournals();
        this.$store.state.isDeclined = true
        this.$store.state.notificationData = 'Journal Deleted Successfully'
        setTimeout(()=>{
          this.$store.state.isDeclined = false
          this.$store.state.notificationData = null
        }, 2500)
      })
    },
    sortByRating()
    {
      if(this.rating==1)
      {
        this.tableItems = this.tableItems.sort((a, b) => (a.rating.slice(0, 1) < b.rating.slice(0, 1)) ? 1 : -1)
                return this.tableItems
      }
      else
      {
        this.tableItems = this.tableItems.sort((a, b) => (a.rating.slice(0, 1) > b.rating.slice(0, 1)) ? 1 : -1)
                return this.tableItems
      }
    },
    sortByVolume()
    {
      if(this.volume==1)
      {
        this.tableItems = this.tableItems.sort((a, b) => (parseInt(a.volume) < parseInt(b.volume)) ? 1 : -1)
                return this.tableItems
      }
      else
      {
        this.tableItems = this.tableItems.sort((a, b) => (parseInt(a.volume) > parseInt(b.volume)) ? 1 : -1)
                return this.tableItems
      }
    },
    encode(id) {
      return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
    }
  },
  computed: {
    escapedSearchValue() {
      return this.searchValueTable.replace(/[\\^$*+?.()|[\]{}]/g, '\\$&');
    }
  },
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