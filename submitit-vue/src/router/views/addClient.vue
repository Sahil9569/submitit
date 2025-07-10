<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import Datepicker from 'vuejs3-datepicker';
import Select2 from "vue3-select2-component";
import axios from "axios";
import CryptoJS from "crypto-js";
import useVuelidate from "@vuelidate/core";
import Vue3TagsInput from 'vue3-tags-input';

import {
  required,
  integer,
  helpers
} from "@vuelidate/validators";

export default {
  page: {
    title: "Add Client",
    meta: [
      {
        name: "description",
        content: "description",
      },
    ],
  },

  props: {
    type: {
      type: String,
      default: "text",
    },
    size: {
      type: String,
      default: "20",
    },
    characters: {
      type: String,
      default: "a-z,A-Z,0-9,#",
    },
    placeholder: {
      type: String,
      default: "Password",
    },
    auto: [String, Boolean],
    value: {
      type: String,
      default: "",
    },
  },

  data() {
    return {
      storyJournalList: "",
      tags: [],
      storyJournalOption: ["Algorithm1", "Algorithm2"],
      v$: useVuelidate(),
      url: process.env.VUE_APP_URL,
      form: {
        firstName: "",
        lastName: "",
        penName: "",
        doNotSubmitList: [],
        city: "",
        street: "",
        date: new Date(),
        state: "",
        zipCode: "",
        email: "",
        phone: "",
        referral: "",
        sharedEmail: "",
        notes: '',
        bio:''
      },
      isShowData: false,
      title: true,
      submitted: false,
      editing: true,
      showForm: true,
      isResetErrors: false,
      journals: []
    };
  },

  components: {
    Layout,
    PageHeader,
    Datepicker,
    Select2,
    Vue3TagsInput
  },
  validations: {
    form: {
      firstName: {
        required: helpers.withMessage("First Name is required", required),
      },
      lastName: {
        required: helpers.withMessage("Last Name is required", required),
      },
      zipCode:{
        integer: helpers.withMessage("Zip Code Should Be Numeric", integer)
      }
    },
  },
  mounted() {
    if (this.auto == "true" || this.auto == 1) {
      this.generate();
    }
  },
  activated() {
    this.editClient();
    this.getJournals();
  },
  created() {
    this.editClient();
    this.getJournals();
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
        if (this.tags.length > 0) {
          this.form.doNotSubmitList = this.form.doNotSubmitList.concat(this.tags)
        }
        if (this.form.doNotSubmitList.some((v) => ["other"].includes(v))) {
          const arrayToRemove = ["other"];
          for (let i = 0; i < arrayToRemove.length; i++) {
            let index = this.form.doNotSubmitList.indexOf(
              arrayToRemove[i]
            );
            if (index !== -1) {
              this.form.doNotSubmitList.splice(index, 1);
            }
          }
        }
        axios
          .post(this.url + "api/registration", this.form)
          .then((res) => {
            if (res.data.error) {
              this.isResetErrors = true;
              this.errors = res.data.message;
            } 
            else {
              this.isResetErrors = false;
              this.$store.state.isAccepted = true
              this.success = res.data.message;
              this.$store.state.notificationData = 'Client Added Successfully'
              this.$store.dispatch('addClient', res.data.user)
              if (type == 0) {
                this.$router.push({ path: "/client" });
              } else {
                this.$router.push("/addStory/" + this.encode(res.data.user.id));
              }
              setTimeout(() => {
                this.$store.state.isAccepted = false
                this.$store.state.notificationData = null
              }, 2500)
            }
          })
          .catch((error) => {
            this.isResetErrors = true;
            this.errors = error.response.data.errors.email;
          });
      }
    },
    handleChangeTag(tags) {
      this.tags = tags;
      this.tags.sort((a, b) => (a - b) || (a > b ? 1 : -1));
    },
    async getJournals() {
      if(this.$store.state.normalStore.journals.length)
      {
        this.journals = this.$store.state.normalStore.journals
      }
      else
      {
        try {
          const response = await axios.get(this.url + 'api/getJournals');
          this.journals = response.data;
        }catch (error) {
          console.error("Error fetching journals:", error);
        }
      }
      this.journals.sort((a, b) => (a.jrnlName > b.jrnlName) ? 1 : -1);
      let journal = { id: 'other', jrnlName: 'Other with fill-in option' };
      this.journals.push(journal);
    },
    dateSelected(date) {
      var getYear = date.toLocaleString("default", { year: "numeric" });
      var getMonth = date.toLocaleString("default", { month: "2-digit" });
      var getDay = date.toLocaleString("default", { day: "2-digit" });
      this.form.date = getYear + "-" + getMonth + "-" + getDay;
    },
    getJournalsName() {
      return this.journals.map(({ id, jrnlName }) => ({
        id: id,
        text: jrnlName
      }));

    },
  async editClient() {
    let id = this.decode(this.$route.params.id);
    let client = null;
    if (id) {
      this.showForm = false;
      this.editing = false;
      this.title = false;
      
      if (this.$store.state.normalStore.clients.length) {
        client = this.$store.state.normalStore.clients.find(item => item.id == id);
        client.doNotSubmitList = client.doNotSubmitList && typeof client.doNotSubmitList === 'string'
        ? client.doNotSubmitList.split(',')
        : [];
        this.form = client;
        this.journals = this.$store.state.normalStore.journals;
      } else {
        try {
          let response = await axios.post(this.url + "api/editClient", { id: id });
          client = response.data.client;
          this.form = response.data.client;
          this.journals = response.data.journals;
        } catch (error) {
          console.error("Error fetching client:", error);
        }
      }
      
      this.journals.sort((a, b) => (a.jrnlName > b.jrnlName) ? 1 : -1);

      if (client && client.doNotSubmitList && client.doNotSubmitList[0] != '') {
        let journals = client.doNotSubmitList;
        this.form.doNotSubmitList = journals.filter((element) => {
          return this.journals.map((item) => String(item.id)).includes(element);
        });
        this.tags = journals.filter((element) => {
          return !this.journals.map((item) => String(item.id)).includes(element);
        });
        if (this.tags.length > 0) {
          this.form.doNotSubmitList.push('other');
        }
        this.tags.sort((a, b) => (a - b) || (a > b ? 1 : -1));
      } else {
        this.form.doNotSubmitList = [];
        this.tags = [];
      }

      this.showForm = true;
    }
  },

    updateClient() {
      this.submitted = true;
      this.v$.$touch();
      if (this.v$.$invalid) {
        return;
      } else {
        let form = new FormData();
        form.append("id", this.decode(this.$route.params.id));
        if (!this.form.doNotSubmitList.some((v) => ["other"].includes(v))) {
          this.tags = [];
        }
        this.form.doNotSubmitList = this.form.doNotSubmitList.concat(this.tags);
        if (this.form.doNotSubmitList.some((v) => ["other"].includes(v))) {
          const arrayToRemove = ["other"];
          for (let i = 0; i < arrayToRemove.length; i++) {
            let index = this.form.doNotSubmitList.indexOf(
              arrayToRemove[i]
            );
            if (index !== -1) {
              this.form.doNotSubmitList.splice(index, 1);
            }
          }
        }
        axios
          .post(this.url + "api/updateClient", this.form)
          .then((res) => {
            this.$store.state.isAccepted = true
            this.$store.state.notificationData = 'Client Updated Successfully'
            this.$store.dispatch('updateClient', {newData: res.data.user, id:this.decode(this.$route.params.id)})
            if (res.data.error) {
              this.isResetErrors = true;
              this.errors = res.data.message;
            } else {
              this.$router.go(-1);
            }
            setTimeout(() => {
              this.$store.state.isAccepted = false
              this.$store.state.notificationData = null
            }, 2500)
          })
          .catch((error) => {
            this.isResetErrors = true;
            this.errors = error.response.data.errors.email;
          });
      }
    },
    generate(type = 'password') {
      let CharacterSet =
        "abcdefghijklmnopqrstuvwxyz![]{}()ABCDFASDFFG1234582475%&*$#^<>~@|";
      let password = "";
      for (let i = 0; i < this.size; i++) {
        password += CharacterSet.charAt(
          Math.floor(Math.random() * CharacterSet.length)
        );
      }
      this.form[type] = password;
    },
    formatPhoneNumber() {
      this.form.phone = this.form.phone.replace(/\D/g, "");
      if (this.form.phone.length > 3) {
        this.form.phone = this.form.phone.replace(
          /(\d{3})(\d{0,3})(\d{0,4})/,
          "$1-$2-$3"
        );
      } else {
        this.form.phone = this.form.phone.replace(
          /(\d{0,3})(\d{0,3})/,
          "$1-$2"
        );
      }
      this.form.phone = this.form.phone.slice(0, 12);
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
    resetFields() {
      this.submitted = false;
      this.isResetErrors = false;
      this.form = {
        firstName: "",
        lastName: "",
        penName: "",
        doNotSubmitList: "",
        city: "",
        street: "",
        date: new Date(),
        state: "",
        zipCode: "",
        email: "",
        phone: "",
        referral: "",
        sharedEmail: "",
      };
      this.title = true;
    },
  },
};
</script>
<template>
  <layout>
    <div class="row mb-3 align-items-center">
      <div class="col">
        <PageHeader :title="title ? 'Add Client' : 'Update Client'" />
      </div>
      <div class="col-auto">
        <router-link to="/client" class="btn btn-theme">
          <i class="mdi mdi-arrow-left font-size-14 align-middle me-2"></i>
          Back to Client List
        </router-link>
      </div>
    </div>
    <b-alert v-model="isResetErrors" variant="danger" class="mt-3" dismissible>
      <span style="margin-left:42%;">{{ errors[0] }}</span>
    </b-alert>
    <b-alert v-if="success" variant="success" class="mt-3" dismissible>{{
      success
    }}</b-alert>
    <div class="card">
      <div class="card-body">
        <form class="needs-validation mb-3" v-if="showForm">
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <label class="form-label">Date</label>
                <div class="input-group w-100 inputFieldDate">
                  <!-- <datepicker v-model="form.dateselect" :first-day-of-week="1" lang="en" class="form-control"></datepicker> -->
                  <datepicker v-model="form.date" name="dateSelect" @input="dateSelected" format="MMMM dd yyyy"
                    input-class="customClass" placeholder="MM-DD-YYYY"></datepicker>
                  <span class="input-group-text bg-theme"><i class="mdi mdi-calendar text-white"></i></span>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="mb-3">
                <label for="first-name-input">First Name</label>
                <input id="first-name-input" v-model="form.firstName" type="text" class="form-control"
                  placeholder="First Name" :class="{
                    'is-invalid': submitted && v$.form.firstName.$error,
                  }" />
                <div v-if="submitted && v$.form.firstName.$error" class="invalid-feedback">
                  <span v-if="v$.form.firstName.required.$message">
                    {{ v$.form.firstName.required.$message }}
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="mb-3">
                <label for="last-name-input">Last Name</label>
                <input id="last-name-input" v-model="form.lastName" type="text" class="form-control"
                  placeholder="Last Name" :class="{
                    'is-invalid': submitted && v$.form.lastName.$error,
                  }" />
                <div v-if="submitted && v$.form.lastName.$error" class="invalid-feedback">
                  <span v-if="v$.form.lastName.required.$message">
                    {{ v$.form.lastName.required.$message }}
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="mb-3">
                <label for="pen-name-input">Pen Name</label>
                <input id="pen-name-input" v-model="form.penName" type="text" class="form-control"
                  placeholder="Pen Name" />
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="mb-3">
                <label for="street-address-input">Street</label>
                <input id="street-address-input" type="text" v-model="form.street" class="form-control"
                  placeholder="Street"  />
                
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="mb-3">
                <label for="city-address-input">City</label>
                <input id="city-address-input" v-model="form.city" type="text" class="form-control" placeholder="City"
                   />
              </div>
            </div>
            <div class="col-md-6 col-lg-2">
              <div class="mb-3">
                <label for="state-address-input">State</label>
                <input id="state-address-input" v-model="form.state" type="text" class="form-control" placeholder="State"
                   />
              </div>
            </div>
            <div class="col-md-6 col-lg-2">
              <div class="mb-3">
                <label for="zip-address-input">Zip</label>
                <input id="zip-address-input" v-model="form.zipCode" type="text" class="form-control" placeholder="Zip"
                :class="{
                  'is-invalid': submitted && v$.form.zipCode.$error,
                }" />
                <div v-if="submitted && v$.form.zipCode.$error" class="invalid-feedback">
                  <span v-if="v$.form.zipCode.integer.$message">
                    {{ v$.form.zipCode.integer.$message }}
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <label for="phone-input">Phone</label>
              <div>
                <input v-model="form.phone" id="phone-input" type="text" name="number" class="form-control" @input="formatPhoneNumber"
                  placeholder="xxx-xxx-xxxx" />
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="mb-3">
                <label for="email-input">Email</label>
                <input v-model="form.email" id="email-input" type="email" name="email" class="form-control" placeholder="Enter a Valid Email" @input="isResetErrors = false" />
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="mb-3">
                <label class="form-label">Referral</label>
                <input id="referral-input" v-model="form.referral" type="text" class="form-control"
                  placeholder="Referral" />
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="mb-3">
                <label for="sharedEmail-input">Shared Email
                  <sup>(created by Submit<span class="text-theme">it</span>)</sup></label>
                <input v-model="form.sharedEmail" id="sharedEmail-input" type="email" name="sharedEmail"
                  class="form-control" placeholder="Enter a Shared Email" />
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="mb-3">
                <label for="shared-email-password-input">Shared Email Password</label>
                <div class="input-group">
                  <input id="shared-email-password-input" v-model="form.sharedEmailPassword" type="text" name="password"
                    class="form-control" placeholder="Password" />
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-theme py-1" @click="generate('sharedEmailPassword')">
                      <span class="mdi mdi-refresh font-size-18"></span>
                    </button>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4">
              <div class="mb-3">
                <label class="form-label">Do Not Submit List</label>
                <div class="multipleSelectCstm">
                  <Select2 v-model="form.doNotSubmitList" placeholder="Select Algorithm" :options="getJournalsName()"
                    :settings="{ multiple: true }" />
                </div>
                <div class="multipleSelectCstm" v-if="form.doNotSubmitList.includes('other')">
                  <vue3-tags-input :tags="tags" add-tag-on-keys="[13]" placeholder="Enter Journal Name"
                    @on-tags-changed="handleChangeTag" />
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Notes</label>
              <textarea type="textarea" v-model="form.notes" placeholder="Type Notes..."
                class="form-control resize-none w-100" rows="6"
              ></textarea>
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Bio</label>
              <textarea type="textarea" v-model="form.bio" placeholder="Bio..."
                class="form-control resize-none w-100" rows="6"
              ></textarea>
            </div>
          </div>
          <div class="row gx-2" v-if="!this.$route.params.id">
            <div class="col-auto">
              <button class="btn btn-theme mt-3" type="submit" @click.prevent="formSubmit(0)">
                Save
              </button>
            </div>
            <div class="col-auto">
              <button class="btn btn-theme mt-3" type="submit" @click.prevent="formSubmit(1)">
                Save with Story
              </button>
            </div>
          </div>
          <div class="col-auto" v-else>
            <button class="btn btn-theme mt-3" type="submit" @click.prevent="updateClient">
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </layout>
</template>
<style lang="css">
.v3ti .v3ti-tag {
  background: #3aafa9;
}
</style>