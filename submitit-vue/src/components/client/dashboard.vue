<script>
import Layout from "../../router/layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import axios from 'axios';
import CryptoJS from 'crypto-js';

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
            title: "Dashboard",
            url: process.env.VUE_APP_URL,
            totalClients: 0,
            client: [],
            clientStories: [],
            headers: [
                { text: "Story Title", value: "storyTitle" },
                { text: "Service", value: "serviceTbl" },
                { text: "Story Genre", value: "storyGenre" },
                { text: "Word Count", value: "story.wordCount" },
                // { text: "Status", value: "statusTble"},
                { text: "Submission Results", value: "subResult" },
            ],
            tableItems: [],
            searchValueTable: "",
            storyStatus : [],
            userType: null,
            customPackage: ["price_1N90ymSGmK7KROFRZP7BnS5g", "price_1N90zySGmK7KROFRfWPL7fVn", "price_1N910nSGmK7KROFRFEsmDeW0"]
        }
    },
    components: {
        Layout,
        PageHeader,
        EasyDataTable
    },
    created() {
        this.userType = atob(decodeURIComponent(JSON.parse(localStorage.getItem('customUser'))))
        this.getSingleClient()
        this.getStoryStatus()
    },
    activated() {
        this.userType = atob(decodeURIComponent(JSON.parse(localStorage.getItem('customUser'))))
        this.getSingleClient();
        this.getStoryStatus()
    },
    methods: {
        getSingleClient() {
            let data = new FormData()
            data.append('id', atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId')))))
            axios.post(this.url + 'api/getSingleClient', data).then(res => {
                this.client = res.data.client
                this.clientStories = res.data.clientStories
                this.getClientStorySubmissionResult()
            })
        },
        getClientStorySubmissionResult()
        {
            axios.post(this.url+'api/getClientStorySubmissionResult', {'id':atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId'))))}).then(res=>{
                this.tableItems = res.data
            })
        },
        getStoryStatus()
        {   
            axios.post(this.url+'api/getStoryStatus', {'user_id':atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId'))))}).then(res=>{
                this.storyStatus = res.data
            })
        },
        encode(id) {
            return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
        }
    }
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
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body pt-4">
                        <!-- <h2 class="card-title">Client Overview</h2> -->
                        <div class="mainButtonEdit">
                            <router-link to="/setting" class="editButtonIcon buttonPosEdit"><i
                                    class="mdi mdi-pencil"></i></router-link>
                        </div>
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <div class="avatar-lg mb-3 mx-auto">
                                    <img :src="`https://ui-avatars.com/api/?name=${String(this.client.firstName).charAt(0).toUpperCase() + String(this.client.lastName).charAt(0).toUpperCase()}&background=3aafa9&size=200`"
                                        alt="" class="img-thumbnail fullCoverImage rounded-circle" />
                                </div>
                                <div class="card-client-info text-center">
                                    <h2>{{ client.firstName }} {{ client.lastName }}</h2>
                                    <p class="text-muted font-size-14">{{ client.email }}</p>
                                </div>
                                <div class="taskcountinfo border-top pt-3 mb-0">
                                    <h2>Payment History</h2>
                                    <div class="text-center mt-3 pt-3">
                                        <router-link to="/invoice" class="btn btn-theme">View Invoice</router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="main-group-info">
                                    <div class="inner-group-info border-bottom pb-2 mb-2">
                                        <label class="text-muted">Email:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.email }}</h3>
                                    </div>
                                    <div class="inner-group-info border-bottom pb-2 mb-2" v-if="!customPackage.includes(userType)">
                                        <label class="text-muted">Shared Email:</label>
                                        <h3 class="card-title font-weight-500 mb-0" v-if="client.sharedEmail">{{ client.sharedEmail }}</h3>
                                        <h3 class="card-title font-weight-500 mb-0" v-else>NULL</h3>
                                    </div>
                                    <div class="inner-group-info border-bottom pb-2 mb-2">
                                        <label class="text-muted">Phone:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.phone }}</h3>
                                    </div>
                                    <div class="inner-group-info border-bottom pb-2 mb-2">
                                        <label class="text-muted">Date:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.date }}</h3>
                                    </div>
                                    <div class="inner-group-info">
                                        <label class="text-muted">Address:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.street }}, {{ client.city }},
                                            {{ client.state }} {{ client.zipCode }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Total Stories</p>
                                        <h4 class="mb-0">{{ clientStories.length }}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-theme">
                                            <span class="avatar-title bg-theme">
                                                <i class="mdi mdi-file-document-edit font-size-20"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Active Stories</p>
                                        <h4 class="mb-0">{{storyStatus.active}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-theme">
                                            <span class="avatar-title bg-theme">
                                                <i class="mdi mdi-file-certificate-outline font-size-20"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Journals: Accepted</p>
                                        <h4 class="mb-0">{{storyStatus.accepted}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-theme">
                                            <span class="avatar-title bg-theme">
                                                <i class="mdi mdi-book-check-outline font-size-20"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Journals: Pending</p>
                                        <h4 class="mb-0">{{storyStatus.pending}}</h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-theme">
                                        <span class="avatar-title bg-theme">
                                            <i class="mdi mdi-file-clock-outline  font-size-20"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Journals: Rejected</p>
                                        <h4 class="mb-0">{{storyStatus.rejected}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-theme">
                                            <span class="avatar-title bg-theme">
                                                <i class="mdi mdi-file-cancel-outline font-size-20"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <p class="text-muted fw-medium">Journals: Withdrawn</p>
                                        <h4 class="mb-0">{{storyStatus.withdrawn}}</h4>
                                    </div>

                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-theme">
                                            <span class="avatar-title bg-theme">
                                                <i class="mdi mdi-file-clock-outline font-size-20"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class=" mb-3">
                    <div class="row gx-2">
                        <div class="col">
                            <h3 class="my-1 font-size-18 text-dark-cstm">Submission Results</h3>
                        </div>
                        <div class="col-auto">
                            <div class="topBarCstmTable searchBarStyle position-relative">
                                <input type="text" v-model="searchValueTable" class="form-control" placeholder="Search...">
                                <span class="iconSearch bx bx-search-alt"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <EasyDataTable :headers="headers" :items="tableItems" :search-value="searchValueTable" border-cell
                    theme-color="#3aafa9" :rows-per-page="25" buttons-pagination table-class-name="table-custom-style">
                    <template #item-storyTitle="{ story }">
                        <router-link :to="`/story_information/${encode(story.id)}`" class="text-theme">{{ story.storyTitle }}</router-link>
                    </template>
                    <template #item-storyGenre="{ story }">
                        <span v-if="story.storyGenre=='fiction'">Fiction</span>
                        <span v-else>Non-Fiction</span>
                    </template>
                    <template #item-serviceTbl="{   }">
                        <span>Submission</span>
                    </template>
                    <!-- <template #item-lastTask="{lastTaskTitle, lastTaskTime}">
                                                          <span class="d-block text-muted"><strong class="font-weight-500 text-dark-cstm">{{lastTaskTitle}}</strong></span>
                                                          <span class="d-flex text-muted"><i class="mdi mdi-calendar me-1"></i> {{lastTaskTime}}</span>
                                                        </template>
                                                        <template #item-nextTask="{nextTaskTitle, nextTaskTime}">
                                                          <span class="d-block text-muted"><strong class="font-weight-500 text-dark-cstm">{{nextTaskTitle}}</strong></span>
                                                          <span class="d-flex text-muted"><i class="mdi mdi-calendar me-1"></i> {{nextTaskTime}}</span>
                                                        </template> -->
                    <template #item-subResult="{ story }">
                        <span class="ms-auto d-flex">
                            <router-link :to="`/story_information/${encode(story.id)}`" class="btn btn-sm btn-soft-info">
                                <i class="mdi mdi-eye me-1"></i> View Result
                            </router-link>
                        </span>
                    </template>
                </EasyDataTable>
            </div>
        </div>
    </Layout>
</template>


