import store from "@/state/store";

export default [
  {
    path: "/",
    name: "dashboard",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/dashboard"),
  },
  {
    path: "/profile",
    name: "Profile",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/adminProfile"),
  },
  {
    path: "/client",
    name: "Client",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/client"),
  },
  {
    path: "/addClient",
    name: "addClient",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/addClient"),
  },
  {
    path: "/addStory/:id?",
    name: "addStory",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/addStories"),
  },
  {
    path: "/addJournalStory/:id?",
    name: "addJournalStory",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/addJournalStory"),
  },
  {
    path: "/edit_journalStory/:id?",
    name: "edit_journalStory",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/addJournalStory"),
  },
  {
    path: "/login",
    name: "login",
    component: () => import("./views/account/login"),
    meta: {
      beforeResolve(routeTo, routeFrom, next) {
        if (store.getters["auth/loggedIn"]) {
          next({ name: "dashboard" });
        } else {
          next();
        }
      },
    },
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("./views/account/register"),
    meta: {
      beforeResolve(routeTo, routeFrom, next) {
        if (store.getters["auth/loggedIn"]) {
          next({ name: "dashboard" });
        } else {
          next();
        }
      },
    },
  },
  {
    path: "/forgot-password",
    name: "Forgot password",
    component: () => import("./views/account/forgot-password"),
    meta: {
      beforeResolve(routeTo, routeFrom, next) {
        // If the user is already logged in
        if (store.getters["auth/loggedIn"]) {
          // Redirect to the home page instead
          next({ name: "dashboard" });
        } else {
          // Continue to the login page
          next();
        }
      },
    },
  },
  {
    path: "/forgot-reset-password/:token",
    name: "Forgot reset password",
    component: () => import("./views/account/forgot-reset-password"),
    props: true,
    meta: {
      beforeResolve(routeTo, routeFrom, next) {
        // If the user is already logged in
        if (store.getters["auth/loggedIn"]) {
          // Redirect to the home page instead
          next({ name: "default" });
        } else {
          // Continue to the login page
          next();
        }
      },
    },
  },
  {
    path: "/logout",
    name: "logout",
    component: () => import("./views/account/logout"),
    meta: {
      authRequired: true,
      beforeResolve(routeTo, routeFrom, next) {
        if (process.env.VUE_APP_DEFAULT_AUTH === "firebase") {
          store.dispatch("auth/logOut");
        } else {
          store.dispatch("authfack/logout");
        }
        const authRequiredOnPreviousRoute = routeFrom.matched.some((route) =>
          route.push("/login")
        );
        next(
          authRequiredOnPreviousRoute ? { name: "dashboard" } : { ...routeFrom }
        );
      },
    },
  },


  {
    path: "/single_client/:id",
    name: "single_client",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/single_client"),
  },
  {
    path: "/edit_client/:id",
    name: "edit_client",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/addClient"),
  },
  {
    path: "/stories",
    name: "stories",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/stories"),
  },
  {
    path: "/defaultTask",
    name: "Default_Task",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/defaultTask"),
  },
  {
    path: "/single_story/:id",
    name: "single_story",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/single_story"),
  },
  {
    path: "/editStory/:id",
    name: "editStory",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/addStories"),
  },
  {
    path: "/addJournal",
    name: "Add Journal",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/addJournal"),
  },
  {
    path: "/journals",
    name: "journals",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/journals"),
  },
  {
    path: "/manageJournals",
    name: "manage journals",
    meta: { authRequired: true, admin:true },
    component: () => import("./views/manage-journals"),
  },
  {
    path: "/single_journal/:id?",
    name: "single_journal",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/single_journal"),
  },
  {
    path: "/edit_journal/:id",
    name: "edit_journal",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/addJournal"),
  },
  {
    path: "/allTask",
    name: "allTask",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/allTask"),
  },
  {
    path: "/addMoreJournal",
    name: "addMoreJournal",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/addMoreJournal"),
  },
  {
    path: "/journalStory/:id",
    name: "Journal Story",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/journalStory"),
  },
  {
    path: "/allResponse",
    name: "allResponse",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/allResponse"),
  },
  {
    path: "/pendingPayments",
    name: "pendingPayments",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/pendingPayments"),
  },
  {
    path: "/signUp",
    name: "signUp",
    component: () => import("./views/signUp"),
  },
  {
    path: "/superSignUp",
    name: "superSignUp",
    component: () => import("./views/superSignUp"),
  },
  {
    path: "/payment/:id",
    name: "payment",
    meta: { 
      authRequired: true , 
      payment:true
    },
    component: () => import("./views/payment"),
  },
  //----Client-Portal-routing
  {
    path: "/dashboard",
    name: "dashboardClient",
    meta: { authRequired: true, admin: false },
    component: () => import("../components/client/dashboard"),
  },
  {
    path: "/submissionResult",
    name: "storySubmissionResult",
    meta: { authRequired: true, admin: false },
    component: () => import("../components/client/storySubmissionResult"),
  },
  {
    path: "/add_story",
    name: "addStoryClient",
    meta: { authRequired: true, admin: false },
    component: () => import("../components/client/addStory"),
  },
  {
    path: "/editClientStory/:id",
    name: "editClientStory",
    meta: { authRequired: true, admin: false },
    component: () => import("../components/client/addStory"),
  },
  {
    path: "/stories_listing",
    name: "storiesClient",
    meta: { authRequired: true, admin: false },
    component: () => import("../components/client/storyListing"),
  },
  {
    path: "/story_information/:id",
    name: "single_storyClient",
    meta: { authRequired: true, admin: false },
    component: () => import("../components/client/singleStory"),
  },
  {
    path: "/single_journal",
    name: "single_journalClient",
    meta: { authRequired: true },
    component: () => import("../components/client/singleJournal"),
  },
  {
    path: "/setting",
    name: "settingClient",
    meta: { authRequired: true, admin: false },
    component: () => import("../components/client/setting"),
  },
  {
    path: "/invoice",
    name: "invoiceClient",
    meta: { authRequired: true, admin: false },
    component: () => import("../components/client/invoice"),
  },
  {
    path: "/pendingPayment",
    name: "pendingPayment",
    meta: { authRequired: true, admin: false },
    component: () => import("../components/client/pendingPayment"),
  },
  {
    path: "/activeSubscription",
    name: "activeSubscription",
    meta: { authRequired: true, admin: false },
    component: () => import("../components/client/activeSubscription"),
  },

  //  Add Extra Services
  {
    path: "/addExtraServices",
    name: "addExtraServices",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/addExtraServices"),
  },
  // Ckeditor
  {
    path: "/emailTemplate",
    name: "emailTemplate",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/emailTemplates"),
  },
  {
    path: "/editEmailTemplate/:id",
    name: "editEmailTemplate",
    meta: { authRequired: true, admin: true },
    component: () => import("./views/ckEditor"),
  },
  // Theme-routing-below
  {
    path: "/invoices/detail",
    name: "Invoice Detail",
    meta: { authRequired: true },
    component: () => import("./views/invoices/detail"),
  },
  {
    path: "/invoices/list",
    name: "Invoice List",
    meta: { authRequired: true },
    component: () => import("./views/invoices/list"),
  },
  {
    path: "/pages/starter",
    name: "Starter",
    meta: { authRequired: true },
    component: () => import("./views/utility/starter"),
  },
  {
    path: "/pages/maintenance",
    name: "Maintenance",
    meta: { authRequired: true },
    component: () => import("./views/utility/maintenance"),
  },
  {
    path: "/pages/coming-soon",
    name: "coming-soon",
    meta: { authRequired: true },
    component: () => import("./views/utility/coming-soon"),
  },
  {
    path: "/pages/timeline",
    name: "Timeline",
    meta: { authRequired: true },
    component: () => import("./views/utility/timeline"),
  },
  {
    path: "/pages/faqs",
    name: "FAQs",
    meta: { authRequired: true },
    component: () => import("./views/utility/faqs"),
  },
  {
    path: "/pages/pricing",
    name: "Pricing",
    meta: { authRequired: true },
    component: () => import("./views/utility/pricing"),
  },
  {
    path: "/pages/404",
    name: "Error-404",
    meta: { authRequired: true },
    component: () => import("./views/utility/404"),
  },
  {
    path: "/pages/500",
    name: "Error-500",
    meta: { authRequired: true },
    component: () => import("./views/utility/500"),
  },
  {
    path: "/404",
    name: "404",
    component: require("./views/utility/404").default,
  },
  {
    path: '/:pathMatch(.*)*',
    meta: { authRequired: true, admin: true },
    component: () => import("./views/dashboard")
  },
];