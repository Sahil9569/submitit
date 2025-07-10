<script>
import axios from "axios";
import Layout from "../../layouts/auth";
import { authMethods } from "@/state/helpers";
import appConfig from "@/app.config";
import { required, email, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

/**
 * Forgot Password component
 */
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  validations: {
    email: {
      required: helpers.withMessage("Email is required", required),
      email: helpers.withMessage("Please enter valid email", email),
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
  data() {
    return {
      url: process.env.VUE_APP_URL,
      email: "",
      error: [],
      success: '',
      submitted: false,
      isSuccessMsg: false,
      tryingToReset: false,
      isResetError: false,
    };
  },
  methods: {
    ...authMethods,
    // Try to register the user in with the email, fullname
    // and password they provided.
    tryToReset() {
      this.submitted = true;
      // stop here if form is invalid
      this.v$.$touch();
      if (this.v$.$invalid) {
        return;
      } else {
        this.isSuccessMsg = false;
        this.success = [];
        this.isResetError = false;
        this.error = []
        axios
          .post(this.url + "api/passwordCreate", {
            email: this.email,
          })
          .then((res) => {
            if (!res.data.error) {
              this.isSuccessMsg = true;
              this.success = res.data.message;
            }
          }).catch(error => {
            this.isResetError = true;
            this.error = error.response.data.errors
          });
      }
    },
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
              <h1 class="font-size-22 mb-1">Welcome Back to Submit<span class="text-theme">it</span></h1>
              <p class="font-size-14">Please enter your Email</p>
            </div>
            <div class="p-2">
              <b-alert v-model="isSuccessMsg" class="mb-4" variant="success" dismissible>{{ success }}</b-alert>
              <b-alert v-model="isResetError" class="mb-4" variant="danger" dismissible>{{ error && error.email[0]
              }}</b-alert>
              <form @submit.prevent="tryToReset">
                <div class="mb-3">
                  <label for="useremail">Email</label>
                  <input type="email" v-model="email" class="form-control" id="useremail" placeholder="Enter Email"
                    :class="{ 'is-invalid': submitted && v$.email.$error }" />
                  <div v-for="(item, index) in v$.email.$errors" :key="index" class="invalid-feedback">
                    <span v-if="item.$message">{{ item.$message }}</span>
                  </div>
                </div>
                <div class="mb-3 row mb-0">
                  <div class="col-12 text-end">
                    <button class="btn btn-theme w-md" type="submit">
                      Reset
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
            Remember It ?
            <router-link to="/login" class="fw-medium text-theme">Sign In here</router-link>
          </p>
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
