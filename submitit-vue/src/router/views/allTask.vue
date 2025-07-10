<template>
    <layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title" />
            </div>
            <!-- <div class="col-auto">
                <button @click="modalTaskTotalAdd = true" type="button" class="btn btn-theme">
                    + Add Task
                </button>
            </div> -->
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="genreLitery" class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                            <input type="radio" v-model="defaultShowTaskType" name="LiteryFiction" @change="filterTasks"
                                class="inputRadioCstm d-none" id="genreLitery" :value="null"><span
                                class="radioCircleIcon"><i class="mdi mdi-circle-outline text-theme font-size-20"></i><i
                                    class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i></span>
                            <h4 class="font-size-12 mb-0 ms-2">All</h4>
                        </label>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="genreNonLiteryManual" class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                            <input type="radio" v-model="defaultShowTaskType" @change="filterTasks" 
                                name="LiteryFiction" value="Manual" class="inputRadioCstm d-none"
                                id="genreNonLiteryManual"><span class="radioCircleIcon"><i
                                    class="mdi mdi-circle-outline text-theme font-size-20"></i><i
                                    class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i></span>
                            <h4 class="font-size-12 mb-0 ms-2">Manual</h4>
                        </label>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="genreNonLitery" class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                            <input type="radio" v-model="defaultShowTaskType" @change="filterTasks"  name="LiteryFiction"
                                value="Automate" class="inputRadioCstm d-none" id="genreNonLitery"><span
                                class="radioCircleIcon"><i class="mdi mdi-circle-outline text-theme font-size-20"></i><i
                                    class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i></span>
                            <h4 class="font-size-12 mb-0 ms-2">Automated</h4>
                        </label>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="genreNonLiteryDue" class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                            <input type="radio" v-model="defaultShowTaskType" @change="filterTasks" 
                                name="LiteryFiction" value="Due" class="inputRadioCstm d-none"
                                id="genreNonLiteryDue"><span class="radioCircleIcon"><i
                                    class="mdi mdi-circle-outline text-theme font-size-20"></i><i
                                    class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i></span>
                            <h4 class="font-size-12 mb-0 ms-2">Due</h4>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <b-modal v-model="modalTaskTotalAdd" id="modalTaskTotalAdd" centered scrollable size="md" title="Update Task"
            title-class="font-18" hide-footer>
            <form>
                <div class="mainFormModal">
                    <div class="mb-3">
                        <label class="form-label">Client Name</label>
                        <div class="single-select2-cstm">
                            <Select2 v-model="clientName" placeholder="Select Client Name" :options="clientNameAddOption" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="task-title-input">Task Title</label>
                        <input id="task-title-input" v-model="form.title" type="text" class="form-control"
                            placeholder="Type Task Title">
                    </div>
                    <div class="mb-3">
                        <label>Duration</label>
                        <div class="input-group w-100 inputFieldDate">
                            <input id="task-title-input" v-model="form.duration" type="number" class="form-control"
                                placeholder="In Days">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="task-title-input">Task Type</label>
                        <select class="form-select" v-model="form.type">
                            <option value="Manual">Manual</option>
                            <option value="Automate">Automate</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="task-title-input">Select Email Template</label>
                        <select class="form-select" v-model="form.email_template_id">
                            <option selected value="">No Email Template</option>
                            <option v-for="template in emailTemplates" :key="template.id" :value="template.id">
                                {{ template.title }}</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end border-top pt-3">
                        <button type="submit" @click.prevent="updateTask()" class="btn btn-theme">Submit</button>
                    </div>
                </div>
            </form>
        </b-modal>
        <div class="card">
            <div class="card-body">
                <template v-if="allTasks.length > 0">
                    <div class="card task-box-custom mb-3" v-for="task in allTasks" :key="task.id"
                        :class="`${task.status != 1 ? 'item' : null}`">
                        <div class="card-body p-relative px-lg-3 px-2 pt-4">
                            <span class="bg-theme text-white p-1 tagLabelCstm line-height-1 d-inline-block">{{ task.type
                            }}</span>
                            <div class="innerTaskMainLst">
                                <div class="row flex-nowrap align-items-center">
                                    <div class="col-9">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-theme-soft squareListTask text-theme">
                                                {{ new Date(task.date).getHours() > 12 ? '0' + (new
                                                    Date(task.date).getHours() - 12) : new Date(task.date).getHours()
                                                }}<br>{{ new Date(task.date).getHours() > 12 ? 'PM' : 'AM' }}
                                            </span>
                                            <div class="row gx-2 justify-content-between align-items-center w-100">
                                                <div class="col-md-7">
                                                    <div class="d-flex flex-wrap align-items-center">
                                                        <p class="text-muted mb-0 me-2"><i
                                                                class="mdi mdi-calendar-month-outline me-1"></i>{{ new
                                                                    Date(task.date).getDate() }} {{ new
                                                                    Date(task.date).toLocaleString('en-US', { month: 'short' })    }},
                                                            {{ new Date(task.date).getFullYear() }}</p>
                                                        <span class="badge rounded-pill badge-soft-success font-size-11"
                                                            v-if="task.status == 1">Task Done</span>
                                                        <span class="badge rounded-pill badge-soft-danger font-size-11"
                                                            v-else-if="task.isDue">Due</span>
                                                        <span class="badge rounded-pill badge-soft-warning font-size-11"
                                                            v-else>Upcoming</span>
                                                    </div>
                                                    <h5 class="taskTitleCstm">{{ task.title }}</h5>
                                                </div>
                                                <div class="col-md-5 ">
                                                    <div class="row gx-3">
                                                        <div class="col-auto">
                                                            <router-link :to="`/single_client/${encode(task.user_id)}`"
                                                                class="mb-0 text-theme"><i class="mdi mdi-account me-1"></i>
                                                                {{ task.firstName
                                                                }}
                                                                {{ task.lastName }}</router-link>
                                                        </div>
                                                        <div class="col-auto">
                                                            <router-link :to="`/single_story/${encode(task.story_id)}`"
                                                                class="mb-0 text-theme"><i
                                                                    class="me-1 mdi mdi-file-document-outline"></i>
                                                                {{ task.storyTitle }}</router-link>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-3" v-if="task.status != 1">
                                        <span class="justify-content-end ms-auto d-flex">
                                            <a href="#" @click.prevent="editTask(task.id)" class="btn btn-sm btn-soft-info">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </a>
                                            <!-- <a  href="#" class="btn btn-sm btn-soft-info ms-1">
                                                <i class="mdi mdi-send"></i>
                                            </a> -->
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <p class="text-center">No Task Scheduled Yet</p>
                </template>
            </div>
        </div>

    </layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/page-header";
import { ref } from "vue";
import Select2 from 'vue3-select2-component';
import axios from "axios";
import CryptoJS from 'crypto-js';
/**
 * Tabs & accordions component
 */
export default {
    page: {
        title: "All Tasks",
        meta: [{ name: "description" }]
    },
    components: {
        Layout,
        PageHeader,
        Select2
    },
    data() {
        return {
            title: "All Tasks",
            form: {
                title: "",
                date: new Date(),
                duration: '',
                type: [],
                email_template_id: ''
            },
            url: process.env.VUE_APP_URL,
            dataFetched: false,
            // pickeddd: new Date(),
            // emailTemplates:[],
            clientName: [],
            clientNameAddOption: [],
            dateselecttask: new Date(),
            enabled: true,
            dragging: false,
            allTasks: [],
            unmutableAllTasks: [],
            defaultShowTaskType: 'Due',
            editId: null,
            enabledDate: new Date(),
            isDue: 0,
            id: null
        };
    },
    mounted() {
        if (!this.dataFetched) {
            this.getAllTasks()
            this.getEmailTemplates();
        }
    },
    activated() {
        if (this.dataFetched) {
            this.getAllTasks()
        }
    },
    methods: {
        getAllTasks() {
            axios.get(this.url + 'api/getAllTasks').then(res => {
                this.allTasks = res.data
                this.allTasks.sort((a, b) => (new Date(a.date) < new Date(b.date)) ? 1 : -1)
                this.allTasks.sort((a, b) => (a.story_id > b.story_id) ? 1 : -1)
                this.allTasks = this.allTasks.map(obj => ({
                    ...obj,
                    isDue: this.shouldDisplayDue(obj)
                }));
                this.unmutableAllTasks = this.allTasks
                if(this.defaultShowTaskType == 'Due'){
                this.allTasks = this.allTasks.filter((task)=> task.isDue);
                this.allTasks.sort((a, b) => (a.date > b.date) ? 1 : -1)

                }
                else{
                    this.allTasks = this.defaultShowTaskType != null ? this.allTasks.filter((task) => task.type === this.defaultShowTaskType) : this.allTasks
                    this.allTasks.sort((a, b) => (a.date > b.date) ? 1 : -1)
                }
            })
        },
        shouldEditable(task)
        {
            if(task.status==1 && this.editButton==true)
            {
                this.editId = task.id
                const previousTaskIndex = this.progressTasks.indexOf(task) - 1;
                if (previousTaskIndex >= 0) {
                    const previousTask = this.progressTasks[previousTaskIndex];
                    this.oldTaskCompletionDate = previousTask.date
                    var date = new Date(previousTask.date)
                    this.enabledDate =  date.setDate(date.getDate())
                }
            }
            else
            {
                this.editButton = false
            }
        },
        editTask(id) {
            this.modalTaskTotalAdd = true;
            axios.post(this.url + 'api/editTask', { "id": id }).then(res => {
                this.form = res.data;
                this.clientNameAddOption = this.allTasks.filter(client => client.id == id).map(({ firstName, lastName }) => firstName + ' ' + lastName);
                this.clientName = this.clientNameAddOption
                this.form.date = new Date(res.data.date);
            })
        },
        updateTask() {
            axios.post(this.url + 'api/updateTask', this.form).then(() => {
                this.$store.state.isAccepted = true
                this.$store.state.notificationData = 'Task Updated Successfully'
                this.modalTaskTotalAdd = false;
                this.getAllTasks();
                setTimeout(()=>{
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = null
                }, 2500)
            })
        },
        getEmailTemplates() {
            axios.get(this.url + 'api/getEmailTemplates').then(res => {
                this.emailTemplates = res.data
                this.dataFetched = true
            })
        },
        changeTaskOrder() {
            var outObject = this.allTasks.reduce(function (a, e) {
                let estKey = (e['story_id']);

                (a[estKey] ? a[estKey] : (a[estKey] = null || [])).push(e);
                return a;
            }, {});
            axios.post(this.url + 'api/changeTaskOrder', outObject).then(() => {
                this.$store.state.notificationData = 'Task Updated Successfully'
                this.modalTaskTotalAdd = false;
                this.getAllTasks();
                setTimeout(()=>{
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = null
                }, 2500)
            })
        },

        filterTasks() {
            let tasks = this.unmutableAllTasks
            if(this.defaultShowTaskType == "Due"){
                this.allTasks = tasks.filter((task) => {
                    return task.isDue;
                })
            }else{
                this.allTasks = this.defaultShowTaskType != null ? tasks.filter((task) => task.type === this.defaultShowTaskType) : tasks
                this.allTasks.sort((a, b) => (a.date > b.date) ? 1 : -1)
            }
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
        },
        encode(id) {
            return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
        }
    },
    computed: {
        myList: {
            get() {
                return this.$store.state.elements
            },
            set(value) {
                this.$store.dispatch('updateElements', value)
            }
        }
    },
    setup() {
        const modalTaskTotalAdd = ref(false);
        return {
            modalTaskTotalAdd,
        }
    }
};
</script>
