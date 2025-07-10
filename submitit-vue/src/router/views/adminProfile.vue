<script>
import Layout from "../../router/layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import axios from 'axios';
import { required, minLength, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

    
export default {
    setup() {
        return { v$: useVuelidate() };
    },
    validations: {
        new_password: {
            required: helpers.withMessage("New Password is required", required),
            minLength: minLength(8)
        },
        confirm_password: {
            required: helpers.withMessage("Confirm Password is required", required),
            minLength: minLength(8)

        },

    },
    page: {
        // title: "Dashboard",
        meta: [
            {
                name: "description",
                content: 'description',
            },
        ],
    },
    data() {
        
        return {
            title: "Profile",
            url: process.env.VUE_APP_URL,
            client: [],
            userType: null,
            new_password: "",
            confirm_password: "",
            submitted: false,
            errors: {},
            tryingToReset: false,
            isResetError: false,
            isResetErrors: false,
            isResetSuccess: false,
         }
    },
    components: {
        Layout,
        PageHeader,
    },
    created() {
        this.userType = atob(decodeURIComponent(JSON.parse(localStorage.getItem('customUser'))))
        this.getSingleClient()
    },
    activated() {
        this.userType = atob(decodeURIComponent(JSON.parse(localStorage.getItem('customUser'))))
        this.getSingleClient();
    },
    methods: {
        getSingleClient() {
            axios.post(this.url + 'api/getSingleClient',{id:atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId'))))}).then(res => {
                this.client = res.data.client
            })
        },
        changePassword() {
            this.submitted = true;
            this.v$.$touch();
            if (this.v$.$invalid) {
                return;
            } else {
                axios
                    .post(this.url + "api/password/change", {
                        id:atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId')))),
                        new_password: this.new_password,
                        confirm_password: this.confirm_password
                    })
                    .then(() => {
                        this.new_password = '';
                        this.confirm_password ='';
                        this.$store.state.isAccepted = true
                        this.$store.state.notificationData = 'Password Changed Successfully'
                    setTimeout(() => {
                        this.$store.state.isAccepted = false
                        this.$store.state.notificationData = null
                    }, 2500)
                    }).catch(error => {
                        this.tryingToReset = false;
                        this.isResetSuccess = false;
                        this.isResetError = false;
                        this.errors = error.response.data.errors;
                        this.isResetErrors = true;

                    });
            }
        },
    }
};
</script>

<template>
    <Layout>
        <PageHeader :title="title" />
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body pt-4">
                        <!-- <h2 class="card-title">Client Overview</h2> -->
                        <!-- <div class="mainButtonEdit">
                            <router-link to="/setting" class="editButtonIcon buttonPosEdit"><i
                                    class="mdi mdi-pencil"></i></router-link>
                        </div> -->
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <div class="avatar-lg mb-3 mx-auto">
                                    <img :src="`https://ui-avatars.com/api/?name=${String(this.client.firstName).charAt(0).toUpperCase() + String(this.client.lastName).charAt(0).toUpperCase()}&background=3aafa9&size=200`"
                                        alt="" class="img-thumbnail fullCoverImage rounded-circle" />
                                </div>
                                <div class="card-client-info text-center">
                                    <h2>{{ client.firstName }} {{ client.lastName }}</h2>
                                    <p class="text-muted font-size-14">{{ client.email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="main-group-info">
                                    <div class="inner-group-info border-bottom pb-2 mb-2">
                                        <label class="text-muted">Email:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.email }}</h3>
                                    </div>
                                    <div class="inner-group-info border-bottom pb-2 mb-2">
                                        <label class="text-muted">Shared Email:</label>
                                        <h3 class="card-title font-weight-500 mb-0" v-if="client.sharedEmail">{{ client.sharedEmail }}</h3>
                                        <h3 class="card-title font-weight-500 mb-0" v-else>NULL</h3>
                                    </div>
                                    <div class="inner-group-info border-bottom pb-2 mb-2">
                                        <label class="text-muted">Phone:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.phone }}</h3>
                                    </div>
                                    <div class="inner-group-info border-bottom pb-2 mb-2">
                                        <label class="text-muted">Date:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.date }}</h3>
                                    </div>
                                    <div class="inner-group-info">
                                        <label class="text-muted">Address:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.street }}, {{ client.city }},
                                            {{ client.state }} {{ client.zipCode }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="row">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="font-size-22 mb-1">Change Password</h1>
                        <p class="font-size-14">Please enter your new Password</p>
                    </div>
                    <div class="p-2">
                        <!-- <b-alert v-model="isResetError" class="mb-4" variant="danger" dismissible>{{
                            error
                        }}</b-alert> -->
                        <b-alert v-model="isResetErrors" class="mb-4" variant="danger" dismissible
                            v-for="(item, index) in errors" :key="index">{{
                                item[0]
                            }}</b-alert>
                        <!-- <b-alert v-model="isResetSuccess" class="mb-4" variant="success" dismissible>{{
                            success
                        }}</b-alert> -->
                        <form @submit.prevent="changePassword">
                            <div class="mb-3">
                                <label for="new_password">New Password</label>
                                <input type="password" v-model="new_password" name="new_password" class="form-control"
                                    id="new_password_id" placeholder="Enter New Password"
                                    :class="{ 'is-invalid': submitted && v$.new_password.$error }" />

                                <div v-for="(item, index) in v$.new_password.$errors" :key="index"
                                    class="invalid-feedback">
                                    <span v-if="item.$message">{{ item.$message }}</span>
                                </div>

                            </div>
                            <div class="mb-3">
                                <label for="confirm_password">New Confirm Password</label>
                                <input type="password" v-model="confirm_password" name="confirm_password"
                                    class="form-control" id="confirm_password_id" placeholder="Enter Confirm Password"
                                    :class="{ 'is-invalid': submitted && v$.confirm_password.$error }" />

                                <div v-for="(item, index) in v$.confirm_password.$errors" :key="index"
                                    class="invalid-feedback">
                                    <span v-if="item.$message">{{ item.$message }}</span>
                                </div>

                            </div>
                            <div class="mb-3 row mb-0">
                                <div class="col-12 text-end">
                                    <button class="btn btn-theme w-md" type="submit">
                                        Change Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end card-body -->
            </div>
            </div>
        </div>
        </div>
    </Layout>
</template>


