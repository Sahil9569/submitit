<template>
  <div>
    <HorizontalTopbar @click="remove"/>
    <HorizontalNav/>
  </div>
</template>

<script>
import HorizontalTopbar from "./horizontal-topbar.vue";
import HorizontalNav from "./horizontal-nav.vue";
import { layoutComputed } from "@/state/helpers";
import axios from "axios";

/**
 * Horizontal-layout
 */
export default {
  props: {},
  data() {
    return {
      url: process.env.VUE_APP_URL
    };
  },
  computed: {
    ...layoutComputed,
  },
  mounted() {
    this.getAuthenticatedUser()
  },
  components: {
    HorizontalTopbar,
    HorizontalNav
  },
  methods: {
    getAuthenticatedUser() {
      let data = new FormData();
      data.append('email', atob(decodeURIComponent(JSON.parse(localStorage.getItem('email')))));
      axios.post(this.url + 'api/getAuthenticatedUser', data).then(res => {
        this.$store.state.authenticatedUser = res.data
        this.$store.dispatch('getAllData') 
        localStorage.setItem('customUser', JSON.stringify(encodeURIComponent(btoa(res.data.superPackage))));
      })
    },
    remove() 
    {
      const submenus = document.querySelectorAll(".submenu");   
        submenus.forEach(submenu => {
            submenu.classList.remove("show");
        });
    }
  }
};
</script>

