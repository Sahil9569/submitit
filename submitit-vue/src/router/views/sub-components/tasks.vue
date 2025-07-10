<template>
    <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card h-100 mb-0">
            <div class="card-body p-0">
              <div class="p-3 border-bottom">
                <div class="row align-items-center">
                  <div class="col">
                    <h2 class="card-title mb-0">Manual Tasks</h2>
                  </div>
                  <div class="col-auto">
                    <router-link to="/allTask" class="btn btn-theme btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></router-link>
                  </div>
                </div>
              </div>
              <div class="">
                <ul class="list-style-none px-3 mb-0 listMainTask">
                  <template v-if="manualTasks.length > 0">
                    <template v-for="task in manualTasks" :key="task.id">
                      <li class="py-2 border-bottom" v-if="task.isDue && $props.pageType=='dashboard'">
                        <span class="bg-theme-soft squareListTask text-theme">
                          {{ new Date(task.date).getHours()>12?String(parseInt(new Date(task.date).getHours())-12).padStart(2, '0'):String(new Date(task.date).getHours()).padStart(2, '0')}}<br>{{ new Date(task.date).getHours()>=12?'PM':'AM' }}
                        </span>
                        <div class="d-inline-block">
                          <span class="text-muted mb-0 me-2"><i class="mdi mdi-calendar-month-outline me-1"></i>{{ new Date(task.date).getDate() }} {{new Date(task.date).toLocaleString('en-US', {month: 'short'})}}, {{ new Date(task.date).getFullYear() }}  </span>
                          <!-- <span class="badge badge-soft-success mb-1" v-if="task.status==1">Task Done</span> -->
                          <span class="badge badge-soft-danger mb-1" v-if="new Date() > new Date(task.date) && task.status==2">Due</span>
                          <!-- <span class="badge badge-soft-warning mb-1" v-else>Upcoming task</span> -->
                          <span class="taskListCard d-block">{{task.title}}</span>
                          <span v-if="task.firstName">
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
                        </span>
                        </div>
                        <span class="ms-auto d-flex" v-if="$props.pageType=='client'">
                          <!-- <button class="btn btn-sm btn-soft-info"><i class="mdi mdi-pencil-outline"></i></button> -->
                          <button v-if="task.status!=1" @click="runTask(task.id, task.email_template_id)" class="btn btn-sm btn-soft-info ms-1"><i class="mdi mdi-send"></i></button>
                        </span>
                      </li>
                      <li class="py-2 border-bottom" v-if="$props.pageType=='client'">
                        <span class="bg-theme-soft squareListTask text-theme">
                          {{ new Date(task.date).getHours()>12?String(parseInt(new Date(task.date).getHours())-12).padStart(2, '0'):String(new Date(task.date).getHours()).padStart(2, '0')}}<br>{{ new Date(task.date).getHours()>=12?'PM':'AM' }}
                        </span>
                        <div class="d-inline-block">
                          <span class="text-muted mb-0 me-2"><i class="mdi mdi-calendar-month-outline me-1"></i>{{ new Date(task.date).getDate() }} {{new Date(task.date).toLocaleString('en-US', {month: 'short'})}}, {{ new Date(task.date).getFullYear() }}  </span>
                          <!-- <span class="badge badge-soft-success mb-1" v-if="task.status==1">Task Done</span> -->
                          <!-- <span class="badge badge-soft-danger mb-1" v-if="new Date() > new Date(task.date) && task.status==2">Due</span> -->
                          <span class="badge badge-soft-danger mb-1" v-if="task.isDue && new Date() > new Date(task.date) && task.status==2">Due</span>
                          <span class="badge badge-soft-warning mb-1" v-else-if="task.status==2">Upcoming task</span>
                          <span class="badge badge-soft-warning mb-1" v-else-if="task.status==3">In Progress</span>
                          <span class="badge badge-soft-success mb-1" v-else>Task Done</span>
                          <span class="taskListCard d-block">{{task.title}}</span>
                          <span v-if="task.firstName">
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
                        </span>
                        </div>
                        <span class="ms-auto d-flex" v-if="$props.pageType=='client'">
                          <!-- <button class="btn btn-sm btn-soft-info"><i class="mdi mdi-pencil-outline"></i></button> -->
                          <button v-if="task.status!=1" @click="runTask(task.id, task.email_template_id)" class="btn btn-sm btn-soft-info ms-1"><i class="mdi mdi-send"></i></button>
                        </span>
                      </li>
                    </template>
                  </template>
                  <template v-else>
                    <p class="text-center">No Manual Task Scheduled Yet</p>
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
                    <h2 class="card-title mb-0">Automated Tasks</h2>
                  </div>
                  <div class="col-auto">
                    <router-link to="/allTask" class="btn btn-theme btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></router-link>
                  </div>
                </div>
              </div>
              <div class="">
                <ul class="list-style-none px-3 mb-0 listMainTask">
                  <template v-if="getAutomatedTasks().length > 0">
                    <template v-for="task in getAutomatedTasks()" :key="task.id">
                      <li class="py-2 border-bottom align-items-center">
                        <span class="bg-theme-soft squareListTask text-theme">
                          {{ new Date(task.date).getHours()>12?String(parseInt(new Date(task.date).getHours())-12).padStart(2, '0'):String(new Date(task.date).getHours()).padStart(2, '0')}}<br>{{ new Date(task.date).getHours()>=12?'PM':'AM' }}
                        </span>
                        <div class="d-inline-block">
                          <span class="text-muted mb-0 me-2"><i class="mdi mdi-calendar-month-outline me-1"></i>{{ new Date(task.date).getDate() }} {{new Date(task.date).toLocaleString('en-US', {month: 'short'})}}, {{ new Date(task.date).getFullYear() }}  </span>
                          <span class="badge badge-soft-success mb-1" v-if="task.status==1">Task Done</span>
                          <span class="badge badge-soft-warning mb-1" v-else>Upcoming task</span>
                          <span class="taskListCard d-block">{{task.title}}</span>
                        </div>
                      </li>
                    </template>
                  </template>
                  <template v-else>
                    <p class="text-center">No Automated Task Scheduled Yet</p>
                  </template>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
</template>
<script>
import axios from 'axios'
import CryptoJS from 'crypto-js';

    export default {
        data()
        {
            return{
                tasks : [],
                url: process.env.VUE_APP_URL,
                manualTasks : []
            }
        },
        props:['allTasks', 'pageType'],
        beforeMount(){
            this.tasks = this.allTasksByProps
            this.manualTasks = this.tasks.filter(task => task.type=='Manual')
        },
        watch:{
          $route() {
            this.tasks = this.allTasksByProps
            this.manualTasks = this.tasks.filter(task => task.type=='Manual')
          }
        },
        methods:{
          runTask(id)
          {
            // if(email_template_id)
            // {
              this.tasks = this.tasks.filter((task)=>{
                if(task.id==id)
                {
                  task.status = 3
                }
              })
              axios.post(this.url+'api/runTask', {id:id}).then((res)=>{
                if(res.data=="1")
                {
                  this.tasks = this.tasks.filter((task)=>{
                    if(task.id==id)
                    {
                      task.status = 1
                    }
                  })
                  this.$store.state.isAccepted = true
                  this.$store.state.notificationData = 'Task Email Has Been Send Successfully'
                  setTimeout(()=>{
                      this.$store.state.isAccepted = false
                      this.$store.state.notificationData = null
                  }, 2500)
                }
                else
                {
                  this.tasks = this.tasks.filter((task)=>{
                    if(task.id==id)
                    {
                      task.status = 2
                    }
                  })
                  this.$store.state.isDeclined = true
                  this.$store.state.notificationData = 'Something Went Wrong'
                  setTimeout(() => {
                      this.$store.state.isDeclined = false
                      this.$store.state.notificationData = null
                  }, 2500)
                }               
                this.$emit('updateTask')
              })
            // }
            // else
            // {
            //   alert('Please Select Email Template');
            // }
          },
          encode(id) {
                return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
            }
        },
        computed:{
            getAutomatedTasks()
                {
                let tasks = this.tasks
                let activeComponent = this.$route.name
                if(activeComponent=='dashboard')
                {
                  return ()=>  tasks.filter(task => task.type == 'Automate' && (new Date(task.date) >= new Date()) && (new Date(task.date) <= new Date().setDate(new Date().getDate() + 30))) 
                }
                else
                {
                  return ()=>  tasks.filter(task => task.type == 'Automate') 
                }
            },
            getMaunalTasks()
            {
                let tasks = this.tasks
                return ()=>  tasks.filter(task => task.type=='Manual' && (new Date() >= new Date(task.date)))    
            },
            allTasksByProps() {
              console.log(this.$props.allTasks)
                return this.$props.allTasks || [];
            }

        }
    }
</script>