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
    <b-modal v-model="modalTaskTotalAdd" id="modalTaskTotalAdd" centered scrollable size="md" title="Update Task"
      title-class="font-18" hide-footer>
      <form>
        <div class="mainFormModal">
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
              <span class="input-group-text bg-theme"><i class="mdi mdi-calendar text-white"></i></span>
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
              <option value="">No Email Template</option>
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
        <draggable class="dragArea topPosLabel list-group w-full" :list="tasks" @change="changeDefaultTaskOrder">
          <template v-for="task in tasks" :key="task.id">
            <div class="card task-box-custom mb-3">
              <div class="card-body p-relative px-lg-3 px-2 pt-4">
                <span class="bg-theme text-white p-1 tagLabelCstm line-height-1 d-inline-block">{{task.type}}</span>
                <div class="innerTaskMainLst">
                  <div class="row flex-nowrap align-items-center">
                    <div class="col-9">
                      <div class="d-flex align-items-center">
                        <div class="row gx-2 justify-content-between align-items-center w-100">
                          <div class="col-md-7">
                            <h5 class="taskTitleCstm">{{task.title}}</h5>
                          </div>
                          <div class="col-md-5 ">
                            <div class="row gx-3">
                              <div class="col-auto">
                                <router-link :to="`/single_client/`" class="mb-0 text-theme"><i
                                    class="mdi mdi-email me-1"></i>

                                  {{task.email_template_id?task.emailTemplate.title:'No Email Template'}}</router-link>
                              </div>
                              <div class="col-auto">
                                <router-link :to="`/single_story/`" class="mb-0 text-theme"><i
                                    class="me-1 mdi mdi-clock-outline"></i>
                                  {{task.duration}} Days </router-link>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-3">
                      <span class="justify-content-end ms-auto d-flex">
                        <a href="#" class="btn btn-sm btn-soft-info" @click.prevent="editTask(task.id)">
                          <i class="mdi mdi-pencil-outline"></i>
                        </a>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </draggable> 
      </div>
    </div>

  </layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/page-header";
import { VueDraggableNext } from 'vue-draggable-next';
import axios from "axios";
// import CryptoJS from 'crypto-js';
/**
* Tabs & accordions component
*/
export default {
  page: {
    title: "All Task",
    meta: [{ name: "description" }]
  },
  components: {
    Layout,
    PageHeader,
    draggable: VueDraggableNext,
  },
  data() {
    return {
      title: "All Task",
      form: {
        title: "",
        duration: '',
        type: [],
        email_template_id: []
      },
      url: process.env.VUE_APP_URL,
      dataFetched : false,
      tasks : [],
      clientName: [],
      clientNameAddOption: [],
      dateselecttask: new Date(),
      enabled: true,
      dragging: false,
      modalTaskTotalAdd : false,
      emailTemplates : []
    };
  },
  beforeMount()
  {
    if(!this.dataFetched)
    {
      this.getDefaultTask();
      this.getEmailTemplates();
    }
  },
  activated()
  {
    if(this.dataFetched)
    {
      this.getDefaultTask()
    }
  },
  methods:{
    getDefaultTask()
    {
      axios.get(this.url+'api/getDefaultTask').then(res=>{
        this.tasks = res.data
        this.dataFetched = true
      })
    },
    editTask(id) {
        this.modalTaskTotalAdd = true;
        axios.post(this.url + 'api/editDefaultTask', { "id": id }).then(res => {
            this.form = res.data;
        })
    },
    updateTask()
    {
      this.modalTaskTotalAdd = false
      axios.post(this.url+'api/updateDefaultTask', this.form).then(()=>{
        this.$store.state.isAccepted = true
        this.$store.state.notificationData = 'Task Updated Successfully'
        this.getDefaultTask()
        setTimeout(()=>{
            this.$store.state.isAccepted = false
            this.$store.state.notificationData = null
        }, 2500)
      })
    },
    changeDefaultTaskOrder()
    {
      axios.post(this.url+'api/changeDefaultTaskOrder', {'tasks':this.tasks}).then(()=>{
        this.$store.state.isAccepted = true
        this.$store.state.notificationData = 'Order for Default Task Changed Successfully'
        this.getDefaultTask()
        setTimeout(()=>{
            this.$store.state.isAccepted = false
            this.$store.state.notificationData = null
        }, 2500)
      })
    },
    getEmailTemplates() {
        axios.get(this.url + 'api/getEmailTemplates').then(res => {
            this.emailTemplates = res.data
        })
    },
  }
};
</script>
