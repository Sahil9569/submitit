<template>
    <layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title" />
            </div>
        </div>
        <div class="">
            <b-tabs pills vertical nav-class="p-0" nav-wrapper-class="col-md-3" content-class="text-muted col-md-9">
                <b-tab title="General" active title-item-class="mb-2 customTabLink">
                    <b-card-text>
                        <div class="card">
                            <div class="card-body">
                                <div class="">
                                    <h2 class="titleCardStyle">Profile Edit</h2>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control" placeholder="First Name"
                                                v-model="client.firstName"
                                                :class="{ 'is-invalid': submitted && v$.client.firstName.$error, }">
                                            <div v-if="submitted && v$.client.firstName.$error" class="invalid-feedback">
                                                <span v-if="v$.client.firstName.required.$message">
                                                    {{ v$.client.firstName.required.$message }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control" placeholder="Last Name"
                                                v-model="client.lastName"
                                                :class="{ 'is-invalid': submitted && v$.client.lastName.$error, }">
                                            <div v-if="submitted && v$.client.lastName.$error" class="invalid-feedback">
                                                <span v-if="v$.client.lastName.required.$message">
                                                    {{ v$.client.lastName.required.$message }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" :disabled="true" placeholder="Email"
                                                v-model="client.email"
                                                :class="{ 'is-invalid': submitted && v$.client.email.$error, }">
                                            <div v-for="(item, index) in v$.client.email.$errors" :key="index"
                                                class="invalid-feedback">
                                                <span v-if="item.$message">
                                                    {{ item.$message }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <label for="street-address">Street Address *</label>
                                        <input id="street-address" type="text" class="form-control"
                                            placeholder="Street Address" v-model="client.street"
                                            :class="{ 'is-invalid': submitted && v$.client.street.$error, }">
                                        <div v-if="submitted && v$.client.street.$error" class="invalid-feedback">
                                            <span v-if="v$.client.street.required.$message">
                                                {{ v$.client.street.required.$message }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="city-name-input">City *</label>
                                        <input id="city-name-input" type="text" class="form-control" placeholder="City"
                                            v-model="client.city"
                                            :class="{ 'is-invalid': submitted && v$.client.city.$error, }">
                                        <div v-if="submitted && v$.client.city.$error" class="invalid-feedback">
                                            <span v-if="v$.client.city.required.$message">
                                                {{ v$.client.city.required.$message }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3 mb-3">
                                        <label for="rsp-name-input">Region/State/Province *</label>
                                        <input id="rsp-name-input" type="text" class="form-control"
                                            placeholder="Region/State/Province" v-model="client.state"
                                            :class="{ 'is-invalid': submitted && v$.client.state.$error, }">
                                        <div v-if="submitted && v$.client.state.$error" class="invalid-feedback">
                                            <span v-if="v$.client.state.required.$message">
                                                {{ v$.client.state.required.$message }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <label for="pz-name-input">Postal / Zip code *</label>
                                        <input id="pz-name-input" type="number" class="form-control"
                                            placeholder="Postal / Zip code" v-model="client.zipCode"
                                            :class="{ 'is-invalid': submitted && v$.client.zipCode.$error, }">
                                        <div v-if="submitted && v$.client.zipCode.$error" class="invalid-feedback">
                                            <span v-if="v$.client.zipCode.required.$message">
                                                {{ v$.client.zipCode.required.$message }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <label for="rsp-name-input">Country *</label>
                                        <div class="single-select2-cstm">
                                            <Select2 placeholder="Select Country" :options="countryOption"
                                                v-model="client.country"
                                                :class="{ 'is-invalid': submitted && v$.client.country.$error, }"></Select2>
                                            <div v-if="submitted && v$.client.country.$error" class="invalid-feedback">
                                                <span v-if="v$.client.country.required.$message">
                                                    {{ v$.client.country.required.$message }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" placeholder="Phone Number"
                                                v-model="client.phone" @input="formatPhoneNumber">
                                        </div>
                                    </div>
                                    <div class="col-12 text-end mt-3">
                                        <button type="button" class="btn btn-theme" @click="updateClient">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-card-text>
                </b-tab>
                <!-- <b-tab title="Profile" title-item-class="mb-2 customTabLink">
                <b-card-text>thrytdhth</b-card-text>
              </b-tab> -->
            </b-tabs>
        </div>
    </layout>
</template>


<script>
import Layout from "../../router/layouts/main";
import PageHeader from "@/components/page-header";
import Select2 from 'vue3-select2-component';
import axios from "axios";
import useVuelidate from "@vuelidate/core";
import {
    required, email,
    helpers,
} from "@vuelidate/validators";

/**
 * Tabs & accordions component
 */
export default {
    page: {
        title: "Settings",
    },
    setup() {
        return { v$: useVuelidate() }
    },
    components: { Layout, PageHeader, Select2 },
    data() {
        return {
            title: "Settings",
            url: process.env.VUE_APP_URL,
            submitted: false,
            client: {
                firstName: '',
                lastName: '',
                email: '',
                phone: '',
                country: '',
                street: '',
                city: '',
                state: '',
                zipCode: ''
            },
            countryOption: ['Afghanistan', 'Aland Islands', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bonaire', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'British Virgin Islands', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo (Brazzaville)', 'Congo (Kinshasa)', ' Cook Islands', 'Costa Rica', 'Croatia', 'Curaçao', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guernsey', 'Guinea', 'Guinea-Bissau', 'uyana', 'Haiti', 'Heard Island and McDonald Islands', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Ireland', 'Isle of Man', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jersey', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestinian Territories', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Republic of Côte d’Ivoire', 'Republic of Kosovo', 'Reunion', 'Romania', 'Russia', 'Rwanda', 'Saint Barthélemy', 'Saint Helena', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Martin', 'Saint Pierre and Miquelon', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Sint Maarten', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia and the South Sandwich Islands', 'South Korea', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard and Jan Mayen', 'Swaziland', 'Sweden', 'Switzerland', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tokelau', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu', 'U.S. Virgin Islands', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican', 'Venezuela', 'Vietnam', 'Wallis and Futuna', 'Western Sahara', 'Yemen', 'Zambia', 'Zimbabwe'],
        };
    },
    validations: {
        client: {
            firstName: {
                required: helpers.withMessage("First Name is required", required),
            },
            lastName: {
                required: helpers.withMessage("Last Name is required", required),
            },
            email: {
                required: helpers.withMessage("Email  is required", required),
                email: helpers.withMessage("Please Enter a valid Email", email),
            },
            country: {
                required: helpers.withMessage("Country  is required", required),
            },
            street: {
                required: helpers.withMessage("Street is required", required),
            },
            city: {
                required: helpers.withMessage("City is required", required),
            },
            state: {
                required: helpers.withMessage("State is required", required),
            },
            zipCode: {
                required: helpers.withMessage("ZipCode is required", required),
            },
        },
    },
    mounted() {
        this.getSingleClient()
    },
    activated() {
        this.getSingleClient()
    },
    methods: {
        getSingleClient() {
            let data = new FormData()
            data.append('id', atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId')))))
            axios.post(this.url + 'api/getSingleClient', data).then(res => {
                this.client = res.data.client
            })
        },
        formatPhoneNumber() {
            this.client.phone = this.client.phone.replace(/\D/g, "")
            if (this.client.phone.length > 3) {
                this.client.phone = this.client.phone.replace(/(\d{3})(\d{0,3})(\d{0,4})/, "$1-$2-$3");
            } else {
                this.client.phone = this.client.phone.replace(/(\d{0,3})(\d{0,3})/, "$1-$2");
            }
            this.client.phone = this.client.phone.slice(0, 12);
        },
        updateClient() {
            this.submitted = true;
            this.v$.$touch();
            if (this.v$.$invalid) {
                return;
            } else {
                axios.post(this.url + 'api/updateClient', this.client).then(() => {
                    this.$store.state.isAccepted = true
                    this.$store.state.notificationData = 'Profile Updated Successfully'
                    this.$router.push('/dashboard')
                    setTimeout(()=>{
                        this.$store.state.isAccepted = false
                        this.$store.state.notificationData = null
                    }, 2500)
                })
            }

        }
    }
};
</script>