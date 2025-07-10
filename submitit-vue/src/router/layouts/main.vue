<script>
import {
  layoutComputed
} from "@/state/helpers";
// import Vertical from "./vertical";
import Header from "@/components/admin/header/header.vue";
import Footer from "@/components/admin/footer/footer.vue";

export default {
  components: {
    // Vertical,
    Header,
    Footer
  },
  data() {
    return {};
  },
  computed: {
    ...layoutComputed,
  },
  methods: {
    remove()
    {
      const submenus = document.querySelectorAll(".submenu");   
        submenus.forEach(submenu => {
            submenu.classList.remove("show");
        });
    }
  },
  mounted() {
    // document.querySelector("html").setAttribute('dir', 'rtl');
  }
};
</script>

<template>
  <div id="layout-wrapper">
    <Header />
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content" @click="remove">
      <div class="page-content">
        <div class="container-fluid">
          <slot />
        </div>
        <!-- container-fluid -->
      </div>
      <!-- End Page-content -->
      <Footer />
    </div>
    <!-- end main content-->
  </div>
</template>
<style>
.table-custom-style {
  --easy-table-border: 1px solid #eff2f7;
  --easy-table-row-border: 1px solid #eff2f7;

  --easy-table-header-font-size: 14px;
  --easy-table-header-height: 45px;
  --easy-table-header-font-color: #495057;
  --easy-table-header-background-color: #eff2f7;

  --easy-table-header-item-padding: 8px 8px;

  --easy-table-body-even-row-font-color: #17252A;
  --easy-table-body-even-row-background-color: #fff;

  --easy-table-body-row-font-color: #17252A;
  --easy-table-body-row-background-color: #fff;
  --easy-table-body-row-height: 50px;
  --easy-table-body-row-font-size: 14px;

  --easy-table-body-row-hover-font-color: #17252A;
  --easy-table-body-row-hover-background-color: #f8f9fa;

  --easy-table-body-item-padding: 8px 8px;

  --easy-table-footer-background-color: #fff;
  --easy-table-footer-font-color: #17252A;
  --easy-table-footer-font-size: 14px;
  --easy-table-footer-padding: 0px 8px;
  --easy-table-footer-height: 50px;

  --easy-table-rows-per-page-selector-width: 70px;
  --easy-table-rows-per-page-selector-option-padding: 8px;
  --easy-table-rows-per-page-selector-z-index: 1;


  --easy-table-scrollbar-track-color: #17252A;
  --easy-table-scrollbar-color: #17252A;
  --easy-table-scrollbar-thumb-color: #485d64;
  --easy-table-scrollbar-corner-color: #344950;

  --easy-table-loading-mask-background-color: #fff;
}
</style>