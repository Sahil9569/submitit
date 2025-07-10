<script>
import axios from "axios";
import Layout from "../../layouts/auth";
import {
  authMethods,
  authFackMethods,
  notificationMethods,
} from "@/state/helpers";
import { mapState } from "vuex";
import appConfig from "@/app.config";
import { required, email, helpers } from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import CryptoJS from 'crypto-js';
/**
 * Login component
 */
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  page: {
    title: "Login",
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
      email: "",
      password: "",
      rememberMe: "",
      submitted: false,
      authError: null,
      tryingToLogIn: false,
      isAuthError: false,
      showPassword: false,
      url: process.env.VUE_APP_URL
    };
  },
  validations: {
    email: {
      required: helpers.withMessage("Email is required", required),
      email: helpers.withMessage("Please enter valid email", email),
    },
    password: {
      required: helpers.withMessage("Password is required", required),
    },
  },
  computed: {
    ...mapState("authfack", ["status"]),
    notification() {
      return this.$store ? this.$store.state.notification : null;
    },
  },
  activated() {
    this.getCookies();
  },
  created() {
    this.getCookies();
  },
  methods: {
    getCookies() {
      if (document.cookie.length !== 0) {
        let UserData = document.cookie.split(";");
        for (var i = 0; i < UserData.length; i++) {
          let email = UserData[i].split("=");
          if (email[0] === " email") {
            this.rememberMe = "accepted";
            this.email = decodeURI(CryptoJS.AES.decrypt(email[1], "Secret Passphrase").toString(CryptoJS.enc.Utf8));
          }
          if (email[0] === " password") {
            this.password = decodeURI(CryptoJS.AES.decrypt(email[1], "Secret Passphrase").toString(CryptoJS.enc.Utf8));
          }
        }

      }
    },
    toggleShow() {
      this.showPassword = !this.showPassword;
    },
    ...authMethods,
    ...authFackMethods,
    ...notificationMethods,
    // Try to log the user in with the username
    // and password they provided.
    tryToLogIn() {
      this.submitted = true;
      // stop here if form is invalid
      this.v$.$touch();
      if (this.v$.$invalid) {
        return;
      } else {
        axios
          .post(this.url + "api/login", {
            email: this.email,
            password: this.password,
          })
          .then(res => {
            if (res.data.error) {
              this.authError = res.data.message;
              this.isAuthError = true;
              this.tryingToLogIn = false;
              this.submitted = false;
            } else {
              if (this.rememberMe === "accepted") {
                document.cookie = `email=${encodeURI(CryptoJS.AES.encrypt(this.email, "Secret Passphrase"))}; max-age=${7 * 24 * 60 * 60}`
                document.cookie = `password=${encodeURI(CryptoJS.AES.encrypt(this.password, "Secret Passphrase"))}; max-age=${7 * 24 * 60 * 60}`
              }
              this.isAuthError = false;
              localStorage.setItem('authenticatedUserId', JSON.stringify(encodeURIComponent(btoa(res.data.id))));
              localStorage.setItem('email', JSON.stringify(encodeURIComponent(btoa(res.data.email))));
              localStorage.setItem('userType1', JSON.stringify(encodeURIComponent(btoa(res.data.userType))));
              localStorage.setItem('customUser', JSON.stringify(encodeURIComponent(btoa(res.data.superPackage))));
              if (res.data.userType === "admin") {
                localStorage.setItem('authRequired1', JSON.stringify(encodeURIComponent(btoa(1))));
                this.$router.push(
                  this.$route.query.redirectFrom || {
                    name: "dashboard",
                  }
                );
              } else {
                localStorage.setItem('authRequired1', JSON.stringify(encodeURIComponent(btoa(1))));
                this.$router.push({ path: '/dashboard' });
              }



            }
          }).catch((error) => {
            this.submitted = false
            this.tryingToLogIn = false;
            this.authError = error ? error : "";
            this.isAuthError = true;
          });
      }
    },

    // },
  },
};
</script>

<template>
  <Layout>
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card overflow-hidden">
          <div class="card-body">
            <div class="text-center mb-3">
              <h1 class="font-size-22 mb-1">Welcome Back to Submit<span class="text-theme">it</span></h1>
              <!-- <p class="font-size-14 mb-0">Please enter your detail</p> -->
            </div>
            <b-alert v-model="isAuthError" variant="danger" class="mt-3" dismissible>{{ authError }}</b-alert>
            <div v-if="notification" :class="'alert ' + notification.type">
              {{ notification.message }}
            </div>
            <b-form class="p-2" @submit.prevent="tryToLogIn">
              <b-form-group class="mb-3" id="input-group-1" label="Email" label-for="input-1">
                <b-form-input id="email_id" v-model="email" type="text" placeholder="Enter email" :class="{
                  'is-invalid': submitted && v$.email.$error,
                }"></b-form-input>
                <div v-for="(item, index) in v$.email.$errors" :key="index" class="invalid-feedback">
                  <span v-if="item.$message">{{ item.$message }}</span>
                </div>
              </b-form-group>

              <b-form-group class="mb-3" id="input-group-2" label="Password" label-for="input-2">
                <div class="input-group side-image-fix auth-pass-inputgroup">
                  <b-form-input v-if="showPassword" id="password_id" v-model="password" type="text"
                    autocomplete="password" placeholder="Enter password" :class="{
                      'is-invalid': submitted && v$.password.$error,
                    }">
                  </b-form-input>
                  <b-form-input v-else id="password_id" v-model="password" type="password" autocomplete="password"
                    placeholder="Enter password" :class="{
                      'is-invalid': submitted && v$.password.$error,
                    }">
                  </b-form-input>
                  <button @click="toggleShow" class="btn btn-light button-eye-style" type="button" id="password-addon">
                    <i class="mdi" :class="{ 'mdi-eye-off-outline': showPassword, 'mdi-eye-outline': !showPassword }"></i>
                  </button>
                  <div v-if="submitted && v$.password.$error" class="invalid-feedback">
                    <span v-if="v$.password.required.$message">{{
                      v$.password.required.$message
                    }}</span>
                  </div>
                </div>

              </b-form-group>
              <b-form-checkbox v-model="rememberMe" class="form-check me-2 mt-0" id="customControlInline"
                name="checkbox-1" value="accepted" unchecked-value="not_accepted">Remember me
              </b-form-checkbox>
              <div class="mt-3 d-grid">
                <button type="submit" class="btn btn-theme">Log In</button>
              </div>
              <!-- <div class="mt-4 text-center">
                                        <h5 class="font-size-14 mb-3">Sign in with</h5>

                                        <ul class="list-inline">
                                          <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="
                                                social-list-item
                                                bg-primary
                                                text-white
                                                border-primary
                                              ">
                                              <i class="mdi mdi-facebook"></i>
                                            </a>
                                          </li>
                                          <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item bg-info text-white border-info">
                                              <i class="mdi mdi-twitter"></i>
                                            </a>
                                          </li>
                                          <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="
                                                social-list-item
                                                bg-danger
                                                text-white
                                                border-danger
                                              ">
                                              <i class="mdi mdi-google"></i>
                                            </a>
                                          </li>
                                        </ul>
                                      </div> -->
              <div class="mt-4 text-center">
                <router-link to="/forgot-password" class="text-theme">
                  <i class="mdi mdi-lock me-1"></i> Forgot your password?
                </router-link>
              </div>
            </b-form>
          </div>
          <!-- end card-body -->
        </div>
        <!-- end card -->

        <div class="mt-5 text-center">
          <p>
            © {{ new Date().getFullYear() }} Submitit
          </p>
        </div>
        <!-- end row -->
      </div>
      <!-- end col -->
    </div>
    <!-- end row -->
  </Layout>
</template>