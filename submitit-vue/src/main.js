import { createApp } from 'vue'

import App from './App.vue'
import router from "./router";
import BootstrapVue3 from 'bootstrap-vue-3';
import 'sweetalert2/dist/sweetalert2.min.css';
import '@vueform/slider/themes/default.css';

import store from "./state/store";

import "../src/design/app.scss";
import "../src/assets/custom.css"


createApp(App)
  .use(store)
  .use(router)
  .use(BootstrapVue3)
  .mount('#app')
