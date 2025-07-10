<template>
    <div class="pt-5">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <div class="text-center mb-5 contentSignUp">
              <h1 class="mainTitleSize">Thank you for signing up with Submit<span class="text-theme">it</span>!</h1>
              <p>After completing the form, you'll hear from us <a href="mailto:info@submititnow.com"
                  class="text-theme">(info@submititnow.com)</a> within 24 hours (if you don't see an email, please check
                your spam folder).</p>
            </div>
            <form-wizard @on-complete="onComplete" step-size="xs" color="#2b7a78" enctype="multipart/form-data">
              <tab-content title="Personal Information" :before-change="validateFormOne">
                <div class="main-from-group my-4">
                  <div class="row">
                    <div class="col-lg-6 col-xl-3 mb-3">
                      <label for="first-name-input">First Name *</label>
                      <input id="first-name-input" type="text" class="form-control" placeholder="First Name"
                        v-model="form.client.firstName" :class="{ 'is-invalid': v$.form.client.firstName.$error }">
                      <div v-for="(client, index) in v$.form.client.firstName.$errors" :key="index"
                        class="invalid-feedback">
                        <span v-if="client.$message">{{ client.$message }}</span>
                      </div>
                    </div>
                    <div class="col-lg-6 col-xl-3 mb-3">
                      <label for="last-name-input">Last Name *</label>
                      <input id="last-name-input" type="text" class="form-control" placeholder="Last Name"
                        v-model="form.client.lastName" :class="{ 'is-invalid': v$.form.client.lastName.$error, }">
                      <div v-for="(client, index) in v$.form.client.lastName.$errors" :key="index" class="invalid-feedback">
                        <span v-if="client.$message">{{ client.$message }}</span>
                      </div>
                    </div>
                    <div class="col-lg-6 col-xl-3 mb-3">
                      <label for="email-name-input">Email *</label>
                      <input id="email-name-input" type="email" class="form-control" @input="emailError = false"
                        placeholder="adcd@example.com" v-model="form.client.email"
                        :class="{ 'is-invalid': this.emailError || v$.form.client.email.$error, }">
                      <div v-for="(client, index) in v$.form.client.email.$errors" :key="index" class="invalid-feedback">
                        <span v-if="client.$message">{{ client.$message }}</span>
                      </div>
  
                      <div v-if="this.emailError">
                        <span class="text-danger" v-if="this.emailError">This Email already exists</span>
                      </div>
                    </div>
                    <div class="col-lg-6 col-xl-3 mb-3">
                      <label for="pen-name-input">Pen Name (if different from above)</label>
                      <input id="pen-name-input" type="text" class="form-control" placeholder="Pen Name"
                        v-model="form.client.penName">
                    </div>
                    <div class="col-lg-6 mb-3">
                      <label for="street-name-input">Street Address *</label>
                      <input id="street-name-input" type="textarea" class="form-control" placeholder="Street Address"
                        v-model="form.client.street" :class="{ 'is-invalid': v$.form.client.street.$error, }" />
                      <div v-for="(client, index) in v$.form.client.street.$errors" :key="index" class="invalid-feedback">
                        <span v-if="client.$message">{{ client.$message }}</span>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="row">
                        <div class="col-lg-6 mb-3">
                          <label for="city-name-input">City *</label>
                          <input id="city-name-input" type="text" class="form-control" placeholder="City"
                            v-model="form.client.city" :class="{ 'is-invalid': v$.form.client.city.$error, }">
                          <div v-for="(client, index) in v$.form.client.city.$errors" :key="index" class="invalid-feedback">
                            <span v-if="client.$message">{{ client.$message }}</span>
                          </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                          <label for="rsp-name-input">Region/State/Province *</label>
                          <input id="rsp-name-input" type="text" class="form-control" placeholder="Region/State/Province"
                            v-model="form.client.state" :class="{ 'is-invalid': v$.form.client.state.$error, }">
                          <div v-for="(client, index) in v$.form.client.state.$errors" :key="index"
                            class="invalid-feedback">
                            <span v-if="client.$message">{{ client.$message }}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                      <label for="pz-name-input">Postal / Zip Code *</label>
                      <input id="pz-name-input" type="number" class="form-control" placeholder="Postal / Zip Code"
                        v-model="form.client.zipCode" :class="{ 'is-invalid': v$.form.client.zipCode.$error, }">
                      <div v-for="(client, index) in v$.form.client.zipCode.$errors" :key="index" class="invalid-feedback">
                        <span v-if="client.$message">{{ client.$message }}</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                      <label for="rsp-name-input">Country *</label>
                      <div class="single-select2-cstm">
                        <Select2 v-model="form.client.country" placeholder="Select Country" :options="countryOption"
                          :class="{ 'is-invalid': v$.form.client.country.$error, }">
                        </Select2>
                        <div v-for="(client, index) in v$.form.client.country.$errors" :key="index"
                          class="invalid-feedback">
                          <span v-if="client.$message">{{ client.$message }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6  mb-3">
                      <label for="ph-name-input">Phone *</label>
                      <input id="ph-name-input" type="text" class="form-control" @input="formatPhoneNumber"
                        placeholder="xxx-xxx-xxxx" v-model="form.client.phone"
                        :class="{ 'is-invalid': v$.form.client.phone.$error, }">
                      <div v-for="(client, index) in v$.form.client.phone.$errors" :key="index" class="invalid-feedback">
                        <span v-if="client.$message">{{ client.$message }}</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                      <label for="about-name-input">How Did You Hear about Us? *</label>
                      <input id="about-name-input" type="text" class="form-control" placeholder="About Us"
                        v-model="form.client.hearAboutUs" :class="{ 'is-invalid': v$.form.client.hearAboutUs.$error, }">
                      <div v-for="(client, index) in v$.form.client.hearAboutUs.$errors" :key="index"
                        class="invalid-feedback">
                        <span v-if="client.$message">{{ client.$message }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </tab-content>
              <tab-content title="Story/Essay Information" :before-change="validateFormTwo">
                <div class="main-from-group my-4">
                  <div class="row">
                    <div class="col-lg-5 col-xl-4">
                      <div class="d-block mb-3">
                        <label for="essay-title-input">Story or Essay Title <sup>("TBD" if undecided)</sup> *</label>
                        <input id="essay-title-input" type="text" class="form-control" placeholder="Story or Essay Title"
                          v-model="form.story.storyTitle" :class="{ 'is-invalid': v2$.form.story.storyTitle.$error }">
                        <div v-for="(story, index) in v2$.form.story.storyTitle.$errors" :key="index"
                          class="invalid-feedback">
                          <span v-if="story.$message"> {{ story.$message }} </span>
                        </div>
                      </div>
                      <div class="d-block mb-3">
                        <label for="word-count-input">Approx. Word Count</label>
                        <input id="word-count-input" type="number" class="form-control" placeholder="Word Count"
                          v-model="form.story.wordCount" :class="{ 'is-invalid': v2$.form.story.wordCount.$error }">
                          <div v-for="(story, index) in v2$.form.story.wordCount.$errors" :key="index"
                            class="help text-danger font-size-11">
                            <span v-if="story.$message"> {{ story.$message }} </span>
                          </div>
                      </div>
                    </div>
                    <div class="col-lg-7 col-xl-8">
                      <div class="">
                        <h4 class="font-size-14">Genre *</h4>
                        <div class="row">
                          <div class="col-md-6">
                            <label for="genreLitery" class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                              <input type="radio" name="LiteryFiction" class="inputRadioCstm d-none" id="genreLitery"
                                v-model="form.story.storyGenre" value="fiction"
                                :class="{ 'is-invalid': v2$.form.story.storyGenre.$error }">
                              <span class="radioCircleIcon">
                                <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                                <i class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                              </span>
                              <h4 class="font-size-12 mb-0 ms-2">Literary Fiction <sup>(short stories and flash
                                  fiction)</sup></h4>
                            </label>
  
                          </div>
  
                          <div class="col-md-6">
                            <label for="genreNonLitery" class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                              <input type="radio" name="LiteryFiction" class="inputRadioCstm d-none" id="genreNonLitery"
                                v-model="form.story.storyGenre" value="non-fiction"
                                :class="{ 'is-invalid': v2$.form.story.storyGenre.$error }">
                              <span class="radioCircleIcon">
                                <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                                <i class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                              </span>
                              <h4 class="font-size-12 mb-0 ms-2">Creative Nonfiction <sup>(essays)</sup></h4>
                            </label>
                          </div>
                          <div v-for="(story, index) in v2$.form.story.storyGenre.$errors" :key="index"
                            class="help text-danger font-size-11">
                            <span v-if="story.$message"> {{ story.$message }} </span>
                          </div>
                        </div>
                        <div class="d-block mt-2">
                          <label class="mb-0 font-size-12" for="additional-note font-weight-400">Additional notes, including
                            journals you have already submitted this piece to.</label>
                          <textarea id="additional-note" type="textarea" class="form-control resize-none"
                            v-model="form.story.notesFromClient" :class="{ 'is-invalid': v2$.form.story.notesFromClient.$error }"></textarea>
                            <div v-for="(story, index) in v2$.form.story.notesFromClient.$errors" :key="index"
                            class="help text-danger font-size-11">
                            <span v-if="story.$message"> {{ story.$message }} </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-md-4">
                      <div class="form-group">
                        <template v-if="!id.includes('1')">
                          <label for="my-file" class="labelUploadFile">
                            <input type="file" accept="image/*" @change="previewImage($event, '1')"
                              class="form-control-file" id="my-file">
                            <div class="uploadImageFile">
                              <span><i class="mdi mdi-arrow-up-circle"></i></span>
                              Upload File
                            </div>
                          </label>
                          <span class="text-danger font-size-12 d-block text-center">doc or docx (Max
                            Size: 15MB)</span>
                        </template>
                        <template v-if="id.includes('1')">
                          <div class="p-2 mb-3 bg-success bg-soft">
                            <div class="row gx-2 align-items-center">
                              <div class="col">
                                <p class="mb-0 text-success font-size-14">file name: {{
                                  image[id.indexOf('1')].name
                                }}</p>
                                <p class="mb-0 text-success font-size-14">size: {{
                                  image[id.indexOf('1')].size / 1024
                                }}KB</p>
                              </div>
                              <div class="col-auto">
                                <div class="">
                                  <button @click="reset('1')" class="border-0 p-0 bg-transparent" type="button">
                                    <i class="mdi mdi-delete font-size-20 text-danger"></i>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </template>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <template v-if="!id.includes('2')">
                          <label for="file-upload-input" class="labelUploadFile">
                            <input type="file" accept="image/*" @change="previewImage($event, '2')"
                              class="form-control-file" id="file-upload-input">
                            <div class="uploadImageFile">
                              <span><i class="mdi mdi-arrow-up-circle"></i></span>
                              Upload File
                            </div>
                          </label>
                          <span class="text-danger font-size-12 d-block text-center">Flash fiction
                            only (2 of 3)</span>
                        </template>
                        <template v-if="id.includes('2')">
                          <div class="p-2 mb-3 bg-success bg-soft">
                            <div class="row gx-2 align-items-center">
                              <div class="col">
                                <p class="mb-0 text-success font-size-14">file name: {{
                                  image[id.indexOf('2')].name
                                }}</p>
                                <p class="mb-0 text-success font-size-14">size: {{
                                  image[id.indexOf('2')].size / 1024
                                }}KB</p>
                              </div>
                              <div class="col-auto">
                                <div class="">
                                  <button @click="reset('2')" class="border-0 p-0 bg-transparent" type="button">
                                    <i class="mdi mdi-delete font-size-20 text-danger"></i>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </template>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <template v-if="!id.includes('3')">
                          <label for="file-upload-input1" class="labelUploadFile">
                            <input type="file" accept="image/*" @change="previewImage($event, '3')"
                              class="form-control-file" id="file-upload-input1">
                            <div class="uploadImageFile">
                              <span><i class="mdi mdi-arrow-up-circle"></i></span>
                              Upload File
                            </div>
                          </label>
                          <span class="text-danger font-size-12 d-block text-center">Flash fiction
                            only (3 of 3)</span>
                        </template>
                        <template v-if="id.includes('3')">
                          <div class="p-2 mb-3 bg-success bg-soft">
                            <div class="row gx-2 align-items-center">
                              <div class="col">
                                <p class="mb-0 text-success font-size-14">file name: {{
                                  image[id.indexOf('3')].name
                                }}</p>
                                <p class="mb-0 text-success font-size-14">size: {{
                                  image[id.indexOf('3')].size / 1024
                                }}KB</p>
                              </div>
                              <div class="col-auto">
                                <div class="">
                                  <button @click="reset('3')" class="border-0 p-0 bg-transparent" type="button">
                                    <i class="mdi mdi-delete font-size-20 text-danger"></i>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </template>
                      </div>
                    </div>
                  </div>
                </div>
              </tab-content>
              <tab-content title="Services" :before-change="validateFormThree">
                <div class="my-4">
                  <div class="contentServiceCstm">
                    <h5>An invoice will be sent (via email). Prices do not include journal submission fees ($2/journal). An
                      additional charge of $35/1,000 words will be added to stories over 3,500 words. All services: 100%
                      money-back guarantee (within 30 days). For more details, see our <a
                        href="https://www.submititnow.com/submission-packages" class="text-theme">Services page</a></h5>
                  </div>
                  <ServiceSub :isSignUp="false" @submissionPackage="setSubmissionPackage" @fictionPackage="setFictionPackage"
                    @editingPackage="setEditingPackage" @superPackage="setSuperPackage"/>
                  <div class="mt-4 text-end">
                    <label class="labelCheckboxCstm">
                      <input type="checkbox" name="agreeAccept" v-model="agreeAccept" class="form-control"
                        :class="{ 'is-invalid': v3$.agreeAccept.$error, }">
                      <span class="checkmark pe-1">
                        <i class="mdi mdi-checkbox-blank-outline font-size-20 text-danger"
                          v-if="this.v3$.agreeAccept.$error"></i>
                        <i class="mdi mdi-checkbox-blank-outline font-size-20 text-theme" v-else></i>
                        <i class="mdi mdi-checkbox-marked-outline font-size-20 text-theme"></i>
                      </span> I agree to Submitit's <a href="https://www.submititnow.com/terms-of-service"
                        class="text-theme ps-1"> Terms of Service.</a>
                    </label>
                  </div>
                </div>
              </tab-content>
            </form-wizard>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { FormWizard, TabContent } from "vue3-form-wizard";
  import 'vue3-form-wizard/dist/style.css';
  import Select2 from 'vue3-select2-component';
  import ServiceSub from './sub-components/service';
  import axios from "axios";
  import _ from "lodash";
  import useVuelidate from "@vuelidate/core";
  import {
    required, email,
    helpers, maxLength, minLength
  } from "@vuelidate/validators";
  import CryptoJS from 'crypto-js';
  
  export default {
    setup() {
      return { v$: useVuelidate(), v2$: useVuelidate(), v3$: useVuelidate() }
    },
    data() {
      return {
        url: process.env.VUE_APP_URL,
        submitted: false,
        emailError: false,
        countryOption: ['Afghanistan', 'Aland Islands', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bonaire', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'British Virgin Islands', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo (Brazzaville)', 'Congo (Kinshasa)', ' Cook Islands', 'Costa Rica', 'Croatia', 'Curaçao', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guernsey', 'Guinea', 'Guinea-Bissau', 'uyana', 'Haiti', 'Heard Island and McDonald Islands', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Ireland', 'Isle of Man', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jersey', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestinian Territories', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Republic of Côte d’Ivoire', 'Republic of Kosovo', 'Reunion', 'Romania', 'Russia', 'Rwanda', 'Saint Barthélemy', 'Saint Helena', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Martin', 'Saint Pierre and Miquelon', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Sint Maarten', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia and the South Sandwich Islands', 'South Korea', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard and Jan Mayen', 'Swaziland', 'Sweden', 'Switzerland', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tokelau', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu', 'U.S. Virgin Islands', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican', 'Venezuela', 'Vietnam', 'Wallis and Futuna', 'Western Sahara', 'Yemen', 'Zambia', 'Zimbabwe'],
        id: [],
        preview: [],
        image: [],
        i: 0,
        form: {
          client: {
            firstName: '',
            lastName: '',
            email: '',
            penName: '',
            city: '',
            state: '',
            zipCode: '',
            country: '',
            street: '',
            phone: '',
            hearAboutUs: '',
            roleAs: 'super'
          },
          story: {
            clientName: '',
            storyTitle: '',
            storyGenre: '',
            wordCount: '',
            notesFromClient: '',
            files: [],
            submissionPackageId: null,
            fictionPackageId: null,
            editingPackageId: null,
            superPackageId: null
          },
        },
        story: {
          submissionPackageId: null,
          fictionPackageId: null,
          editingPackageId: null,
          superPackageId: null,
        },
        agreeAccept: false,
      }
    },
    validations: {
      form: {
        client: {
          firstName: {
            required: helpers.withMessage("First Name is required", required),
          },
          lastName: {
            required: helpers.withMessage("Last Name is required", required),
          },
          email: {
            required: helpers.withMessage("Email is required", required),
            email: helpers.withMessage("Enter a valid Email", email),
          },
          city: {
            required: helpers.withMessage("City is required", required),
          },
          state: {
            required: helpers.withMessage("State is required", required),
          },
          zipCode: {
            required: helpers.withMessage("Zip Code is required", required),
          },
          country: {
            required: helpers.withMessage("Country is required", required),
          },
          street: {
            required: helpers.withMessage("Street is required", required),
          },
          phone: {
            required: helpers.withMessage("Phone is required", required),
            minLength: helpers.withMessage('Enter Valid Phone Number',minLength(12)),
            maxLength: maxLength(12)
          },
          hearAboutUs: {
            required: helpers.withMessage("About us is required", required),
          },
        },
        story: {
          storyTitle: {
            required: helpers.withMessage("Story Title is required", required),
          },
          storyGenre: {
            required: helpers.withMessage("Story Genre is required", required),
          },
          wordCount: {
            required: helpers.withMessage("Word Count is required", required)
          },
          notesFromClient: {
            required: helpers.withMessage("Notes  is required", required)
          },
        }
  
      },
      agreeAccept: {
        checked: value => value === true,
      }
    },
    components: {
      FormWizard,
      TabContent,
      Select2,
      ServiceSub
    },
  
  
    methods: {
      onComplete() {
        this.submitted = true;
        this.v$.$touch();
        if (this.v$.$invalid) {
          return false;
        } else {
          const form = new FormData();
          _.each(this.form.client, (value, key) => {
            form.append(key, value);
          });
          _.each(this.form.story, (value, key) => {
            form.append(key, value);
          });
          for (let i = 0; i < this.image.length; i++) {
            form.append('files[]', this.image[i]);
          }
          form.append('clientName', this.form.client.firstName + ' ' + this.form.client.lastName);
          let packages = []
          packages.push(this.story.submissionPackageId)
          packages.push(this.story.fictionPackageId)
          packages.push(this.story.editingPackageId)
          this.$store.state.selectedPackages = packages.filter((el) => { return el != null; });
          axios.post(this.url + 'api/signUp', form).then((res) => {
            this.$store.state.isAccepted = true
            this.$store.state.notificationData = 'Successfully SignUp! Please complete your Payment'
            this.$router.push('/payment/' + res.data.paymentLink)
            setTimeout(()=>{
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = null
                }, 2500)
          }).catch(error => {
            console.log(error);
          })
        }
  
      },
      formatPhoneNumber() {
        this.form.client.phone = this.form.client.phone.replace(/\D/g, "")
        if (this.form.client.phone.length > 3) {
          this.form.client.phone = this.form.client.phone.replace(/(\d{3})(\d{0,3})(\d{0,4})/, "$1-$2-$3");
        } else {
          this.form.client.phone = this.form.client.phone.replace(/(\d{0,3})(\d{0,3})/, "$1-$2");
        }
        this.form.client.phone = this.form.client.phone.slice(0, 12);
      },
      validateFormOne() {
        this.v$.$touch();
        if (this.v$.form.client.$invalid) {
          return false;
        } else {
          return axios.post(this.url + 'api/checkEmail', { "email": this.form.client.email }).then((res) => {
            if (res.data) {
              this.emailError = true
              return false
            }
            else {
              this.emailError = false
              return true
            }
          })
  
        }
      },
      validateFormTwo() {
        this.v2$.$touch();
        if (this.v2$.form.story.$invalid) {
          return false;
        } else {
          return true;
        }
      },
      validateFormThree() {
        this.v3$.$touch();
        if (this.v3$.agreeAccept.$invalid) {
          return false;
        } else {
          return true;
        }
      },
      previewImage(event, id) {
        this.id.push(id)
        var input = event.target;
        if (input.files) {
          var reader = new FileReader();
          reader.onload = (e) => {
            this.preview.push(e.target.result);
          }
          this.image.push(input.files[0]);
          reader.readAsDataURL(input.files[0]);
        }
      },
  
      encode(id) {
        return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
      },
      reset(id) {
        const index = this.id.indexOf(id);
        this.id.splice(index, 1);
        this.preview.splice(index, 1);
        this.image.splice(index, 1);
      },
      setSubmissionPackage(id) {
        this.form.story.submissionPackageId = id
      },
      setFictionPackage(id) {
        this.form.story.fictionPackageId = id
      },
      setEditingPackage(id) {
        this.form.story.editingPackageId = id
      },
      setSuperPackage(id) {
        this.form.story.superPackageId = id
      }
    },
  };
  </script>  