<template>
  <layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader title="Update Status" />
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
                    <label class="form-label mb-0">Open</label>
                    <select class="form-select" @change="sortByOpenSubmission" v-model="fictionAndNonfiction">
                      <option disabled selected="selected" value="null">Open</option>
                      <option value="0">Fiction Submissions</option>
                      <option value="1" selected>Nonfiction Submissions</option>
                      <option value="2"> Fiction & Nonfiction Submissions</option>
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
                  <!-- <div class="col-sm-auto mt-2 mt-sm-0 text-end text-md-start">
                    <button @click="isshow = !isshow" class="btn btn-theme">Advance Search</button>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="thead bg-light">
              <tr>
                <th>Journal Name</th>
                <th>Defunct</th>
                <!-- <th>Year</th>
                <th>Acceptance %</th>
                <th>Volume</th>
                <th>Style</th>
                <th>Online Submission</th> -->
                <th>Fiction Submissions</th>
                <th>Nonfiction Submissions</th>
                <th>Fiction & Nonfiction Submissions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="resultQuery.length == 0">
                <td colspan="7" class="text-center">No Data Found</td>
              </tr>
              <tr v-else v-for="journal in resultQuery" :key="journal.id">
                <td class="text-theme">
                  <router-link :to="`/single_journal/${encode(journal.id)}`" class="text-theme">{{ journal.jrnlName
                  }}</router-link>
                  <div class="d-flex">
                    <span v-for="jRating in parseInt(journal.rating.slice(0, 1))" :key="jRating">
                      <i :class="`mdi mdi-star font-size-13 text-warning`"></i>
                    </span>
                    <span v-for="jRating in 5 - parseInt(journal.rating.slice(0, 1))" :key="jRating">
                      <i :class="`mdi mdi-star-outline font-size-13 text-warning`"></i>
                    </span>
                  </div>
                </td>
                <!-- <td>{{ journal.yearOfInception }}</td>
                <td>{{ journal.acceptancePercent }}</td>
                <td>{{ journal.submissionVolume }}</td>
                <td>{{ journal.permanentThemes }}</td>
                <td v-if="journal.onlineSubmission" class="text-success">Open</td>
                <td v-else class="text-danger">Close</td> -->
                <td>
                  <span v-if="journal.defunct">Yes</span>
                  <span v-else>No</span>
                </td>
                <td>
                  <input type="checkbox" name="fiction-check" true-value="1" false-value="0"
                    v-model="journal.fictionSubmission" @change="selectCheckbox(journal.id, $event, 3)" />
                </td>
                <td>
                  <input type="checkbox" name="fiction-check" true-value="1" false-value="0"
                    v-model="journal.nonFictionSubmission" @change="selectCheckbox(journal.id, $event, 2)" />
                </td>
                <td>
                  <input type="checkbox" :id="journal.id"
                    :checked="journal.fictionSubmission && journal.nonFictionSubmission" name="fiction-check"
                    true-value="1" v-model="journal.checkSubmission" false-value="0"
                    @change="selectCheckbox(journal.id, $event, 1)" />
                </td>
                <!-- <td v-else>
                  <input type="checkbox" :id="journal.id" name="fiction-check"    true-value="1"  v-model="journal.checkSubmission"
                  false-value="0" @change="selectCheckbox(journal.id, $event)" />


                </td> -->
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-3 text-end">
          <button type="button" class="btn btn-theme" @click="updateStatus">
            Update
          </button>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import axios from "axios";
import CryptoJS from 'crypto-js';

export default {
  page: {
    title: "Add More Journal",
    meta: [
      {
        name: "description",
        content: "description",
      },
    ],
  },

  data() {
    return {
      title: "",
      url: process.env.VUE_APP_URL,
      searchValueTable: "",
      isshow: false,
      journalRatingMain: "",
      volumeMain: "",
      AcceptPreMain: "",
      AcceptPreOptions: ["3-5%"],
      journalRatingOptions: ["1 Star", "2 Stars", "3 Stars", "4 Stars", "5 Stars",],
      volumeOptions: ["1", "2", "3", "4", "5"],
      itemsSelected: [],
      tableItems: [],
      unmutableTableItems: [],
      onlineSubmission: null,
      fictionAndNonfiction: null,
    };
  },
  components: {
    Layout,
    PageHeader
  },
  activated() {
    this.getAllJournals();
  },
  beforeMount() {
    this.getAllJournals();
  },
  computed: {
    resultQuery() {
      if (this.searchValueTable) {
        return this.tableItems.filter((item) => {
          return this.searchValueTable.toString().toLowerCase().split(" ").every((v) => item.jrnlName.toString().toLowerCase().includes(v) ||
            item.nonFictionSubmission.toString().toLowerCase().includes(v) ||
            item.fictionSubmission.toString().toLowerCase().includes(v));
        });
      } else {
        return this.tableItems;
      }
    },
  },
  methods: {
    selectCheckbox(id, event, functionId) {
      this.tableItems = this.tableItems.map((journal) => {
        if (journal.id == id) {
          if (functionId == 3) {
            return { ...journal, fictionSubmission: event.target.checked == 1 ? 1 : 0 };
          } else if (functionId == 2) {
            return { ...journal, nonFictionSubmission: event.target.checked == 1 ? 1 : 0 };
          } else {
            return { ...journal, fictionSubmission: event.target.checked == 1 ? 1 : 0, nonFictionSubmission: event.target.checked == 1 ? 1 : 0 };
          }
        } else {
          return journal;
        }
      });
    },

    getAllJournals() {
      this.journalRatingMain = "";
      this.volumeMain = "";
      this.AcceptPreMain = "";
      axios.get(this.url + "api/getJournals").then((res) => {
        this.tableItems = res.data;
        this.unmutableTableItems = res.data;
      });
    },
    updateStatus() {
      axios
        .post(this.url + "api/updateAllJournalStatus", {
          journals: this.tableItems,
        })
        .then(() => {
          this.$store.state.isAccepted = true
          this.$store.state.notificationData = 'Journals Status Updated Successfully'
          setTimeout(() => {
            this.$store.state.isAccepted = false
            this.$store.state.notificationData = null
          }, 2500)
        });
    },
    sortByOpenSubmission() {
      if (this.fictionAndNonfiction == 1) {
        this.tableItems = this.unmutableTableItems.filter((journal) => {
          return journal.nonFictionSubmission == 1
        })
      }
      else if (this.fictionAndNonfiction == 2) {
        this.tableItems = this.unmutableTableItems.filter((journal) => {
          return journal.fictionSubmission == 1 && journal.nonFictionSubmission == 1;
        })
      }
      else {
        this.tableItems = this.unmutableTableItems.filter((journal) => {
          return journal.fictionSubmission == 1
        })
      }
    },
    encode(id) {
      return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
    }
  },
};
</script>
