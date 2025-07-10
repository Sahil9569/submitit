<template>
    <layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title ? 'Add Journal Story' : 'Update Journal Story'" />
            </div>
            <div class="col-auto">
                <button class="btn btn-theme" @click="goBack">
                    <i class="mdi mdi-arrow-left font-size-14 align-middle me-2"></i>
                    Back
                </button>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form enctype="multipart/form-data" v-if="showForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Story Title</label>
                            <input type="text" class="form-control" placeholder="Story Title"
                                v-model="journalStory.storyTitle" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Year</label>
                            <input type="number" class="form-control" placeholder="Story Year"
                                v-model="journalStory.year" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <h4 class="font-size-14 mb-0">Is Anthologies? </h4>
                            <div class="d-flex gap-3">
                                <label for="yesAltho" class="mb-0 p-relative labelStyleRadioCstm">
                                    <input type="radio" v-model="journalStory.isAnthologies" :value="true"
                                        name="optionAltho" class="inputRadioCstm d-none" id="yesAltho">
                                    <span class="radioCircleIcon">
                                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                                        <i class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                                    </span>
                                    <h4 class="font-size-12 mb-0 ms-1">Yes</h4>
                                </label>
                                <label for="noAltho" class="mb-0 p-relative labelStyleRadioCstm">
                                    <input type="radio" v-model="journalStory.isAnthologies" :value="false"
                                        name="optionAltho" class="inputRadioCstm d-none" id="noAltho">
                                    <span class="radioCircleIcon">
                                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                                        <i class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                                    </span>
                                    <h4 class="font-size-12 mb-0 ms-1">No</h4>
                                </label>
                            </div>
                            <div class="d-block" v-if="journalStory.isAnthologies ? true : false">
                                <label class="form-label mb-1 font-size-14">Anthologies </label>
                                <!-- <input type="number" v-model="story.anthologies" class="form-control" placeholder="Anthologies"> -->
                                <div class="single-select2-cstm">
                                    <Select2 v-model="journalStory.anthologies" placeholder="Select Anthology"
                                        :options="storyJournalOption" />
                                    <div class="mt-2" v-if="journalStory.anthologies == 'other [w/ fill-in]'">
                                        <input type="text" v-model="anthology" class="form-control form-control-md" placeholder="Type..."/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h4 class="font-size-14 mb-0">Dummy Story ?</h4>
                            <div class="d-flex gap-3">
                                <label for="yesDummy" class="mb-0 p-relative labelStyleRadioCstm">
                                    <input type="radio" v-model="journalStory.dummyStory" :value="true" name="optionDummy"
                                        class="inputRadioCstm d-none" id="yesDummy">
                                    <span class="radioCircleIcon">
                                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                                        <i class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                                    </span>
                                    <h4 class="font-size-12 mb-0 ms-1">Yes</h4>
                                </label>
                                <label for="noDummy" class="mb-0 p-relative labelStyleRadioCstm">
                                    <input type="radio" v-model="journalStory.dummyStory" :value="false" name="optionDummy"
                                        class="inputRadioCstm d-none" id="noDummy">
                                    <span class="radioCircleIcon">
                                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                                        <i class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                                    </span>
                                    <h4 class="font-size-12 mb-0 ms-1">No</h4>
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="innerGroupFeilds mb-4 p-3">
                                <label class="form-label mb-2 font-size-16">Story Scoring</label>
                                <div class="row gx-3 mb-3">
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Quality</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.quality">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Stylized</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.stylized">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Difficulty</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.difficulty">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Topical</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.topical">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Experimental</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.experimental">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Humor</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.humor">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Strange</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.strange">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Dark</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.dark">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">International</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.international">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Margins</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.margins">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Historical</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.historical">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Long</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.long">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                                        <label class="form-label mb-0 font-size-12">Short</label>
                                        <select class="form-select form-select-sm" v-model="journalStory.short">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" v-if="showData ? true : false">
                            <button class="btn btn-theme" type="button" @click.prevent="submitStory">Submit</button>
                        </div>
                        <div class="col-12" v-else>
                            <button class="btn btn-theme" type="button" @click.prevent="updateJournalStory">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import Select2 from 'vue3-select2-component';
import axios from "axios";
import _ from 'lodash'
import CryptoJS from 'crypto-js';

export default {
    page: {
        title: "Add Journal Story",
        meta: [
            {
                name: "description",
                content: 'description',
            },
        ],
    },
    data() {
        return {
            storyJournalOption: ['BASS', 'Pushcart', 'O. Henry', 'Best Net', 'Best Small Fictions', 'Non-Required', 'other [w/ fill-in]'],
            url: process.env.VUE_APP_URL,
            title: true,
            clients: [],
            anthology: null,
            journalStory: {
                storyTitle: "",
                isAnthologies: false,
                anthologies: null,
                dummyStory: false,
                quality: 1,
                stylized: 1,
                difficulty: 1,
                topical: 1,
                experimental: 1,
                humor: 1,
                strange: 1,
                dark: 1,
                international: 1,
                margins: 1,
                historical: 1,
                long: 1,
                short: 1,
                journal_id: null
            },
            showData: true,
            showForm: true
        }
    },
    props: ['email'],
    components: {
        Layout,
        PageHeader,
        Select2
    },
    computed: {
        lastItem() {
            return this.clients.slice(-1)[0]
        },
    },
    watch: {
        $route() {
            this.resetFields()
        }
    },
    created() {
        this.editJournalStory();

    },
    activated() {
        this.editJournalStory();
    },
    methods: {
        submitStory() {
            this.journalStory.journal_id = this.decode(this.$route.params.id)
            if(this.journalStory.anthologies == 'other [w/ fill-in]')
            {
                this.journalStory.anthologies = this.anthology
            }
            let data = new FormData();
            _.each(this.journalStory, (value, key) => {
                data.append(key, value)
            })
            axios.post(this.url + 'api/journalStoryStore', data).then(() => {
                this.$store.state.isAccepted = true
                this.$store.state.notificationData = 'Journal Story Added Successfully'
                this.$router.go(-1)
                setTimeout(()=>{
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = null
                }, 2500)
            })
        },

        resetFields() {
            this.title = true;
            this.journalStory = {
                storyTitle: "",
                year: null,
                isAnthologies: false,
                anthologies: null,
                dummyStory: false,
                quality: 1,
                stylized: 1,
                difficulty: 1,
                topical: 1,
                experimental: 1,
                humor: 1,
                strange: 1,
                dark: 1,
                international: 1,
                margins: 1,
                historical: 1,
                long: 1,
                short: 1,
                journal_id: null
            },
                this.showData = true;
        },
        editJournalStory() {
            if (this.$route.name === 'edit_journalStory' && this.$route.params.id) {
                this.showForm = false
                this.showData = false;
                this.title = false;
                axios.post(this.url + "api/editJournalStory", { 'id': this.decode(this.$route.params.id) }).then(res => {
                    this.journalStory = res.data;
                    this.showForm = true
                })
            }
        },
        updateJournalStory() {
            let data = new FormData();
            data.append('id', this.decode(this.$route.params.id));
            _.each(this.journalStory, (value, key) => {
                data.append(key, value)
            })
            axios.post(this.url + 'api/updateJournalStory', data).then(() => {
                this.$store.state.isAccepted = true
                this.$store.state.notificationData = 'Journal Story Updated Successfully'
                this.$router.go(-1)
                setTimeout(()=>{
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = null
                }, 2500)
            });
        },
        goBack() {
            this.$router.go(-1)
        },
        decode(id) {
            return decodeURIComponent(CryptoJS.AES.decrypt(String(id), "Secret Passphrase").toString(CryptoJS.enc.Utf8));
        },
        encode(id) {
            return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
        }
    }
}

</script>