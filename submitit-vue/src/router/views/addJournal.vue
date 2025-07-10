<template>
  <layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader :title="title ? 'Add Journal' : 'Update Journal'" />
      </div>
      <div class="col-auto">
        <router-link to="/journals" class="btn btn-theme">
          <i class="mdi mdi-arrow-left font-size-14 align-middle me-2"></i>
          Back to Journals List
        </router-link>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <form class="needs-validation mb-3" v-if="showForm">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="journal-name-input">Journal Name *</label>
                <input id="journal-name-input" v-model="form.jrnlName" type="text" class="form-control"
                  placeholder="Journal Name" :class="{
                    'is-invalid': submitted && v$.form.jrnlName.$error,
                  }" />
                <div v-if="submitted && v$.form.jrnlName.$error" class="invalid-feedback">
                  <span v-if="v$.form.jrnlName.required.$message">
                    {{ v$.form.jrnlName.required.$message }}
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="last-name-input">Journal Rating *</label>
                <div class="single-select2-cstm">
                  <Select2 v-model="form.rating" placeholder="Select Rating" :options="ratingOption" :class="{
                    'is-invalid': submitted && v$.form.rating.$error,
                  }"></Select2>
                  <div v-if="submitted && v$.form.rating.$error" class="invalid-feedback">
                    <span v-if="v$.form.rating.required.$message">
                      {{ v$.form.rating.required.$message }}
                    </span>
                  </div>
                </div>
                <!-- <input
                  id="last-name-input"
                  v-model="form.ratingModal"
                  type="text"
                  class="form-control"
                  placeholder="Last name"
                  
                /> -->
              </div>
            </div>
            <div class="col-md-12 mb-3">
              <div class="innerGroupFeilds my-2 p-2 p-md-3">
                <div class="row gx-3">
                  <div class="col-md-4 col-lg-3 mb-3">
                    <label class="form-label mb-1">Date Last Checked</label>
                    <div class="single-select2-cstm">
                      <datepicker v-model="form.date" name="dateLastChecked" format="MMMM dd yyyy" @input="dateSelected"
                        input-class="customClass" placeholder="MM-DD-YYYY"></datepicker>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-3 mb-3">
                    <h4 class="font-size-13 mb-2">
                      Open for Fiction Submissions?
                    </h4>
                    <label for="fiction-check" class="labelCheckboxCstm d-flex font-size-13">
                      <input type="checkbox" class="d-none" id="fiction-check" name="fiction-check" true-value="1"
                        false-value="0" v-model="form.fictionSubmission" />
                      <div class="checkmark me-2">
                        <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                        <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                      </div>
                      Yes
                    </label>
                  </div>
                  <div class="col-md-4 col-lg-3 mb-3">
                    <h4 class="font-size-13 mb-2">
                      Open for Nonfiction Submissions?
                    </h4>
                    <label for="nonfiction-check" class="labelCheckboxCstm d-flex font-size-13">
                      <input type="checkbox" true-value="1" false-value="0" v-model="form.nonFictionSubmission"
                        class="d-none" id="nonfiction-check" name="nonfiction-check" />
                      <div class="checkmark me-2">
                        <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                        <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                      </div>
                      Yes
                    </label>
                  </div>
                  <div class="col-md-4 col-lg-3 mb-3">
                    <h4 class="font-size-13 mb-2">Fiction Only?</h4>
                    <label for="fictiononly-check" class="labelCheckboxCstm d-flex font-size-13">
                      <input type="checkbox" true-value="1" false-value="0" v-model="form.fictionOnly" class="d-none"
                        id="fictiononly-check" name="fictiononly-check" />
                      <div class="checkmark me-2">
                        <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                        <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                      </div>
                      Yes
                    </label>
                  </div>
                  <div class="col-md-4 col-lg-3 mb-3">
                    <h4 class="font-size-13 mb-2">Nonfiction Only?</h4>
                    <label for="nonfictiononly-check" class="labelCheckboxCstm d-flex font-size-13">
                      <input type="checkbox" true-value="1" false-value="0" v-model="form.nonFictionOnly" class="d-none"
                        id="nonfictiononly-check" name="nonfictiononly-check" />
                      <div class="checkmark me-2">
                        <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                        <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                      </div>
                      Yes
                    </label>
                  </div>
                  <div class="col-md-4 col-lg-3 mb-3">
                    <h4 class="font-size-13 mb-2">Online Submissions?</h4>
                    <label for="onlineSub-check" class="labelCheckboxCstm d-flex font-size-13">
                      <input type="checkbox" true-value="0" false-value="1" v-model="form.onlineSubmission" class="d-none"
                        id="onlineSub-check" name="onlineSub-check" />
                      <div class="checkmark me-2">
                        <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                        <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                      </div>
                      No
                    </label>
                  </div>
                  <div class="col-md-4 col-lg-3 mb-3">
                    <h4 class="font-size-13 mb-2">Simultaneous Submissions?</h4>
                    <label for="simultaneous-check" class="labelCheckboxCstm d-flex font-size-13">
                      <input type="checkbox" true-value="0" false-value="1" v-model="form.simultaneousSubmission"
                        class="d-none" id="simultaneous-check" name="simultaneous-check" />
                      <div class="checkmark me-2">
                        <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                        <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                      </div>
                      No
                    </label>
                  </div>
                  <div class="col-md-4 col-lg-3 mb-3">
                    <h4 class="font-size-13 mb-2">Defunct?</h4>
                    <label for="defunct-check" class="labelCheckboxCstm d-flex font-size-13">
                      <input type="checkbox" true-value="1" false-value="0" v-model="form.defunct" class="d-none"
                        id="defunct-check" name="defunct-check" />
                      <div class="checkmark me-2">
                        <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                        <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                      </div>
                      Yes
                    </label>
                  </div>
                  <div class="col-md-4 col-lg-3 mb-3">
                    <h4 class="font-size-13 mb-2">Not Included</h4>
                    <label for="included-check" class="labelCheckboxCstm d-flex font-size-13">
                      <input type="checkbox" true-value="1" false-value="0" v-model="form.notIncluded" class="d-none"
                        id="included-check" name="included-check" />
                      <div class="checkmark me-2">
                        <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                        <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                      </div>
                      Yes
                    </label>
                  </div>
                  <div class="col-md-4 col-lg-3 mb-3">
                    <h4 class="font-size-13 mb-2">Possible Themes?</h4>
                    <label for="possible-check" class="labelCheckboxCstm d-flex font-size-13">
                      <input type="checkbox" class="d-none" id="possible-check" name="possible-check" true-value="1"
                        false-value="0" v-model="form.possibleThemes" />
                      <div class="checkmark me-2">
                        <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                        <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                      </div>
                      Yes
                    </label>
                  </div>
                  <div class="col-md-4 col-lg-3 mb-3">
                    <h4 class="font-size-13 mb-2">Flash</h4>
                    <div class="single-select2-cstm">
                      <Select2 v-model="form.flash" placeholder="0" :options="flashOption"
                        :class="{ 'is-invalid': submitted && v$.form.flash.$error, }"></Select2>
                      <div v-if="submitted && v$.form.flash.$error" class="invalid-feedback">
                        <span v-if="v$.form.flash.required.$message">
                          {{ v$.form.flash.required.$message }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Year of Inception *</label>
              <div class="single-select2-cstm">
                <Select2 v-model="form.yearOfInception" placeholder="Year of Inception" :options="YearInceptionOption"
                  :class="{
                    'is-invalid': submitted && v$.form.yearOfInception.$error,
                  }">
                </Select2>
                <div v-if="submitted && v$.form.yearOfInception.$error" class="invalid-feedback">
                  <span v-if="v$.form.yearOfInception.required.$message">
                    {{ v$.form.yearOfInception.required.$message }}
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Actual Acceptance Percent *</label>
              <div class="input-group">
                <span class="input-group-text">%</span>
                <input type="text" class="form-control" placeholder="5" v-model="form.acceptance" :class="{
                  'is-invalid': submitted && v$.form.acceptance.$error,
                }" />
                <div v-if="submitted && v$.form.acceptance.$error" class="invalid-feedback">
                  <span v-if="v$.form.acceptance.required.$message">
                    {{ v$.form.acceptance.required.$message }}
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Submission Volume *</label>
              <input type="text" class="form-control" placeholder="Submission Volume" v-model="form.volume" :class="{
                'is-invalid': submitted && v$.form.volume.$error,
              }" />
              <div v-if="submitted && v$.form.volume.$error" class="invalid-feedback">
                <span v-if="v$.form.volume.required.$message">
                  {{ v$.form.volume.required.$message }}
                </span>
              </div>
            </div>
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Minimum Length *</label>
                  <input type="number" class="form-control" placeholder="Minimum Length" v-model="form.minimumLength"
                    :class="{
                      'is-invalid': submitted && v$.form.minimumLength.$error,
                    }" />
                  <div v-if="submitted && v$.form.minimumLength.$error" class="invalid-feedback">
                    <span v-if="v$.form.minimumLength.required.$message">
                      {{ v$.form.minimumLength.required.$message }}
                    </span>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Maximum Length (0-25000)*</label>
                  <input type="number" class="form-control" placeholder="Maximum Length" v-model="form.maximumLength"
                    :class="{
                      'is-invalid': submitted && v$.form.maximumLength.$error,
                    }" />
                  <div v-if="submitted && v$.form.maximumLength.$error" class="invalid-feedback">
                    <span v-if="v$.form.maximumLength.required.$message">
                      {{ v$.form.maximumLength.required.$message }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Style & Permanent Themes *</label>
              <input type="text" class="form-control" placeholder="e.g., “Experimental” or “Topical/Margins"
                v-model="form.permanentThemes" :class="{
                  'is-invalid': submitted && v$.form.permanentThemes.$error,
                }" />
              <div v-if="submitted && v$.form.permanentThemes.$error" class="invalid-feedback">
                <span v-if="v$.form.permanentThemes.required.$message">
                  {{ v$.form.permanentThemes.required.$message }}
                </span>
              </div>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Notes About Journal</label>
              <textarea type="textarea" class="form-control resize-none" rows="4" placeholder="Notes About Journal..."
                v-model="form.notesAboutJournal"
                :class="{ 'is-invalid': submitted && v$.form.notesAboutJournal.$error, }"></textarea>
              <div v-if="submitted && v$.form.notesAboutJournal.$error" class="invalid-feedback">
                <span v-if="v$.form.notesAboutJournal.required.$message">
                  {{ v$.form.notesAboutJournal.required.$message }}
                </span>
              </div>
            </div>
            <div class="col-12 mb-3">
              <label>Theme Date</label>
              <div class="row gx-1">
                <div class="col-md-6 mb-2 mb-md-0">
                  <div class="input-group w-100 inputFieldDate">
                    <datepicker v-model="theme.date" name="dateLastChecked" width="160px" format="MMMM dd yyyy"
                      input-class="customClass" placeholder="MM-DD-YYYY" @input="themeDateSelected"></datepicker>
                    <span class="input-group-text bg-theme"><i class="mdi mdi-calendar text-white"></i></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Theme" v-model="theme.text" />
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <h2 class="text-dark-cstm font-size-18 mt-4 mb-3">
                Submission Details
              </h2>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label mb-1">Submission Platform</label>
                  <div class="single-select2-cstm">
                    <Select2 v-model="form.submissionPlatform" placeholder="Select Submission Platform"
                      :options="submissionPoption"
                      :class="{ 'is-invalid': submitted && v$.form.submissionPlatform.$error, }" />
                    <div v-if="submitted && v$.form.submissionPlatform.$error" class="invalid-feedback">
                      <span v-if="v$.form.submissionPlatform.required.$message">
                        {{ v$.form.submissionPlatform.required.$message }}
                      </span>
                    </div>
                  </div>
                  <div class="d-block overflowScrollInputs mt-2" v-if="form.submissionPlatform.includes(
                    'other [see below]'
                  )
                    ">
                    <label class="form-label font-size-11 mb-0">Other</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Type..."
                      v-model="submissionPlatformExtra" />
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label mb-1">Submission Guidelines</label>
                  <div class="multipleSelectCstm">
                    <Select2 v-model="form.submissionGuidelines" placeholder="Select Submission Guidelines"
                      :options="submissionGuidelineoption" :settings="{ multiple: true }"
                      />
                  </div>
                  <div class="d-block overflowScrollInputs mt-2" v-if="form.submissionGuidelines && form.submissionGuidelines.some((v) =>
                    [
                      'File Name [see below]',
                      'Email Subject [see below]',
                      'Check for permanent theme [see below]',
                      'other [see below]',
                    ].includes(v)
                  )
                    ">
                    <div class="mt-0">
                      <textarea type="text" class="form-control form-control-md" row="5" placeholder="Type..."
                        v-model="submissionGuidelineExtra" />
                    </div>
                  </div>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label mb-1">Submission Cost <sup>(Dollars)</sup>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="text" class="form-control" placeholder="0" v-model="form.submissionCost" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row gx-2" v-if="!this.$route.params.id">
            <div class="col-auto">
              <button class="btn btn-theme mt-3" type="submit" @click.prevent="formSubmit(1)">
                Add Story
              </button>
            </div>
            <div class="col-auto">
              <button class="btn btn-theme mt-3" type="submit" @click.prevent="formSubmit(0)">
                Submit
              </button>
            </div>
          </div>
          <div class="col-auto" v-else>
            <button class="btn btn-theme mt-3" type="submit" @click.prevent="updateJournal">
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import useVuelidate from "@vuelidate/core";
// import datetime from "vuejs-datetimepicker-translatable";
import Select2 from "vue3-select2-component";
import axios from "axios";
import _ from "lodash";
import Datepicker from 'vuejs3-datepicker';
import CryptoJS from "crypto-js";

import { required, helpers, minValue, maxValue } from "@vuelidate/validators";

export default {
  page: {
    title: "Add Journal",
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
      url: process.env.VUE_APP_URL,
      pickeddd: new Date(),
      id: [],
      newId: [],
      image: [],
      preview: [],
      newImage: [],
      i: 0,
      title: true,
      showForm: true,
      // v$: useVuelidate(),
      form: {
        jrnlName: "",
        rating: "",
        date: null,
        fictionSubmission: false,
        nonFictionSubmission: false,
        fictionOnly: false,
        nonFictionOnly: false,
        onlineSubmission: false,
        simultaneousSubmission: false,
        defunct: false,
        notIncluded: false,
        possibleThemes: false,
        flash: [],
        yearOfInception: "",
        acceptance: "",
        volume: "",
        minimumLength: "",
        maximumLength: "",
        permanentThemes: "",
        notesAboutJournal: "",
        theme: "",
        submissionPlatform: "",
        submissionGuidelines: [],
        submissionCost: "",
      },

      theme: {
        date: '',
        text: "",
      },
      guideLine: {
        fileName: "",
        email: "",
        permanentTheme: "",
        other: "",
      },
      submissionPlatformExtra: "",
      submissionGuidelineExtra: "",
      ratingOption: ["1 Stars", "2 Stars", "3 Stars", "4 Stars", "5 Stars"],
      lastDateOption: [
        "01",
        "02",
        "03",
        "04",
        "05",
        "06",
        "07",
        "08",
        "09",
        "10",
        "11",
        "12",
        "13",
        "14",
      ],
      flashOption: ["0", "1", "2", "3", "4", "5"],
      YearInceptionOption: [
        "unknown",
        "2017",
        "2018",
        "2019",
        "2020",
        "2021",
        "2022",
        "2023",
      ],
      isShowData: false,
      submitted: false,
      password: this.value,
      submissionPoption: [
        "Submittable",
        "email (paste)",
        "email (attach)",
        "own manager",
        "online form",
        "Google forms",
        "Duosuma",
        "Oleada",
        "Moksha",
        "other [see below]",
      ],
      submissionGmain: "",
      submissionGuidelineoption: [
        "Blind",
        "No page numbers",
        "Single-Space",
        "1.5-space",
        "File Name [see below]",
        "Email Subject [see below]",
        "PDF only",
        "Block Paragraphs",
        "Indented Paragraphs",
        "Check for possible themes",
        "Check for permanent theme [see below]",
        "other [see below]",
      ],
      submissionCmain: "",
      submissionCoption: ["$120", "$200", "$230", "$260", "$280"],
    };
  },

  components: {
    Layout,
    PageHeader,
    Select2,
    Datepicker,
  },
  validations: {
    form: {
      jrnlName: {
        required: helpers.withMessage("Journal Name is required", required),
      },
      rating: {
        required: helpers.withMessage("Rating is required", required),
      },
      yearOfInception: {
        required: helpers.withMessage("Year Of Inception is required", required),
      },
      acceptance: {
        required: helpers.withMessage("Acceptance Percet is required", required),
      },
      volume: {
        required: helpers.withMessage("Submission Volume is required", required),
      },
      minimumLength: {
        required: helpers.withMessage("Enter Valid Minimum Length", required),
        minValue: helpers.withMessage('Enter Valid Minimum Length', minValue(0)),
        maxValue: helpers.withMessage('Enter Valid Minimum Length', maxValue(25000))
      },
      maximumLength: {
        required: helpers.withMessage("Enter Valid Maximum Length", required),
        minValue: helpers.withMessage('Enter Valid Maximum Length', minValue(0)),
        maxValue: helpers.withMessage('Enter Valid Maximum Length', maxValue(25000))
      },
      permanentThemes: {
        required: helpers.withMessage("Permanent Themes is required", required),
      },
      flash: {
        required: helpers.withMessage("Flash is required", required),
      },
      notesAboutJournal: {
        required: helpers.withMessage("Notes about journal is required", required),
      },
      submissionPlatform: {
        required: helpers.withMessage("Submission Platform is required", required),
      }
    },
  },
  activated() {
    this.editJournal();
  },
  created() {
    this.YearInceptionOption = Array.from(
      { length: (new Date().getFullYear() - 1800) / 1 + 1 },
      (value, index) => 1800 + index * 1
    );
    const newFirstElement = 'unknown';
    this.YearInceptionOption = [newFirstElement].concat(this.YearInceptionOption)
    this.editJournal();
  },
  watch: {
    $route() {
      this.resetFields();
    },
  },
  methods: {
    formSubmit(type) {
      this.submitted = true;
      this.v$.$touch();
      if (this.v$.$invalid) {
        return;
      } else {
        if (
          this.form.submissionPlatform.includes("other [see below]")
        ) {
          this.form.submissionPlatform = this.submissionPlatformExtra;
        }
        if (this.form.submissionGuidelines &&
          this.form.submissionGuidelines.length > 0 &&
          this.form.submissionGuidelines.some((v) =>
            [
              "File Name [see below]",
              "Email Subject [see below]",
              "Check for permanent theme [see below]",
              "other [see below]",
            ].includes(v)
          )
        ) {
          const arrayToRemove = [
            "File Name [see below]",
            "Email Subject [see below]",
            "Check for permanent theme [see below]",
            "other [see below]",
          ];
          for (let i = 0; i < arrayToRemove.length; i++) {
            let index = this.form.submissionGuidelines.indexOf(
              arrayToRemove[i]
            );
            if (index !== -1) {
              this.form.submissionGuidelines.splice(index, 1);
            }
          }
          this.form.submissionGuidelines.push(this.submissionGuidelineExtra);
        }
        if(this.theme.text && this.theme.date)
        {
          this.form.theme =
          this.theme.date +
          ":" +
          this.theme.text;
        }
        else
        {
          this.form.theme = ''
        }
        let data = new FormData();
        _.each(this.form, (value, key) => {
          data.append(key, value);
        });
        axios.post(this.url + "api/addJournal", data).then((res) => {
          this.$store.state.isAccepted = true
          this.$store.state.notificationData = 'Journal Added Successfully'
          this.$store.dispatch('addJournal', res.data)
          if (type == 0) {
            this.$router.push({ path: "/journals" });
          } else {
            this.$router.push({ path: "/addStory" });
          }
          setTimeout(() => {
            this.$store.state.isAccepted = false
            this.$store.state.notificationData = null
          }, 2500)
        });
      }
    },
    dateSelected(date) {
      var getYear = date.toLocaleString("default", { year: "numeric" });
      var getMonth = date.toLocaleString("default", { month: "2-digit" });
      var getDay = date.toLocaleString("default", { day: "2-digit" });
      this.form.date = getMonth + "-" + getDay + "-" + getYear;
    },
    themeDateSelected(date) {
      var getYear = date.toLocaleString("default", { year: "numeric" });
      var getMonth = date.toLocaleString("default", { month: "2-digit" });
      var getDay = date.toLocaleString("default", { day: "2-digit" });
      this.theme.date = getMonth + "-" + getDay + "-" + getYear ;
    },
    editJournal() {
      let id = this.decode(this.$route.params.id);
      if (id) {
        this.showForm = false;
        this.title = false;
        axios.post(this.url + "api/editJournal", { id: id }).then((res) => {
          this.form = res.data;
          if(res.data.submissionPlatform)
          {
            this.form.submissionPlatform = res.data.submissionPlatform.split(",");
          }
          if(res.data.submissionGuidelines)
          {
            this.form.submissionGuidelines = res.data.submissionGuidelines.split(",");
          }
          this.form.acceptance = (res.data.acceptance * 100).toFixed(2)
          let arr = [];
          if(this.form.submissionGuidelines)
          {
            arr = this.form.submissionGuidelines.filter(
            (i) => !this.submissionGuidelineoption.includes(i)
            );
          }
          if (arr.length > 0) {
            for (let i = 0; i < arr.length; i++) {
              let index = this.form.submissionGuidelines.indexOf(arr[i]);
              if (index !== -1) {
                this.form.submissionGuidelines.splice(index, 1);
              }
            }
            this.form.submissionGuidelines.push("other [see below]");
            this.submissionGuidelineExtra = arr.toString();
          }
          let arr1 = [];
          if(this.form.submissionPlatform)
          {
            arr1 = this.form.submissionPlatform.filter(
            ( i) => !this.submissionPoption.includes(i)
            );
          }
          if (arr1.length > 0) {
            this.form.submissionPlatform.push("other [see below]");
            this.submissionPlatformExtra = arr1.toString();
          }
          if (res.data.theme) {
            this.theme.date = res.data.theme.split(":")[0];
            this.theme.text = res.data.theme.split(":")[1];
          }
          this.showForm = true;
        });
      }
    },

    updateJournal() {
      this.submitted = true;
      this.v$.$touch();
      if (this.v$.$invalid) {
        return;
      } else {
        if (
          this.form.submissionPlatform.includes("other [see below]")
        ) {
          this.form.submissionPlatform = this.submissionPlatformExtra;
        }
        if (this.form.submissionGuidelines &&
          this.form.submissionGuidelines.length > 0 &&
          this.form.submissionGuidelines.some((v) =>
            [
              "File Name [see below]",
              "Email Subject [see below]",
              "Check for permanent theme [see below]",
              "other [see below]",
            ].includes(v)
          )
        ) {
          const arrayToRemove = [
            "File Name [see below]",
            "Email Subject [see below]",
            "Check for permanent theme [see below]",
            "other [see below]",
          ];
          for (let i = 0; i < arrayToRemove.length; i++) {
            let index = this.form.submissionGuidelines.indexOf(
              arrayToRemove[i]
            );
            if (index !== -1) {
              this.form.submissionGuidelines.splice(index, 1);
            }
          }
          this.form.submissionGuidelines.push(this.submissionGuidelineExtra);
        }
        if(this.theme.text && this.theme.date)
        {
          this.form.theme =  this.theme.date + ":" + this.theme.text;
        }
        else
        {
          this.form.theme = '';
        }
        let data = new FormData();
        data.append("id", this.decode(this.$route.params.id));
        _.each(this.form, (value, key) => {
          data.append(key, value);
        });
        axios.post(this.url + "api/updateJournal", data).then((res) => {
          this.$store.dispatch('updateJournal', { newData: res.data, id: this.decode(this.$route.params.id) })
          this.$store.state.isAccepted = true
          this.$store.state.notificationData = 'Journal Updated Successfully'
          this.$router.go(-1);
          setTimeout(() => {
            this.$store.state.isAccepted = false
            this.$store.state.notificationData = null
          }, 2500)
        });
      }
    },
    decode(id) {
      return decodeURIComponent(
        CryptoJS.AES.decrypt(String(id), "Secret Passphrase").toString(
          CryptoJS.enc.Utf8
        )
      );
    },
    resetFields() {
      this.submitted = false,
        this.form = {
          jrnlName: "",
          rating: "",
          date: null,
          fictionSubmission: false,
          nonFictionSubmission: false,
          fictionOnly: false,
          nonFictionOnly: false,
          onlineSubmission: false,
          simultaneousSubmission: false,
          defunct: false,
          notIncluded: false,
          possibleThemes: false,
          flash: [],
          yearOfInception: "",
          acceptance: "",
          volume: "",
          minimumLength: "",
          maximumLength: "",
          permanentThemes: "",
          notesAboutJournal: "",
          theme: "",
          submissionPlatform: "",
          submissionGuidelines: [],
          submissionCost: "",
        };
      // this.submitted = false
      (this.id = []),
        (this.newId = []),
        (this.image = []),
        (this.preview = []),
        (this.newImage = []),
        (this.i = 0),
        (this.submissionPlatformExtra = null);
      this.submissionGuidelineExtra = null;
      this.title = true;
      this.theme = {
        date: "",
        text: "",
      };
    },
  },
  computed: {
    days() {
      const daysInMonth = this.getDaysInMonth();
      return Array.from({ length: daysInMonth }, (_, index) => index + 1);
    },
    months() {
      return [
        'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August',
        'September', 'October', 'November', 'December',
      ];
    },
    years() {
      const currentYear = new Date().getFullYear();
      const startYear = currentYear - 10;
      const endYear = currentYear;
      return Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
    },
  },
};
</script>