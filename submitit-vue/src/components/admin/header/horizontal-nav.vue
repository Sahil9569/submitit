<script>
// import { layoutMethods } from "@/state/helpers";
import { menuItemsAdmin } from "./horizontal-menu";
import { menuItemsClient } from './horizontal-menu-client';
export default {
  data() {
    return {
      menuItems: null,
    };
  },
  beforeMount() {
    this.menuItems = atob(decodeURIComponent(JSON.parse(localStorage.getItem('userType1')))) !== 'client' ? menuItemsAdmin : menuItemsClient
  },
  methods: {
    onMenuClick(event) {
      event.preventDefault();
      const nextEl = event.target.nextElementSibling;
      const submenus = document.querySelectorAll(".submenu");

      if (nextEl && nextEl.classList.contains("submenu")) {
        submenus.forEach(submenu => {
          if (submenu != nextEl) {
            submenu.classList.remove("show");
          }
        });
        nextEl.classList.add("show");
      } else {
        submenus.forEach(submenu => {
          submenu.classList.add("show");
        });
      }
      // var topbar = document.querySelectorAll("#topnav-dashboard");
      // topbar.classList.remove("show");
      // var navbar = document.querySelectorAll("#topnav-menu-content");
      // navbar.classList.remove("show");
      
    },
    onSubMenuClick(event)
    {
      event.preventDefault();
      const submenus = document.querySelectorAll(".submenu");


  submenus.forEach(submenu => {
    
      submenu.classList.remove("show");
   
  });

  var topbar = document.querySelector("#topnav-dashboard");
      topbar.classList.remove("show");
      var navbar = document.querySelector("#topnav-menu-content");
      navbar.classList.remove("show");
    },
    /**
     * Returns true or false if given menu item has child or not
     * @param item menuItem
     */
    hasItems(item) {
      return item.subItems !== undefined ? item.subItems.length > 0 : false;
    },
  },

};
</script>
<template>
  <div class="topnav">
    <div class="container-fluid">
      <nav class="navbar navbar-light navbar-expand-lg topnav-menu active">
        <div class="collapse navbar-collapse active" id="topnav-menu-content">
          <ul class="navbar-nav">
            <li class="nav-item dropdown" v-for="menu in menuItems" :key="menu.id">
              <template v-if="menu.subItems">
                <span class="nav-link dropdown-toggle arrow-none d-block" role="button"  @click="onMenuClick($event)">
                  <i class="me-2" :class="`${menu.icon}`"></i>
                  {{ menu.label }}
                  <div class="arrow-down"></div>
                </span>
                <div class="dropdown-menu submenu" aria-labelledby="topnav-dashboard" id="topnav-dashboard">
                  <router-link class="col dropdown-item side-nav-link-ref linkURL" v-for="subitem in menu.subItems"
                    :key="subitem.id" :to="`${subitem.link}`" @click="onSubMenuClick($event)">
                    <i class="me-2" :class="`${subitem.icon}`"></i>
                    {{ subitem.label }}
                  </router-link>
                </div>
              </template>
              <template v-else>
                <router-link :to="`${menu.link}`" class="nav-link dropdown-toggle arrow-none linkURL">
                  <i class="me-2" :class="`${menu.icon}`"  @click="onSubMenuClick($event)"></i>
                  {{ menu.label }}
                </router-link>
              </template>
            </li>
            <!-- Menu data -->
          </ul>
        </div>
      </nav>
    </div>
  </div>
</template>