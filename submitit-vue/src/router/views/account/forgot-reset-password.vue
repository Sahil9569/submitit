<script>

import axios from "axios";
import Layout from "../../layouts/auth";
import { authMethods } from "@/state/helpers";
import appConfig from "@/app.config";
import { required, minLength, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

/**
 * Forgot Password component
 */
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
        },

    },

    page: {
        title: "Forgot Password",
        meta: [
            {
                name: "description",
                content: appConfig.description,
            },
        ],
    },
    components: {
        Layout,
    },
    name: 'forgot-reset-password',
    props: ['token'],
    data() {
        return {
            email: "",
            new_password: "",
            confirm_password: "",
            isToken: this.$route.params.token,
            interval: null,
            submitted: false,
            error: null,
            errors: {},
            success: null,
            tryingToReset: false,
            isResetError: false,
            isResetErrors: false,
            isResetSuccess: false,
            url: process.env.VUE_APP_URL
        };
    },
    activated() {
        this.getEmail();
    },
    methods: {
        ...authMethods,
        // Try to register the user in with the email, fullname
        // and password they provided.
        newPassword() {
            this.submitted = true;
            this.v$.$touch();
            if (this.v$.$invalid) {
                return;
            } else {
                axios
                    .post(this.url + "api/password/reset", {
                        token: this.isToken,
                        new_password: this.new_password,
                        confirm_password: this.confirm_password
                    })
                    .then(res => {
                        if (res.data.error) {
                            this.isResetError = true;
                            this.isResetErrors = false;
                            this.isResetSuccess = false;
                            this.error = res.data.message;
                        } else {
                            this.isResetSuccess = true;
                            this.isResetError = false;
                            this.isResetErrors = false;
                            this.success = res.data.message;
                            if (this.success) {
                                this.interval = setTimeout(
                                    function () {
                                        this.$router.push({ name: 'login' });
                                    }.bind(this),
                                    2000
                                );
                            }
                        }
                    }).catch(error => {
                        this.tryingToReset = false;
                        this.isResetSuccess = false;
                        this.isResetError = false;
                        this.errors = error.response.data.errors;
                        this.isResetErrors = true;

                    });
            }
        },
        getEmail() {
            axios.post(this.url + 'api/getEmail', { token: this.$route.params.token }).then(res => {
                this.email = res.data.email;
            })
        }



    },
};
</script>

<template>
    <Layout>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="text-center">
                            <h1 class="font-size-22 mb-1">Welcome Back to Submit <span class="text-theme">it</span></h1>
                            <p class="font-size-14">Please enter your new Password</p>
                        </div>
                        <div class="p-2">
                            <b-alert v-model="isResetError" class="mb-4" variant="danger" dismissible>{{
                                error
                            }}</b-alert>
                            <b-alert v-model="isResetErrors" class="mb-4" variant="danger" dismissible
                                v-for="(item, index) in errors" :key="index">{{
                                    item[0]
                                }}</b-alert>
                            <b-alert v-model="isResetSuccess" class="mb-4" variant="success" dismissible>{{
                                success
                            }}</b-alert>
                            <form @submit.prevent="newPassword">
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" v-model="email" name="email" class="form-control" id="email_id"
                                        readonly placeholder="Enter Email" />
                                </div>
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
                                            Reset Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->

                <div class="mt-5 text-center">
                    <p>
                        © {{ new Date().getFullYear() }} Submitit
                    </p>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </Layout>
</template>

<style lang="scss" module></style>
