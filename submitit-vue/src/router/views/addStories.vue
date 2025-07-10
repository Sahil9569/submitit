<template>
  <layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader :title="title ? 'Add Story' : 'Update Story'" />
      </div>
      <div class="col-auto">
        <router-link to="/stories" class="btn btn-theme">
          <i class="mdi mdi-arrow-left font-size-14 align-middle me-2"></i>
          Back to Stories List
        </router-link>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <form enctype="multipart/form-data" v-if="showForm">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label class="form-label">Select Client</label>
              <div class="single-select2-cstm">
                <Select2 v-model="story.clientName" placeholder="Select Client Name" v-on:select="user_id = $event.id"
                  :options="clientName()" :settings="{ settingOption: value, settingOption: value }"
                  @select="mySelectEvent($event)" :class="{ 'is-invalid': submitted && v$.story.clientName.$error, }" />
                <div v-if="submitted && v$.story.clientName.$error" class="invalid-feedback">
                  <span v-if="v$.story.clientName.required.$message">{{ v$.story.clientName.required.$message
                  }}</span>
                </div>
              </div>
            </div>
            <!-- <div class="row mb-2"> -->
            <div class="col-md-6 mb-3">
              <label class="form-label">Story Title</label>
              <input type="text" class="form-control" placeholder="Story Title" v-model="story.storyTitle"
                :class="{ 'is-invalid': submitted && v$.story.storyTitle.$error, }" />
              <div v-if="submitted && v$.story.storyTitle.$error" class="invalid-feedback">
                <span v-if="v$.story.storyTitle.required.$message">{{ v$.story.storyTitle.required.$message
                }}</span>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Story Genre</label>
              <div class="single-select2-cstm">
                <Select2 v-model="story.storyGenre" placeholder="Select Story Genre" :options="storyGenOption"
                  :class="{ 'is-invalid': submitted && v$.story.storyGenre.$error, }" />
                <div v-if="submitted && v$.story.storyGenre.$error" class="invalid-feedback">
                  <span v-if="v$.story.storyGenre.required.$message">{{ v$.story.storyGenre.required.$message
                  }}</span>
                </div>
              </div>
            </div>
            <!-- </div> -->

            <div class="col-md-6 mb-3">
              <label class="form-label">Story Information</label>
              <input type="text" class="form-control" placeholder="Story Information" v-model="story.storyInfo" />
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Word Count</label>
              <input type="number" class="form-control" placeholder="Word Count" v-model="story.wordCount"
                :class="{ 'is-invalid': submitted && v$.story.wordCount.$error, }" />
              <div v-if="submitted && v$.story.wordCount.$error" class="invalid-feedback">
                <span v-if="v$.story.wordCount.required.$message">{{ v$.story.wordCount.required.$message
                }}</span>
              </div>
            </div>
            <!-- <div class="row mb-2"> -->
            <div class="col-md-6 mb-3">
              <label class="labelCheckboxCstm">
                <input type="checkbox" name="storyCheckbox" true-value="1" false-value="0"
                  v-model="story.finalVersionSaved" />
                <span class="checkmark pe-1">
                  <i class="mdi mdi-checkbox-blank-outline font-size-20 text-theme"></i>
                  <i class="mdi mdi-checkbox-marked-outline font-size-20 text-theme"></i>
                </span>
                Final Version Saved
              </label>
            </div>
            <div class="col-md-6 mb-3">
              <label class="labelCheckboxCstm">
                <input type="checkbox" name="storyCheckbox" true-value="1" false-value="0"
                  v-model="story.wordCountChecked" />
                <span class="checkmark pe-1">
                  <i class="mdi mdi-checkbox-blank-outline font-size-20 text-theme"></i>
                  <i class="mdi mdi-checkbox-marked-outline font-size-20 text-theme"></i>
                </span>
                Final Word Count Checked
              </label>
            </div>
            <!-- </div> -->
            <div class="col-md-6 mb-3">
              <label class="form-label">Submission Strategy</label>
              <div class="single-select2-cstm">
                <Select2 v-model="story.submissionStrategy" placeholder="Select Submission Strategy"
                  :options="submsnStrategyOption" />
              </div>
              <!-- <input type="text" class="form-control mt-2" v-if="story.submissionStrategy.includes('Manual')"
                placeholder="Manual Type" v-model="manual" /> -->
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Strategy Notes</label>
              <input type="text" class="form-control" placeholder="Strategy Notes" v-model="story.strategyNotes"
               />
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Correspondence</label>
              <textarea type="textarea" v-model="story.correspondence" placeholder="Correspondence"
                class="form-control resize-none w-100" rows="6"></textarea>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Notes</label>
              <textarea type="textarea" v-model="story.notesFromClient" placeholder="Type Notes..."
                class="form-control resize-none w-100" rows="6"
              ></textarea>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Story Blurb</label>
              <textarea type="textarea" v-model="story.storyBlurb" placeholder="Type Story Blurb"
                class="form-control resize-none w-100" rows="6"></textarea>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Bio</label>
              <textarea type="textarea" :value="story.bio" placeholder="Bio..." readonly
                class="form-control resize-none w-100" rows="6"></textarea>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Cover Letter</label>
              <textarea type="textarea" v-model="story.coverLetter" placeholder="Type Cover Letter..."
                class="form-control resize-none w-100" rows="6"></textarea>
            </div>
            <div class="col-12 mb-4">
              <label class="form-label font-size-15">Upload File</label>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <div class="form-group">
                    <label for="my-file" class="labelUploadFile">
                      <input type="file"
                        accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                        @change="previewImage($event)" class="form-control-file" id="my-file" />
                      <div class="uploadImageFile">
                        <span><i class="mdi mdi-arrow-up-circle"></i></span>
                        Upload File
                      </div>
                    </label>
                    <span class="text-danger font-size-12 d-block text-center">doc or docx (Max Size: 15MB)</span>
                  </div>
                </div>
                <!-- <template v-if="newId.length"> -->
                <template v-for="datas in newId" :key="datas">
                  <div class="col-md-4">
                    <div class="p-2 mb-3 bg-success bg-soft">
                      <div class="row gx-2 align-items-center">
                        <div class="col">
                          <p class="mb-0 text-success font-size-14">
                            File name: {{ datas }}
                          </p>
                        </div>
                        <div class="col-auto">
                          <div class="">
                            <button @click="resetData(datas)" class="border-0 p-0 bg-transparent" type="button">
                              <i class="mdi mdi-delete font-size-20 text-danger"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
                <!-- </template> -->
                <template v-for="data in id" :key="data">
                  <div class="col-md-4">
                    <div class="p-2 mb-3 bg-success bg-soft">
                      <div class="row gx-2 align-items-center">
                        <div class="col">
                          <p class="mb-0 text-success font-size-14">
                            File name: {{ image[id.indexOf(data)].name }}
                          </p>
                          <p class="mb-0 text-success font-size-14">
                            Size: {{ image[id.indexOf(data)].size / 1024 }}KB
                          </p>
                        </div>
                        <div class="col-auto">
                          <div class="">
                            <button @click="reset(data)" class="border-0 p-0 bg-transparent" type="button">
                              <i class="mdi mdi-delete font-size-20 text-danger"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </div>
            <template v-if="((showData ? true : false) && !customPackage.includes(superPackage))">
              <div v-if="parseInt(storyLimit)!=0">
                <h4 class="font-size-14">
                  Do You want to Proceed with your old Package ?
                </h4>
                <div class="row">
                  <div class="col-md-6">
                    <label for="genreLitery" class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                      <input type="radio" name="LiteryFiction" v-model="story.useAvailablePackage"
                        class="inputRadioCstm d-none" id="genreLitery" :value="true" />
                      <span class="radioCircleIcon">
                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                        <i class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                      </span>
                      <h4 class="font-size-12 mb-0 ms-2">Yes</h4>
                    </label>
                  </div>

                  <div class="col-md-6">
                    <label for="genreNonLitery" class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                      <input type="radio" name="LiteryFiction" v-model="story.useAvailablePackage"
                        class="inputRadioCstm d-none" id="genreNonLitery" :value="false" />
                      <span class="radioCircleIcon">
                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                        <i class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                      </span>
                      <h4 class="font-size-12 mb-0 ms-2">No</h4>
                    </label>
                  </div>
                </div>
              </div>
            </template>
            <div class="col-md-12" v-if="((showData ? true : false) && !customPackage.includes(superPackage))">
              <template v-if="story.useAvailablePackage == false">
                <div class="innerGroupFeilds p-2 p-md-3 mb-4">
                  <h2 class="main-title-head">Services</h2>
                  <ServiceSub :isSignUp="true" @submissionPackage="setSubmissionPackage" @fictionPackage="setFictionPackage"
                    @editingPackage="setEditingPackage" />
                  <div class="mt-3">
                    <label class="form-label"> Customize a Service:</label>
                    <textarea type="textarea" class="form-control resize-none" rows="4"
                      v-model="story.customizeService"></textarea>
                  </div>
                </div>
              </template>
            </div>
            <div class="col-12" v-if="showData ? true : false">
              <button class="btn btn-theme" type="button" @click.prevent="submitStory">
                Submit
              </button>
            </div>
            <div class="col-12" v-else>
              <button class="btn btn-theme" type="button" @click.prevent="updateStory">
                Update
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import Select2 from "vue3-select2-component";
import ServiceSub from "./sub-components/service";
import axios from "axios";
import _ from "lodash";
import CryptoJS from "crypto-js";
import useVuelidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";


export default {
  page: {
    title: "Add Stories",
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
    const modalJournalAdd = false;
    return {
      storyJournalOption: ["Algorithm1", "Algorithm2"],
      dataFetched: false,
      storyLimit: 0,
      message: "",
      message1: "",
      message2: "",
      message3: "",
      message4: "",
      message5: "",
      message6: "",
      message7: "",
      message8: "",
      disabled: false,
      disabled1: false,
      disabled2: false,
      disabled3: false,
      disabled4: false,
      disabled5: false,
      disabled6: false,
      disabled7: false,
      disabled8: false,
      modalJournalAdd,
      id: [],
      newId: [],
      url: process.env.VUE_APP_URL,
      title: true,
      submitted: false,
      storyGenOption: ["fiction", "nonfiction"],
      submsnStrategyMain: "",
      submsnStrategyOption: ["top", "mid-range", "accessible"],
      clients: [],
      story: {
        clientName: "",
        storyTitle: "",
        storyInfo: "",
        storyGenre: "",
        submissionStrategy: "",
        strategyNotes: [],
        wordCount: "",
        finalVersionSaved: false,
        wordCountChecked: false,
        correspondence: null,
        notesFromClient: "",
        storyBlurb: "",
        coverLetter: "",
        files: [],
        useAvailablePackage: false,
        submissionPackageId: null,
        fictionPackageId: null,
        editingPackageId: null,
        customizeService: "",
        user_id: null,
      },
      clientLimitId: null,
      // manual: null,
      preview: [],
      image: [],
      newImage: [],
      i: 0,
      edit_user_id: "",
      showData: true,
      showForm: true,
      journals: [],
      doNotSubmitList: [],
      unmutableJournals: [],
      superPackage: null,
      customPackage: ["price_1N90ymSGmK7KROFRZP7BnS5g", "price_1N90zySGmK7KROFRfWPL7fVn", "price_1N910nSGmK7KROFRFEsmDeW0"]
    };
  },
  validations: {
    story: {
      clientName: {
        required: helpers.withMessage("Client name is required", required)
      },
      storyTitle: {
        required: helpers.withMessage("Story title is required", required)
      },
      storyGenre: {
        required: helpers.withMessage("Story Genre is required", required)
      },
      wordCount: {
        required: helpers.withMessage("Word Count is required", required)
      },
    }
  },
  props: ["email"],
  components: {
    Layout,
    PageHeader,
    ServiceSub,
    Select2,
  },
  created() {
    this.getClients();
    this.editStory();
    this.getJournals();
  },
  activated() {
    this.getClients();
    this.editStory();
    this.getJournals();
  },
  computed: {
    lastItem() {
      return this.clients.slice(-1)[0];
    },
  },
  watch: {
    $route() {
      this.resetFields();
    },
  },
  methods: {
    getDoNotSubmitJournals(id) {
      this.journals = []
      axios.post(this.url + 'api/getClientDoNotSubmitJournals', { id: id }).then(res => {
        this.doNotSubmitList = res.data;
        this.journals = this.unmutableJournals.filter((element) => {
          return !this.doNotSubmitList.map((item) => item.id).includes(element.id);
        });
      })
    },
    getJournals() {
      axios.get(this.url + 'api/getJournals').then(res => {
        this.journals = res.data
        this.unmutableJournals = res.data
      })
    },
    getJournalsName() {
      return this.journals.map(({ id, jrnlName }) => ({
        id: id,
        text: jrnlName
      }));

    },
    getClients() {
      axios.get(this.url + "api/GetClients").then((res) => {
        this.clients = res.data;
        this.user_id = this.decode(this.$route.params.id);
        if (this.decode(this.$route.params.id)) {
          this.clientLimitId = this.decode(this.$route.params.id);
          this.getClientLimit();
        }
      });
    },
    clientName() {
      if (this.edit_user_id) {
        let result = this.clients.filter(({ id }) => id == this.edit_user_id);
        this.story.clientName = this.edit_user_id;
        return result.map(({ id, firstName, lastName }) => ({
          id: id,
          text: firstName + " " + lastName,
        }));
      } else if (this.$route.params.id && this.clients.length > 0) {
        let result = this.clients.filter(({ id }) => id == this.user_id);
        this.story.clientName = this.user_id;
        return result.map(({ id, firstName, lastName }) => ({
          id: id,
          text: firstName + " " + lastName,
        }));
      } else {
        return this.clients.map(({ id, firstName, lastName }) => ({
          id: id,
          text: firstName + " " + lastName,
        }));
      }
    },
    submitStory() {
      this.submitted = true;
      this.v$.$touch();
      if (this.v$.$invalid) {
        return;
      } 
      else if((!this.customPackage.includes(this.superPackage)) && parseInt(this.storyLimit)==0 && this.story.submissionPackageId == null && this.story.fictionPackageId == null && this.story.editingPackageId == null)
      {
        alert('Sorry you have to first select the package')
        return
      }
      else {
        let result = this.clients.filter(({ id }) => id == this.user_id);
        this.story.clientName = result.map(
          ({ firstName, lastName }) => firstName + " " + lastName
        );
        this.story.user_id = this.user_id;
        // this.story.submissionStrategy =
          // this.story.submissionStrategy === "Manual"
          //   ? this.manual :
            //  this.story.submissionStrategy;
        let data = new FormData();
        _.each(this.story, (value, key) => {
          data.append(key, value);
        });
        for (let i = 0; i < this.image.length; i++) {
          data.append("files[]", this.image[i]);
        }
        data.append("paymentLink", this.encode(result.map(({ email }) => email)));
        axios.post(this.url + "api/storeStory", data).then(() => {
          this.$store.state.isAccepted = true
          this.$store.state.notificationData = 'Story Created Successfully'
          this.$router.push({ path: "/stories" });
          setTimeout(() => {
            this.$store.state.isAccepted = false
            this.$store.state.notificationData = null
          }, 2500)
        });
      }
    },
    previewImage(event) {
      this.id.push(this.i++);
      var input = event.target;
      if (input.files) {
        var reader = new FileReader();
        reader.onload = (e) => {
          this.preview.push(e.target.result);
        };
        this.image.push(input.files[0]);
        reader.readAsDataURL(input.files[0]);
      }
    },
    mySelectEvent(event) {
      this.clientLimitId = event.id;
      this.story.user_id = event.id;
      this.getClientLimit();
      this.getDoNotSubmitJournals(event.id);
    },
    async getClientLimit() {
      try {
        const response = await axios.post(this.url + "api/getClientLimit", {
          id: this.clientLimitId,
        });
        this.storyLimit = response.data.user.storyLimit;
        this.superPackage = response.data.superPackage
      } catch (error) {
        console.log(error);
      }
    },
    resetFields() {
      this.title = true;
      this.submitted = false;
      this.preview = [];
      this.image = [];
      this.newImage = [];
      this.i = 0;
      this.story = {
        clientName: "",
        storyTitle: "",
        storyInfo: "",
        storyGenre: "",
        submissionStrategy: "",
        wordCount: "",
        finalVersionSaved: false,
        wordCountChecked: false,
        correspondence: null,
        notesFromClient: "",
        storyBlurb: "",
        coverLetter: "",
        files: [],
        useAvailablePackage: false,
        submissionPackageId: null,
        fictionPackageId: null,
        editingPackageId: null,
        customizeService: "",
        user_id: null,
      };
      this.id = [];
      this.newId = [];
      this.edit_user_id = null;
      this.showData = true;
      this.storyLimit = 0;



    },
    editStory() {
      if (this.$route.name === "editStory" && this.$route.params.id) {
        this.showForm = false;
        let id = this.decode(this.$route.params.id);
        this.showData = false;
        this.title = false;
        axios.post(this.url + "api/editStory", { id: id }).then((res) => {
          // let submissionStrategy = res.data.submissionStrategy;
          this.edit_user_id = res.data.user_id;
          this.story = res.data;
          this.story.clientName = res.data.user_id;
          // this.manual = submissionStrategy;
          //  this.story.submissionStrategy === "top" ||   this.story.submissionStrategy === "mid-range" ||     this.story.submissionStrategy === "accessible" || this.story.submissionStrategy ;     // ? this.story.submissionStrategy  // : "Manual";
          if(res.data.strategyNotes!=null)
          {
            this.story.strategyNotes = res.data.strategyNotes.split(',')
          }
          if (res.data.files) {
            this.newImage = res.data.files.split(",");
            this.newId = res.data.files.split(",");
          }
          if (res.data.files) {
            this.i = res.data.files.split(",").length;
          }
          this.getDoNotSubmitJournals(this.story.user_id)
          this.showForm = true;
        });
      }
    },
    updateStory() {
      this.submitted = true;
      this.v$.$touch();
      if (this.v$.$invalid) {
        return;
      } else {
        let result = this.clients.filter(({ id }) => id == this.edit_user_id);
        this.story.clientName = result.map(
          ({ firstName, lastName }) => firstName + " " + lastName
        );
        this.story.user_id = this.edit_user_id;
        // this.story.submissionStrategy =
          // this.story.submissionStrategy === "Manual"
          //   ? this.manual :
            // this.story.submissionStrategy;
        let data = new FormData();
        data.append("id", this.decode(this.$route.params.id));
        _.each(this.story, (value, key) => {
          data.append(key, value);
        });
        for (let i = 0; i < this.image.length; i++) {
          data.append("files[]", this.image[i]);
        }

        for (let j = 0; j < this.newImage.length; j++) {
          data.append("images[]", this.newImage[j]);
        }
        axios.post(this.url + "api/updateStory", data).then(() => {
          this.$store.state.isAccepted = true
          this.$store.state.notificationData = 'Story Updated Successfully'
          this.$router.go(-1);
          setTimeout(() => {
            this.$store.state.isAccepted = false
            this.$store.state.notificationData = null
          }, 2500)
        });
      }
    },
    reset(data) {
      const index = this.id.indexOf(data);
      this.id.splice(index, 1);
      this.preview.splice(index, 1);
      this.image.splice(index, 1);
    },
    resetData(datas) {
      const newIndex = this.newId.indexOf(datas);
      this.newId.splice(newIndex, 1);
      this.newImage.splice(newIndex, 1);
    },
    encode(id) {
      return encodeURIComponent(
        CryptoJS.AES.encrypt(String(id), "Secret Passphrase")
      );
    },
    decode(id) {
      return decodeURIComponent(
        CryptoJS.AES.decrypt(String(id), "Secret Passphrase").toString(
          CryptoJS.enc.Utf8
        )
      );
    },
    setSubmissionPackage(id) {
      this.story.submissionPackageId = id;
    },
    setFictionPackage(id) {
      this.story.fictionPackageId = id;
    },
    setEditingPackage(id) {
      this.story.editingPackageId = id;
    },
  },
};
</script>