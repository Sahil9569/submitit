<template>
    <Layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title" />
            </div>
            <div class="col-auto">
                <router-link to="/client" class="btn btn-theme">
                    <i class="mdi mdi-arrow-left font-size-14 align-middle me-2"></i>
                    Back to Client List
                </router-link>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body pt-4">
                        <!-- <h2 class="card-title">Client Overview</h2> -->
                        <div class="mainButtonEdit">
                            <router-link :to="`/edit_client/${encode(client.id)}`" class="editButtonIcon buttonPosEdit"><i
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
                                    <h2>Notes:</h2>
                                    <p class="text-center">{{client.notes}}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="main-group-info">
                                    <div class="inner-group-info border-bottom pb-2 mb-2">
                                        <label class="text-muted">Email:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.email }}</h3>
                                    </div>
                                    <div class="inner-group-info border-bottom pb-2 mb-2">
                                        <label class="text-muted">Shared Email:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.sharedEmail }}</h3>
                                    </div>
                                    <div class="inner-group-info border-bottom pb-2 mb-2">
                                        <label class="text-muted">Shared Email Password:</label>
                                        <h3 class="card-title font-weight-500 mb-0">{{ client.sharedEmailPassword }}</h3>
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
                                        <p class="text-muted fw-medium">Client Total Stories</p>
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
                                        <p class="text-muted fw-medium">Client Active Stories</p>
                                        <h4 class="mb-0">{{ storyStatus.active }}</h4>
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
                                        <h4 class="mb-0">{{ storyStatus.accepted }}</h4>
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
                                        <h4 class="mb-0">{{ storyStatus.pending }}</h4>
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
                                        <h4 class="mb-0">{{ storyStatus.rejected }}</h4>
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
                                        <h4 class="mb-0">{{ storyStatus.withdrawn }}</h4>
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
        <div class="row" v-if="clientStories.length > 0">
            <div class="mt-4">
                <h2 class="card-title">All Stories Details</h2>
                <Carousel class="arrow-style-cstm" :settings="settings" ref="carousel" v-model="currentSlide">
                    <Slide v-for="slide in clientStories" :key="slide.id">
                        <div class="carousel__item">
                            <div class="card">
                                <div class="card-body mainCardStoryclnt">
                                    <div class="row text-start">
                                        <div class="col-md-4">
                                            <div class="text-start contentCardStory">
                                                <div class="clntInnerText1">
                                                    <h6 class="text-theme mb-0 font-weight-400"><span
                                                            v-if="slide.storyGenre == 'fiction'">Fiction</span><span
                                                            v-else>Non-Fiction</span></h6>
                                                    <h4 class="card-title text-start font-weight-500"><router-link
                                                            :to="`/single_story/${encode(slide.id)}`"
                                                            class="text-link-cstm">{{ slide.storyTitle }}</router-link></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-2 border">
                                                <h4 class="mb-2 d-flex font-size-14" v-if="slide.lastTask"><span
                                                        class="text-no-wrap">Last Task :</span> <span
                                                        class="ms-1 text-truncate">{{ slide.lastTask.title }}</span></h4>
                                                <h4 class="mb-2 d-flex font-size-14" v-else><span class="text-no-wrap">Last
                                                        Task :</span> <span class="ms-1 text-truncate">Nothing</span></h4>
                                                <h5 class="mb-0 d-flex font-size-14" v-if="slide.lastTask"><span
                                                        class="text-no-wrap">Last Date :</span> <span class="ms-1">{{ new
                                                            Date(slide.lastTask.date).getDate() }} {{ new
        Date(slide.lastTask.date).toLocaleString('en-US', {
            month: 'short'
        }) }}, {{ new
        Date(slide.lastTask.date).getFullYear() }}</span></h5>
                                                <h5 class="mb-0 d-flex font-size-14" v-else><span class="text-no-wrap">Last
                                                        Date :</span> <span class="ms-1">Nothing</span></h5>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-2 border">
                                                <h4 class="mb-2 d-flex font-size-14" v-if="slide.nextTask"><span
                                                        class="text-no-wrap">Next Task :</span> <span
                                                        class="ms-1 text-truncate">{{ slide.nextTask.title }}</span></h4>
                                                <h4 class="mb-2 d-flex font-size-14" v-else><span class="text-no-wrap">Next
                                                        Task :</span> <span class="ms-1 text-truncate">Nothing</span></h4>
                                                <h5 class="mb-0 d-flex font-size-14" v-if="slide.nextTask"><span
                                                        class="text-no-wrap">Next Date :</span> <span class="ms-1"> {{ new
                                                            Date(slide.nextTask.date).getDate() }} {{ new
        Date(slide.nextTask.date).toLocaleString('en-US', {
            month: 'short'
        }) }}, {{ new
        Date(slide.nextTask.date).getFullYear() }}</span></h5>
                                                <h5 class="mb-0 d-flex font-size-14" v-else><span class="text-no-wrap">Next
                                                        Date :</span> <span class="ms-1">Nothing</span></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Slide>
                    <template #addons>
                        <Navigation />
                    </template>
                </Carousel>
            </div>
        </div>
        <div class="card ">
            <div class="card-body">
                <div class="row mb-3 align-items-center">
                    <div class="col">
                        <h2 class="card-title font-size-17">All Tasks</h2>
                    </div>
                </div>
                <Tasks :allTasks="getStoryTask" pageType="client" @updateTask="getAllTasks"></Tasks>
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
                    <template #item-journalMain="{ journal }">
                        <span class="text-dark-cstm d-block">
                            {{ journal.jrnlName }}
                        </span>
                        <div class="d-flex">
                            <template v-for="jRating in parseInt(journal.rating.slice(0, 1))" :key="jRating">
                                <i :class="`mdi mdi-star font-size-13 text-warning`"></i>
                            </template>
                            <template v-for="jRating in 5 - parseInt(journal.rating.slice(0, 1))" :key="jRating">
                                <i :class="`mdi mdi-star-outline font-size-13 text-warning`"></i>
                            </template>
                        </div>

                    </template>
                    <template #item-storyTitle="{ story }">
                        <router-link :to="`/single_story/${encode(story.id)}`" class="text-link-cstm">
                            {{ story.storyTitle }}
                        </router-link>

                    </template>
                    <template #item-replyStatus="{ status }">
                        <span class="text-primary" v-if="status == 0">Pending</span>
                        <span class="text-primary" v-if="status == 1">Active</span>
                        <span class="text-danger" v-if="status == 2">Withdrawn</span>
                        <span class="text-warning" v-if="status == 3">Rejected</span>
                        <span class="text-success" v-if="status == 4">Accepted</span>
                    </template>
                    <template #item-dateSubMail="{ created_at }">
                        <div class="">
                            <p class="mb-0 text-muted"><i class="mdi mdi-calendar"></i> {{ new
                                Date(created_at).getDate() }} {{ new
        Date(created_at).toLocaleString('en-US', { month: 'short' }) }}, {{ new
        Date(created_at).getFullYear() }}</p>
                        </div>
                    </template>
                    <template #item-dateResMail="{ updated_at, status }">
                        <!-- <p class="mb-0 text-muted"><i class="mdi mdi-calendar"></i> {{ new
                            Date(updated_at).getDate() }} {{ new
        Date(updated_at).toLocaleString('en-US', { month: 'short' }) }}, {{ new
        Date(updated_at).getFullYear() }}</p> -->
                        <div v-if="status == 0">
                        </div>
                        <div v-else>
                            {{ new Date(updated_at).getDate() }}
                            {{
                                new Date(updated_at).toLocaleString("en-US", { month: "short" })
                            }}, {{ new Date(updated_at).getFullYear() }}
                        </div>
                    </template>
                    <!-- <template #item-journalMain="{ journalName, journalUrl, Jrating }">
                        <router-link :to="journalUrl"  class="text-theme">
                            {{journalName}} 
                        </router-link>
                        <div class="d-flex">
                            <template v-for="(Jratings,index) in Jrating.length" :key="index">
                                <i :class="`mdi ${Jrating[index]} font-size-13 text-warning`" ></i>
                            </template>
                        </div>
                    </template>  -->
                </EasyDataTable>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import { Carousel, Slide, Navigation } from 'vue3-carousel'
import 'vue3-carousel/dist/carousel.css'
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import axios from 'axios';
import CryptoJS from 'crypto-js';
import Tasks from './sub-components/tasks.vue';
// import Datepicker from "vue3-datepicker"; 
// import { ref } from "vue";
// import datetime from "vuejs-datetimepicker-translatable";

export default ({

    page: {
        title: "Client Information",
        meta: [
            {
                name: "description",
                content: 'description',
            },
        ],
    },
    data() {
        return {
            title: "Client Information",
            url: process.env.VUE_APP_URL,
            dataFetched: false,
            client: [],
            clientStories: [],
            tasks: [],
            currentSlide: 0,
            pickeddd: new Date(),
            dateselecttask: new Date(),
            todayTask: null,
            automatedTask: null,
            storyStatus: [],
            settings: {
                itemsToShow: 1,
                snapAlign: 'start',
            },
            headers: [
                { text: 'Journal name', value: "journalMain" },
                { text: 'Story name', value: "storyTitle" },
                { text: 'Reply ', value: "replyStatus" },
                { text: 'Submission Date', value: "dateSubMail" },
                { text: 'Response Date', value: "dateResMail" },
            ],
            tableItems: [],
            searchValueTable: "",
            sliderContent: [],
            isDue: 0,
            id: null
        }
    },
    components: {
        Layout,
        PageHeader,
        Carousel,
        Slide,
        Tasks,
        EasyDataTable,
        Navigation
    },
    beforeMount() {
        this.getSingleClient()
        this.getClientStoryTasks()
        this.getAllTasks()
        this.getClientStorySubmissionResult()
    },
    watch: {
        $route() {
            this.getSingleClient()
            this.getClientStoryTasks()
            this.getAllTasks()
            this.getClientStorySubmissionResult()
        },
    },
    methods: {
        getSingleClient() {
            if(this.$store.state.normalStore.clients.length)
            {
                let client = this.$store.state.normalStore.clients.find(item => item.id == this.decode(this.$route.params.id));
                let clientStories = this.$store.state.normalStore.stories.filter(item => item.user_id == this.decode(this.$route.params.id));
                this.client = client;
                clientStories.forEach((story, key) => {
                    clientStories[key]['lastTask'] = this.$store.state.normalStore.tasks
                        .filter(task => task.story_id == story.id && task.status == 1)
                        .sort((a, b) => b.updated_at.localeCompare(a.updated_at))
                        .shift();

                    clientStories[key]['nextTask'] = this.$store.state.normalStore.tasks
                        .filter(task => task.story_id == story.id && task.status == 2)
                        .sort((a, b) => a.date.localeCompare(b.date))
                        .shift();
                });
                this.clientStories = clientStories;
                this.getStoryStatus()
            }
            else
            {
                let data = new FormData()
                data.append('id', this.decode(this.$route.params.id))
                axios.post(this.url + 'api/getSingleClient', data).then(res => {
                    this.client = res.data.client
                    this.clientStories = res.data.clientStories
                    this.getStoryStatus()
                })
            }
        },
        getStoryStatus() {
            if(this.$store.state.normalStore.clients.length)
            {
                this.storyStatus['pending'] = (this.$store.state.normalStore.storySubmission.filter(item => ((item.user_id == this.decode(this.$route.params.id)) && item.status==0))).length
                this.storyStatus['accepted'] = (this.$store.state.normalStore.storySubmission.filter(item => ((item.user_id == this.decode(this.$route.params.id)) && item.status==1))).length
                this.storyStatus['withdrawn'] = (this.$store.state.normalStore.storySubmission.filter(item => ((item.user_id == this.decode(this.$route.params.id)) && item.status==2))).length
                this.storyStatus['rejected'] = (this.$store.state.normalStore.storySubmission.filter(item => ((item.user_id == this.decode(this.$route.params.id)) && item.status==3))).length
                this.storyStatus['active'] = (this.$store.state.normalStore.storySubmission.filter(item => ((item.user_id == this.decode(this.$route.params.id)) && item.status==4))).length
            }
            else
            {
                axios.post(this.url + 'api/getStoryStatus', { 'user_id': this.decode(this.$route.params.id) }).then(res => {
                    this.storyStatus = res.data
                })
            }
        },
        getClientStoryTasks() {
            if(this.$store.state.normalStore.clients.length)
            {
                let storyIds = this.clientStories.map(obj=> obj.id)
                this.tasks = this.$store.state.normalStore.tasks.filter(item=>storyIds.includes(item.story_id));
                this.tasks = this.tasks.map(obj => ({
                    ...obj,
                    isDue: this.shouldDisplayDue(obj)
                }));
                this.tasks.sort((a, b) => (a.date > b.date) ? 1 : -1)
            }
            else
            {
                axios.post(this.url + 'api/getClientStoryTasks', { 'id': this.decode(this.$route.params.id) }).then(res => {
                    this.tasks = res.data
                    this.tasks = this.tasks.map(obj => ({
                        ...obj,
                        isDue: this.shouldDisplayDue(obj)
                    }));
                    this.tasks.sort((a, b) => (a.date > b.date) ? 1 : -1)
                })
            }
        },
        getAllTasks() {
            let tasksData=[]
            if(this.$store.state.normalStore.clients.length)
            {
                tasksData = this.$store.state.normalStore.tasks.filter(item=>item.user_id==this.decode(this.$route.params.id));
                this.todayTask = tasksData.filter(task => (new Date().toLocaleDateString("en-US") == new Date(task.date).toLocaleDateString("en-US")))
                this.automatedTask = tasksData.filter(task => task.type == 'Automate' && (new Date().toLocaleDateString("en-US") == new Date(task.date).toLocaleDateString("en-US")))
            }
            else
            {
                axios.get(this.url + 'api/getAllTasks').then(res => {
                    let tasks = res.data
                    tasks = tasks.map(obj => ({
                        ...obj,
                        isDue: this.shouldDisplayDue(obj)
                    }));
                    tasksData = tasks.filter(task => task.user_id == this.decode(this.$route.params.id));
                    this.todayTask = tasksData.filter(task => (new Date().toLocaleDateString("en-US") == new Date(task.date).toLocaleDateString("en-US")))
                    this.automatedTask = tasksData.filter(task => task.type == 'Automate' && (new Date().toLocaleDateString("en-US") == new Date(task.date).toLocaleDateString("en-US")))
                })
            }
        },
        shouldDisplayDue(task) {
            if (this.id !== task.story_id) {
                this.isDue = 0
            }
            this.id = task.story_id
            if (this.isDue == 0 && new Date() > new Date(task.date) && task.status == 2) {
                this.isDue = 1
                return true
            }
            return false
        },
        getClientStorySubmissionResult() {
            if(this.$store.state.normalStore.clients.length)
            {
                this.tableItems = this.$store.state.normalStore.storySubmission
                .filter(item => item.user_id == this.decode(this.$route.params.id))
                .map(item => {
                    return {
                        ...item,
                        journal: this.$store.state.normalStore.journals.find(journal => journal.id == item.journal_id),
                        story: this.$store.state.normalStore.stories.find(story => story.id == item.story_id)
                    };
                });

            }
            else
            {
                axios.post(this.url + 'api/getClientStorySubmissionResult', { id: this.decode(this.$route.params.id) }).then(res => {
                    this.tableItems = res.data
                    this.dataFetched = true
                })
            }
        },
        decode(id) {
            return decodeURIComponent(CryptoJS.AES.decrypt(String(id), "Secret Passphrase").toString(CryptoJS.enc.Utf8));
        },
        encode(id) {
            return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
        }
    },
    computed:{
        getStoryTask() {
            return this.tasks.filter((task) => task.story_id == this.clientStories[this.currentSlide].id)
        }
    }
})
</script>
<style scoped>
.carousel__slide {
    padding: 10px;
}

.carousel__item {
    width: 100%;
}
</style>