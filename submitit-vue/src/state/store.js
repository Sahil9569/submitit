import { createStore } from 'vuex'

import module_file from './modules'
import axios from 'axios'
import normalStore from './normalStore'

const store = createStore({
  state() {
    return {
      authenticatedUser: [],
      authenticated: [],
      BASE_URL: process.env.VUE_APP_URL,
      singleClientId: '',
      otherJournals: [],
      selectedJournals : [],
      selectedPackages : [],
      allTasks : [],
      rejectJournals: [],
      isSubscribe: false,
      isAccepted: false,
      isDeclined: false,
      notificationData: null,
      stories: [],
      journals: [],
      users: []
    }
  },
  module_file,
  modules: {
    normalStore,
  },
  actions: {
    async fetchDataFromAPI({ commit, state }) {
      try {
        const response = await axios.get(state.BASE_URL + 'api/getAllData');
        const data = response.data;
        console.log(data)
        commit('updateData', data);
      } catch (error) {
        console.error('API call failed:', error);
      }
    },
  },
  mutations: {
    updateData(state, newData) {
      state.journals = newData.journals;
      state.users = newData.users;
      state.stories = newData.stories
    },
  },
  // Enable strict mode in development to get a warning
  // when mutating state outside of a mutation.
  // https://vuex.vuejs.org/guide/strict.html
})

export default store

