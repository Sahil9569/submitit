import { createWebHashHistory, createRouter } from "vue-router";
import routes from './routes'
// import CryptoJS from 'crypto-js';

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  mode: 'history',
  // Simulate native-like scroll behavior when navigating to a new
  // route and using back/forward buttons.
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  },
})


router.beforeEach((routeTo, routeFrom, next) => {
  const logged = parseInt(atob(decodeURIComponent(JSON.parse(localStorage.getItem('authRequired1')))));
  const authRe = routeTo.matched.some(record => record.meta.authRequired);
  const authAdmin = routeTo.matched.some(record => record.meta.admin);
  const authPayment = routeTo.matched.some(record => record.meta.payment);
  const userType =  String(atob(decodeURIComponent(JSON.parse(localStorage.getItem('userType1')))));
  const superPackage =  String(atob(decodeURIComponent(JSON.parse(localStorage.getItem('customUser')))));
  const packages = ["price_1N90ymSGmK7KROFRZP7BnS5g", "price_1N90zySGmK7KROFRfWPL7fVn", "price_1N910nSGmK7KROFRFEsmDeW0"];
  

  if(routeTo.name == 'payment' && authPayment){
    next();
  }
  if (authRe && !logged ) {
     return next({ path: '/login' });
  }
  else {
    if (!authRe && logged) {
      return next({ path: '/' });
    }
    else
    {
      if(authAdmin && userType=='client' && routeTo.name == 'addMoreJournal' && packages.includes(superPackage))
      {
        next()
      }
      else if(!authAdmin && userType=='admin')
      {
        return next({ path: '/' });
      }
      else if(authAdmin && userType=='client')
      {
        return next({ path: '/dashboard' });
      }
        next()
    }
  }
})

// router.beforeEach((routeTo, routeFrom, next) => {
//   const logged = JSON.parse(localStorage.getItem('authRequired'));
//   const authRe = routeTo.matched.some(record => record.meta.authRequired);
//   const client = routeTo.matched.some(record => record.meta.client);
//   if (authRe && !logged ) {
//     next({ path: '/login' });
//   } else {
//     if (!authRe && logged == 1) {
//       next({ path: '/' });
//     } else if (!client && logged == 0) {
//       next({ path: '/dashboard' });
//     }
//     next();
//   }
// })

export default router
