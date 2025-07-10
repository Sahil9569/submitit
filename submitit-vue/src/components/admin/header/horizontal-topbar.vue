<script>
import axios from 'axios';
import CryptoJS from 'crypto-js';

export default {

  data() {
    return {
      url: process.env.VUE_APP_URL,
      searchText:'',
      filteredItems: []
    };
  },
  methods: {
    toggleMenu() {
      let element = document.getElementById("topnav-menu-content");
      element.classList.toggle("show");
    },
    search() {
      const journal = this.journals.filter((item) => {
        return item.jrnlName.toLowerCase().startsWith(this.searchText.toLowerCase());
      });
      const story = this.stories.filter((item) => {
        return item.storyTitle.toLowerCase().startsWith(this.searchText.toLowerCase());
      });
      const client = this.clients.filter((item) => {
        const name = item.firstName + ' ' + item.lastName;
        return name.toLowerCase().startsWith(this.searchText.toLowerCase());
      });
      this.filteredItems = journal.concat(story, client);
  },
    logout() {
      axios.post(this.url + 'api/logout').then(() => {
        localStorage.removeItem('user');
        localStorage.removeItem('logged');
        localStorage.removeItem('authRequired1');
        localStorage.removeItem('email');
        localStorage.removeItem('userType1');
        localStorage.removeItem('authenticatedUserId');
        localStorage.removeItem('customUser');
        document.cookie = `${'email'}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
        document.cookie = `${'password'}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
        this.$router.push('/login');
      }).catch(error => {
        console.log(error);
      });
    },
    encode(id) {
      return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
    }
  },
  computed: {
    journals() {
        return this.$store.state.normalStore.journals;
    },
    clients() {
        return this.$store.state.normalStore.clients;
    },
    stories() {
        return this.$store.state.normalStore.stories;
    }
  }

};
</script>

<template>
  <header id="page-topbar">
    <div class="navbar-header">
      <div class="d-flex">
        <!-- LOGO -->
        <h1 class="navbar-brand-box">
          <router-link to="/" class="logo logo-dark">
            <span class="logo-sm">
              <img src="@/assets/images/logo-header.png" alt height="36" />
            </span>
            <span class="logo-lg">
              <img src="@/assets/images/logo-header.png" alt height="30" />
            </span>
          </router-link>

          <router-link to="/" class="logo logo-light">
            <span class="logo-sm">
              <img src="@/assets/images/logo-header-light.png" alt height="36" />
            </span>
            <span class="logo-lg">
              <img src="@/assets/images/logo-header-light.png" alt height="30" />
            </span>
          </router-link>
        </h1>

        <button id="toggle" type="button" class="btn btn-sm me-2 font-size-16 d-lg-none header-item" @click="toggleMenu">
          <i class="fa fa-fw fa-bars"></i>
        </button>
      </div>

      <div class="d-flex">
        <!-- App Search-->
        <!-- <form class="app-search d-none d-lg-block">
          <div class="position-relative">
            <input type="text" class="form-control" placeholder="Search..." v-model="searchText"   @input="search"/>
            <span class="bx bx-search-alt"></span>
          </div>
        </form> -->
        <b-dropdown class="d-inline-block ms-2" variant="black"
          menu-class="dropdown-menu-lg p-0 dropdown-menu-end" toggle-class="header-item noti-icon" right>
          <template v-slot:button-content>
            <i class="mdi mdi-magnify"></i>
          </template>

          <form class="p-0">
            <div class="form-group m-0">
              <div class="input-group p-3">
                <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username" v-model="searchText" @input="search"/>
                <div class="input-group-append">
                  <button class="btn btn-theme" type="submit">
                    <i class="mdi mdi-magnify"></i>
                  </button>
                </div>
              </div>
              <ul class="dropListSearch">
                <template v-if="searchText.length==0">
                  <li class="text-center pb-3" v-if="searchText.length==0">
                    No Result Found
                  </li>
                </template>
               <template v-else>
                <li v-for="item in filteredItems" :key="item.id">
                  <router-link
                    :to="(item.arrType === 'Journal' ? '/single_journal/' :( item.arrType === 'Client' ? '/single_client/' :'/single_story/')) + encode(item.id) "
                    class="px-3 py-2"
                  >
                    <span class="searchResultDt">
                      {{ item.arrType === 'Journal' ? item.jrnlName : item.arrType === 'Client' ? item.firstName + ' ' + item.lastName : item.storyTitle }}
                    </span>
                    <span class="catInrSpn">{{ item.arrType }}</span>
                  </router-link>
                </li>
               </template>
              </ul>
            </div>
          </form>
        </b-dropdown>


        <!-- <b-dropdown menu-class="dropdown-menu-lg p-0 dropdown-menu-end" toggle-class="header-item noti-icon"
          variant="black">
          <template v-slot:button-content>
            <i class="bx bx-bell bx-tada"></i>
            <span class="badge bg-danger rounded-pill">

              0</span>
          </template>

          <div class="p-3">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="m-0">
                  Notifications
                </h6>
              </div>
              <div class="col-auto">
                <a href="#" class="small"></a>
              </div>
            </div>
          </div>
          <SimpleBar style="max-height: 230px">
            <a href="javascript: void(0);" class="text-reset notification-item">
              <div class="d-flex">
                <div class="avatar-xs me-3">
                  <span class="avatar-title bg-primary rounded-circle font-size-16">
                    <i class="bx bx-cart"></i>
                  </span>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mt-0 mb-1">
                    Dummy Title
                  </h6>
                  <div class="font-size-12 text-muted">
                    <p class="mb-1">
                      Lorem Ipsum dummy description
                    </p>
                    <p class="mb-0">
                      <i class="mdi mdi-clock-outline"></i>
                      12.00
                    </p>
                  </div>
                </div>
              </div>
            </a>
          </SimpleBar>
          <div class="p-2 border-top d-grid">
            <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
              <i class="mdi mdi-arrow-right-circle me-1"></i>
              <span key="t-view-more">
                View more</span>
            </a>
          </div>
        </b-dropdown> -->

        <b-dropdown right variant="black" toggle-class="header-item">
          <template v-slot:button-content>
            <img class="d-none rounded-circle header-profile-user" src="@/assets/images/users/avatar-1.jpg"
              alt="Header Avatar" />
            <span class="text-truncate userNameStyle">{{ $store.state.authenticatedUser.firstName }}
              {{ $store.state.authenticatedUser.lastName }}</span>
            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
          </template>
          <!-- item-->
          <!-- <b-dropdown-item>
            <router-link to="/contacts/profile" v-slot="{ navigate }" custom>
              <span @click="navigate" @keypress.enter="navigate">
                <i class="bx bx-user font-size-16 align-middle me-1"></i>
                Profile
              </span>
            </router-link>
          </b-dropdown-item>
          <b-dropdown-item class="d-block" href="javascript: void(0);">
            <span class="badge bg-success float-end">0</span>
            <i class="bx bx-wrench font-size-16 align-middle me-1"></i>
            Setting
          </b-dropdown-item>
          <b-dropdown-divider></b-dropdown-divider> -->

          <div v-if="$store.state.authenticatedUser.userType === 'admin'">
            <router-link to="/profile" class="dropdown-item text-info">
              <i class="bx bx-user font-size-16 align-middle me-1"></i>
              <!-- <i class="bx bx-power-off font-size-16 align-middle me-1 text-info"></i> -->
              Profile
            </router-link>
            <router-link to="/emailTemplate" class="dropdown-item text-info">
              <i class="mdi mdi-file-document-outline font-size-16 align-middle me-1"></i>
              <!-- <i class="bx bx-power-off font-size-16 align-middle me-1 text-info"></i> -->
              Email Template
            </router-link>
            <router-link to="/defaultTask" class="dropdown-item text-success">
              <i class="mdi mdi-file-document-outline font-size-16 align-middle me-1 text-success"></i>
              Task Management
            </router-link>
            <router-link to="/addExtraServices" class="dropdown-item text-theme">
              <i class="mdi mdi-file-document-outline font-size-16 align-middle me-1 text-theme"></i>
              Extra Services
            </router-link>
            <router-link to="/pendingPayments" class="dropdown-item text-theme">
              <i class="mdi mdi-file-document-outline font-size-16 align-middle me-1 text-theme"></i>
                Pending Payments
            </router-link>
          </div>
          <div v-if="$store.state.authenticatedUser.userType === 'client'">
            <router-link to="/pendingPayment" class="dropdown-item text-theme">
              <i class="mdi mdi-file-document-outline font-size-16 align-middle me-1 text-theme"></i>
                Pending Payment
            </router-link>
            
          </div>
          <button @click="logout" class="dropdown-item text-danger">
            <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i>
            Logout
          </button>
        </b-dropdown>

      </div>
    </div>
  </header>
</template>
