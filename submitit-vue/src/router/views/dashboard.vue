<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import Tasks from './sub-components/tasks.vue';
import axios from 'axios';

export default {
  page: {
    // title: "Dashboard",
    meta: [
      {
        name: "description",
        content: 'description',
      },
    ],
  },
  data() {
    return {
      url: process.env.VUE_APP_URL,
      dataFetched : false,
      title: "Dashboard",
      totalClients: 0,
      totalJournals: 0,
      recentStories: [],
      recentJournals: [],
      tasks: [],
      storyInReview : 0,
      isDue : 0,
      id: null
    }
  },
  components: {
    Layout,
    PageHeader,
    Tasks
    // EasyDataTable
  },
  created() {
    if(!this.dataFetched)
    {
      this.getClients()
      this.getJournals()
      this.getRecentJournals()
      this.getRecentStory()
      this.getAllTasks()
      this.getStoryInReview()
    }
  },
  activated() {
    if(this.dataFetched)
    {
      this.getClients()
      this.getJournals()
      this.getRecentJournals()
      this.getRecentStory()
      this.getAllTasks()
      this.getStoryInReview()
    }
  },
  methods: {
    getClients() {
      if(this.$store.state.normalStore.clients.length)
      {
        this.totalClients = this.$store.state.normalStore.clients.length
      }
      else
      {
        axios.get(this.url + 'api/GetClients').then(res => {
          this.totalClients = res.data.length
        })
      }
    },
    getJournals() {
      if(this.$store.state.normalStore.journals.length)
      {
        this.totalJournals = this.$store.state.normalStore.journals.length
      }
      else
      {
        axios.get(this.url + 'api/getJournals').then(res => {
          this.totalJournals = res.data.length
        })
      }
    },
    getRecentStory() {
      axios.get(this.url + 'api/getRecentStory').then(res => {
        this.recentStories = res.data
      })
    },
    getRecentJournals() {
      axios.get(this.url + 'api/getRecentJournals').then(res => {
        this.recentJournals = res.data
      })
    },
    getAllTasks() {
      if(this.$store.state.normalStore.tasks)
      {
        this.tasks = this.$store.state.normalStore.tasks
        this.tasks = this.tasks.map(obj => ({
            ...obj,
            isDue: this.shouldDisplayDue(obj)
          }));
        this.tasks.sort((a, b) => (a.date > b.date) ? 1 : -1)
      }
      else
      {
        axios.get(this.url + 'api/getAllTasks').then(res => {
          this.tasks = res.data
          this.tasks = this.tasks.map(obj => ({
                      ...obj,
                      isDue: this.shouldDisplayDue(obj)
          }));
          this.tasks.sort((a, b) => (a.date > b.date) ? 1 : -1)
        })
      }
    },
    getStoryInReview()
    {
      axios.get(this.url+'api/storyInReview').then(res=>{
        this.dataFetched = true
        if(res.data.length>0)
        {
          this.storyInReview = res.data.length
        }
      })
    },
    shouldDisplayDue(task) {
      if(this.id!=task.story_id)
      {
        this.isDue=0
      }
      this.id = task.story_id
      if (this.isDue == 0 && new Date() > new Date(task.date) && task.status == 2) {
          this.isDue = 1
          return true
      }
      return false
    }
  },

};
</script>

<template>
  <Layout>
    <PageHeader :title="title" />
    <div class="background-theme-image card">
      <div class="card-body bg-transparent py-5">
        <div class="row align-items-center">
          <div class="col">
            <div class="py-3">
              <h2 class="text-white text-center mainStartTitle mb-0" style="font-size:40px;">Welcome to the Submit<span class="text-theme">it</span>
                Dashboard</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body row align-items-center pe-4">
            <div class="col">
              <h2 class="text-theme">{{ totalJournals }}</h2>
              <p class="font-size-16 text-dark font-weight-500 mb-0">Total Journals</p>
            </div>
            <div class="text-center col-auto p-4">
              <div class="avatar-sm mx-auto p-relative overlayEffectCircle overlaySuccess">
                <div class="avatar-sm mx-auto overflow-hidden rounded-circle">
                  <span class="avatar-title bg-success bg-soft">
                    <i class="mdi mdi-book-edit-outline text-success font-size-24"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body row align-items-center pe-4">
            <div class="col ">
              <h2 class="text-theme">{{ totalClients }}</h2>
              <p class="font-size-16 text-dark font-weight-500 mb-0">Total Clients</p>
            </div>
            <div class="text-center col-auto p-4">
              <div class="avatar-sm mx-auto p-relative overlayEffectCircle overlayWarning">
                <div class="avatar-sm mx-auto overflow-hidden rounded-circle">
                  <span class="avatar-title bg-warning bg-soft">
                    <i class="mdi mdi-account-circle text-warning font-size-24"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body row align-items-center pe-4">
            <div class="col ">
              <h2 class="text-theme">{{storyInReview}}</h2>
              <p class="font-size-16 text-dark font-weight-500 mb-0">Stories in Review</p>
            </div>
            <div class="text-center col-auto p-4">
              <div class="avatar-sm mx-auto p-relative overlayEffectCircle overlayPrimary">
                <div class="avatar-sm mx-auto overflow-hidden rounded-circle">
                  <span class="avatar-title bg-primary bg-soft">
                    <i class="mdi mdi-file-document-edit-outline text-primary font-size-24"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Tasks :allTasks="tasks" pageType="dashboard"></Tasks>


  <div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100 mb-0">
        <div class="card-body p-0">
          <div class="p-3 border-bottom">
              <div class="row align-items-center">
                <div class="col">
                  <h2 class="card-title mb-0">Recent Journals</h2>
                </div>
                <div class="col-auto">
                  <router-link to="/journals" class="btn btn-theme btn-sm" v-if="recentJournals.length > 0">View More <i
                      class="mdi mdi-arrow-right ms-1"></i></router-link>
                </div>
              </div>
            </div>
            <div class="p-3">
              <ul class="verti-timeline client-list-card list-unstyled">
                <template v-if="recentJournals.length != 0">
                  <li class="event-list" v-for="(journal, index) in recentJournals" :key="journal.id">
                    <template v-if="index<10">
                      <div class="event-timeline-dot"><i class="bx bx-right-arrow-circle font-size-18"></i></div>
                      <div class="d-flex">
                        <div class="me-3">
                          <h5 class="font-size-14 d-flex dateWdthSet">{{ new Date(journal.created_at).getDate() }} {{ new
                            Date(journal.created_at).toLocaleString('default', { month: 'short' }) }}<i
                              class="bx bx-right-arrow-alt font-size-16 text-theme align-middle ms-2"></i></h5>
                        </div>
                        <div class="flex-grow-1">
                          <div>
                            <h3 class="font-size-15 mb-1">{{ journal.jrnlName }}</h3>
                            <div class="d-flex">
                              <template v-for="rating in parseInt(journal.rating.slice(0, 1))" :key="rating">
                                <i class="mdi mdi-star text-warning"></i>
                              </template>
                              <template v-for="rating in 5 - parseInt(journal.rating.slice(0, 1))" :key="rating">
                                <i class="mdi mdi-star-outline text-warning"></i>
                              </template>
                            </div>
                          </div>
                        </div>
                      </div>
                  </template>
                </li>
              </template>
              <template v-else>
                <p class="text-center">No Recent Journals</p>
              </template>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-4">
      <div class="card h-100 mb-0">
        <div class="card-body p-0">
          <div class="p-3 border-bottom">
            <div class="row align-items-center">
              <div class="col">
                <h2 class="card-title mb-0">Recent Stories</h2>
              </div>
              <div class="col-auto">
                <router-link to="/stories" class="btn btn-theme btn-sm" v-if="recentStories.length != 0">View More <i
                    class="mdi mdi-arrow-right ms-1"></i></router-link>
              </div>
            </div>
          </div>
          <div class="p-3">
            <ul class="verti-timeline client-list-card list-unstyled">
              <template v-if="recentStories.length != 0">
                <li class="event-list" v-for="(story, index) in recentStories" :key="story.id">
                  <template v-if="index<10">
                    <div class="event-timeline-dot"><i class="bx bx-right-arrow-circle font-size-18"></i></div>
                    <div class="d-flex">
                      <div class="me-3">
                        <h5 class="font-size-14 d-flex dateWdthSet"> {{ new Date(story.created_at).getDate() }} {{ new
                          Date(story.created_at).toLocaleString('default', { month: 'short' }) }} <i
                            class="bx bx-right-arrow-alt font-size-16 text-theme align-middle ms-2"></i></h5>
                      </div>
                      <div class="flex-grow-1">
                        <h3 class="font-size-15 mb-1">{{ story.storyTitle }}</h3>
                        <div class="row gx-1 mt-2 align-items-start">
                          <!-- <div class="col-auto">
                                <h4 class="font-size-12 mb-0 line-height-1">{{story.title}}</h4>
                              </div> -->
                          <!-- <div class="col d-flex align-items-center flex-wrap">
                                <span class="p-1 pt-0 font-size-11 d-inline-block line-height-1 labelStyleStory" v-for="(journal, index) in story.journalsList.split(',')" :key="index">{{journal}}</span>
                              </div> -->
                        </div>
                      </div>
                    </div>
                  </template>
                </li>
              </template>
              <template v-else>
                <p class="text-center">No Recent Stories</p>
              </template>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="card">
      <div class="card-body p-0">
        <div class="p-3 border-bottom">
          <div class="row align-items-center">
            <div class="col">
              <h2 class="card-title mb-0">Recent Responses</h2>
            </div>
            <div class="col-auto">
              <router-link to="/allResponse" class="btn btn-theme btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></router-link>
            </div>
          </div>
        </div>
        <div class="p-3 pt-2">
          <router-link to="#" class="d-flex align-items-center border-bottom p-2 card-list-resp">
            <div class="name-email-res d-flex align-items-center">
              <i class="mdi mdi-email font-size-16 text-theme me-2"></i>
              <h4 class="font-size-14 text-dark-cstm mb-0 text-truncate">New Ohio Review</h4>
            </div>
            <div class="subject-main-res">
              <h5 class="font-size-13 text-dark-cstm sub-title-inner">Thanks For sending email</h5>
              <span class="d-inline-block px-1">-</span>
              <p class="text-truncate text-muted sub-desc-inner">Demonstrate the visual form of a document or a typeface without relying on meaningful content</p>
            </div>
            <div class="date-main-res d-flex">
              <h3 class="font-size-13 mb-0 text-dark-cstm"><i class="mdi mdi-account"></i> Sam Campbell</h3>
              <span class="d-inline-block px-1">-</span>
              <p class="mb-0 text-muted"> <i class="mdi mdi-clock"></i> Jan 2</p>
            </div>
          </router-link>
          <router-link to="#" class="d-flex align-items-center border-bottom p-2 card-list-resp">
            <div class="name-email-res d-flex align-items-center">
              <i class="mdi mdi-email font-size-16 text-theme me-2"></i>
              <h4 class="font-size-14 mb-0 text-dark-cstm text-truncate">Bellingham Review</h4>
            </div>
            <div class="subject-main-res">
              <h5 class="font-size-13 text-dark-cstm sub-title-inner">Thanks For sending email</h5>
              <span class="d-inline-block px-1">-</span>
              <p class="text-truncate text-muted sub-desc-inner">Demonstrate the visual form of a document or a typeface without relying on meaningful content</p>
            </div>
            <div class="date-main-res d-flex">
              <h3 class="font-size-13 mb-0 text-dark-cstm"><i class="mdi mdi-account"></i> Neal Burnham</h3>
              <span class="d-inline-block px-1">-</span>
              <p class="mb-0 text-muted"> <i class="mdi mdi-clock"></i> Jan 2</p>
            </div>
          </router-link>
          <router-link to="#" class="d-flex align-items-center border-bottom p-2 card-list-resp">
            <div class="name-email-res d-flex align-items-center">
              <i class="mdi mdi-email font-size-16 text-theme me-2"></i>
              <h4 class="font-size-14 mb-0 text-dark-cstm text-truncate">The Headlight Review</h4>
            </div>
            <div class="subject-main-res">
              <h5 class="font-size-13 text-dark-cstm sub-title-inner">Thanks For sending email</h5>
              <span class="d-inline-block px-1">-</span>
              <p class="text-truncate text-muted sub-desc-inner">Demonstrate the visual form of a document or a typeface without relying on meaningful content</p>
            </div>
            <div class="date-main-res d-flex">
              <h3 class="font-size-13 mb-0 text-dark-cstm"><i class="mdi mdi-account"></i> Jarvis Coffin</h3>
              <span class="d-inline-block px-1">-</span>
              <p class="mb-0 text-muted"> <i class="mdi mdi-clock"></i> Jan 2</p>
            </div>
          </router-link>
          <router-link to="#" class="d-flex align-items-center border-bottom p-2 card-list-resp">
            <div class="name-email-res d-flex align-items-center">
              <i class="mdi mdi-email font-size-16 text-theme me-2"></i>
              <h4 class="font-size-14 mb-0 text-dark-cstm text-truncate">The Woodward Review</h4>
              </div>
              <div class="subject-main-res">
                <h5 class="font-size-13 text-dark-cstm sub-title-inner">Thanks For sending email</h5>
                <span class="d-inline-block px-1">-</span>
                <p class="text-truncate text-muted sub-desc-inner">Demonstrate the visual form of a document or a typeface without relying on meaningful content</p>
              </div>
              <div class="date-main-res d-flex">
                <h3 class="font-size-13 mb-0 text-dark-cstm"><i class="mdi mdi-account"></i> Neal Burnham</h3>
                <span class="d-inline-block px-1">-</span>
                <p class="mb-0 text-muted"> <i class="mdi mdi-clock"></i> Jan 2</p>
              </div>
            </router-link> 
            <router-link to="#" class="d-flex align-items-center border-bottom p-2 card-list-resp">
              <div class="name-email-res d-flex align-items-center">
                <i class="mdi mdi-email font-size-16 text-theme me-2"></i>
                <h4 class="font-size-14 mb-0 text-dark-cstm text-truncate">The Dillydoun Review</h4>
              </div>
              <div class="subject-main-res">
                <h5 class="font-size-13 text-dark-cstm sub-title-inner">Thanks For sending email</h5>
                <span class="d-inline-block px-1">-</span>
                <p class="text-truncate text-muted sub-desc-inner">Demonstrate the visual form of a document or a typeface without relying on meaningful content</p>
              </div>
              <div class="date-main-res d-flex">
                <h3 class="font-size-13 mb-0 text-dark-cstm"><i class="mdi mdi-account"></i> Amy Cacciola</h3>
                <span class="d-inline-block px-1">-</span>
                <p class="mb-0 text-muted"> <i class="mdi mdi-clock"></i> Jan 2</p>
              </div>
            </router-link>
            <router-link to="#" class="d-flex align-items-center border-bottom p-2 card-list-resp">
              <div class="name-email-res d-flex align-items-center">
                <i class="mdi mdi-email font-size-16 text-theme me-2"></i>
                <h4 class="font-size-14 mb-0 text-dark-cstm text-truncate">Reed Magazine</h4>
              </div>
              <div class="subject-main-res">
                <h5 class="font-size-13 text-dark-cstm sub-title-inner">Thanks For sending email</h5>
                <span class="d-inline-block px-1">-</span>
                <p class="text-truncate text-muted sub-desc-inner">Demonstrate the visual form of a document or a typeface without relying on meaningful content</p>
              </div>
              <div class="date-main-res d-flex">
                <h3 class="font-size-13 mb-0 text-dark-cstm"><i class="mdi mdi-account"></i> Jarvis Coffin</h3>
                <span class="d-inline-block px-1">-</span>
                <p class="mb-0 text-muted"> <i class="mdi mdi-clock"></i> Jan 2</p>
              </div>
            </router-link>
            <router-link to="#" class="d-flex align-items-center border-bottom p-2 card-list-resp">
              <div class="name-email-res d-flex align-items-center">
                <i class="mdi mdi-email font-size-16 text-theme me-2"></i>
                <h4 class="font-size-14 mb-0 text-dark-cstm text-truncate">Stoneboat Literary Journal</h4>
              </div>
              <div class="subject-main-res">
                <h5 class="font-size-13 text-dark-cstm sub-title-inner">Thanks For sending email</h5>
                <span class="d-inline-block px-1">-</span>
                <p class="text-truncate text-muted sub-desc-inner">Demonstrate the visual form of a document or a typeface without relying on meaningful content</p>
              </div>
              <div class="date-main-res d-flex">
                <h3 class="font-size-13 mb-0 text-dark-cstm"><i class="mdi mdi-account"></i> Amy Cacciola</h3>
                <span class="d-inline-block px-1">-</span>
                <p class="mb-0 text-muted"> <i class="mdi mdi-clock"></i> Jan 2</p>
              </div>
            </router-link>
            <router-link to="#" class="d-flex align-items-center border-bottom p-2 card-list-resp">
              <div class="name-email-res d-flex align-items-center">
                <i class="mdi mdi-email font-size-16 text-theme me-2"></i>
                <h4 class="font-size-14 mb-0 text-dark-cstm text-truncate">Rock and a Hard Place</h4>
              </div>
              <div class="subject-main-res">
                <h5 class="font-size-13 text-dark-cstm sub-title-inner">Thanks For sending email</h5>
                <span class="d-inline-block px-1">-</span>
                <p class="text-truncate text-muted sub-desc-inner">Demonstrate the visual form of a document or a typeface without relying on meaningful content</p>
              </div>
              <div class="date-main-res d-flex">
                <h3 class="font-size-13 mb-0 text-dark-cstm"><i class="mdi mdi-account"></i> Jarvis Coffin</h3>
                <span class="d-inline-block px-1">-</span>
                <p class="mb-0 text-muted"> <i class="mdi mdi-clock"></i> Jan 2</p>
              </div>
            </router-link>
          </div>
        </div>
      </div> -->
  </Layout>
</template>
