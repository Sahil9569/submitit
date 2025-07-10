<template>
    <layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title" />
            </div>
            <div class="col-auto">
                <router-link to="/journals" class="btn btn-theme">
                    <i class="mdi  mdi-arrow-left font-size-14 align-middle me-2"></i>
                    Back to Journal List
                </router-link>
            </div>
        </div>
        <div class="row" v-if="journal.length != 0">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mt-4 mt-xl-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h4 class="my-1 font-size-24 text-dark-cstm">{{ journal.jrnlName }}</h4>
                                            <p class="text-muted d-flex mb-1">
                                                <template v-for="jRating in parseInt(journal.rating.slice(0, 1))"
                                                    :key="jRating">
                                                    <span class="mdi mdi-star text-warning"></span>
                                                </template>
                                                <template v-for="rating in 5 - (parseInt(journal.rating.slice(0, 1)))"
                                                    :key="rating">
                                                    <span class="mdi mdi-star-outline text-warning"></span>
                                                </template>
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <router-link :to="`/edit_journal/${encode(journal.id)}`" class="btn btn-theme">
                                                <i class="mdi mdi-pencil font-size-12 align-middle me-1"></i> Edit
                                            </router-link>
                                        </div>
                                    </div>

                                    <!-- <div class="mb-4">
                                        <h4 class="text-dark-cstm font-size-14">:</h4>
                                        
                                    </div> -->
                                    <div class="row pt-3">
                                        <div class="col-lg-5">
                                            <div class="innerGroupFeilds">
                                                <ul class="list-style-none ps-0 mb-0 customListjournal">
                                                    <li class="border-bottom">
                                                        <h3>Last checked</h3>
                                                        <span class="px-1">:</span>
                                                        <p>{{ journal.date }}</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Open for fiction submissions?</h3>
                                                        <span class="px-1">:</span>
                                                        <p v-if="journal.fictionSubmission">Yes</p>
                                                        <p v-else>No</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Open for nonfiction submissions? </h3>
                                                        <span class="px-1">:</span>
                                                        <p v-if="journal.nonFictionSubmission">Yes</p>
                                                        <p v-else>No</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Fiction only?</h3>
                                                        <span class="px-1">:</span>
                                                        <p v-if="journal.fictionOnly">Yes</p>
                                                        <p v-else>No</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Nonfiction only?</h3>
                                                        <span class="px-1">:</span>
                                                        <p v-if="journal.nonFictionOnly">Yes</p>
                                                        <p v-else>No</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Online submissions?</h3>
                                                        <span class="px-1">:</span>
                                                        <p v-if="journal.onlineSubmission">Yes</p>
                                                        <p v-else>No</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Simultaneous submissions?</h3>
                                                        <span class="px-1">:</span>
                                                        <p v-if="journal.simultaneousSubmission">Yes</p>
                                                        <p v-else>No</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Defunct?</h3>
                                                        <span class="px-1">:</span>
                                                        <p v-if="journal.defunct">Yes</p>
                                                        <p v-else>No</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Not included</h3>
                                                        <span class="px-1">:</span>
                                                        <p v-if="journal.notIncluded">Yes</p>
                                                        <p v-else>No</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Possible themes?</h3>
                                                        <span class="px-1">:</span>
                                                        <p v-if="journal.possibleThemes">Yes</p>
                                                        <p v-else>No</p>
                                                    </li>
                                                    <li>
                                                        <h3>Flash</h3>
                                                        <span class="px-1">:</span>
                                                        <p>{{ journal.flash }}</p>
                                                        <!-- <p v-else>No</p> -->
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <table class="table mb-0 table-bordered tabelJrnlCstm">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row" width="200">Year of inception</th>
                                                        <td>{{ journal.yearOfInception }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Acceptance %</th>
                                                        <td>{{ (journal.acceptance * 100).toFixed(2) }}%</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Submission volume</th>
                                                        <td>{{ journal.volume }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Minimum length</th>
                                                        <td>{{ journal.minimumLength }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Maximum length</th>
                                                        <td>{{ journal.maximumLength }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Style</th>
                                                        <td>{{ journal.permanentThemes }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Notes about journal</th>
                                                        <td>
                                                            <p class="text-muted mb-0">{{ journal.notesAboutJournal }}</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Theme</th>
                                                        <td v-if="journal.theme!=null && journal.theme!=''">{{ journal.theme }}</td>
                                                        <td v-else></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Submission Details</th>
                                                        <td><span class="d-block"><strong class="font-weight-500">Platform
                                                                    :</strong> {{ journal.submissionPlatform }}</span> <span
                                                                class="d-block"><strong class="font-weight-500">Guidelines :
                                                                </strong>
                                                                <span v-if="journal.submissionGuidelines"> <template
                                                                        v-for="guideLine in journal.submissionGuidelines.split(',')"
                                                                        :key="guideLine.id"><span>{{ guideLine }} &nbsp;
                                                                        </span></template>
                                                                </span>
                                                            </span><span class="d-block"><strong
                                                                    class="font-weight-500">Cost : </strong><span v-if="journal.submissionCost!=null || journal.submissionCost!=''">${{ journal.submissionCost }}</span>
                                                                </span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                    </div>
                </div>
                <!-- end card -->
            </div>
        </div>

        <div class="innerGroupFeilds mb-4 p-3">
            <div class="row">
                <div class="col"><label class="form-label mb-3 font-size-20">Journal Scoring</label></div>
                <div class="col align-right text-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                        <router-link :to="`/journalStory/${encode(journal.id)}`" class="btn btn-theme text-end">Journal
                            Stories</router-link>
                        <router-link :to="`/addJournalStory/${encode(journal.id)}`" class="btn btn-theme text-end">Add
                            Journal Story</router-link>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label mb-1 font-size-14">Stories</label>
                    <input type="number" disabled class="form-control-sm form-control" placeholder="Stories"
                        v-model="journalScore.stories" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label mb-1 font-size-14">Anthologies</label>
                    <input type="number" disabled class="form-control-sm form-control" placeholder="Anthologies"
                        v-model="journalScore.anthologies" />
                </div>
                <div class="col-md-4 mb-3">
                    <label for="journalList4" class="labelCheckboxCstm d-flex font-size-16 mb-1">
                        <span class="font-size-14">Dummy story</span>
                        <input type="checkbox" v-model="disabled" @change="this.message3 = ''" class="d-none"
                            id="journalList4" name="journalList">
                        <!-- <div class="checkmark ms-4 me-2">
                                <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                                <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                            </div> <span class="font-size-12">Yes</span> -->
                    </label>
                    <div class="form-group">
                        <input type="text" :disabled="!disabled" placeholder="Dummy Stories"
                            v-model="journalScore.dummyStory" class="form-control-sm form-control" />
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap mainScoreRowCstm">
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.quality }}</h3>
                            <p class="mb-0 font-size-12">Quality</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.stylized }}</h3>
                            <p class="mb-0 font-size-12">Stylized</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.difficulty }}</h3>
                            <p class="mb-0 font-size-12">Difficulty</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.topical }}</h3>
                            <p class="mb-0 font-size-12">Topical</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.experimental }}</h3>
                            <p class="mb-0 font-size-12">Experimental</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.humor }}</h3>
                            <p class="mb-0 font-size-12">Humor</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.strange }}</h3>
                            <p class="mb-0 font-size-12">Strange</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.dark }}</h3>
                            <p class="mb-0 font-size-12">Dark</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.international }}</h3>
                            <p class="mb-0 font-size-12">International</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.margins }}</h3>
                            <p class="mb-0 font-size-12">Margins</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.historical }}</h3>
                            <p class="mb-0 font-size-12">Historical</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.long }}</h3>
                            <p class="mb-0 font-size-12">Long</p>
                        </div>
                    </div>
                </div>
                <div class="customColScoreSingle">
                    <div class="card mb-0">
                        <div class="card-body p-1 innerCardScore">
                            <h3>{{ journalScore.short }}</h3>
                            <p class="mb-0 font-size-12">Short</p>
                        </div>
                    </div>
                </div>
                <!-- <div class="customColScoreSingle">
                            <div class="card mb-0">
                                <div class="card-body p-1 innerCardScore">
                                    <h3>1</h3>
                                    <p class="mb-0 font-size-12">Stories</p>
                                </div>
                            </div>
                        </div>
                        <div class="customColScoreSingle">
                            <div class="card mb-0">
                                <div class="card-body p-1 innerCardScore">
                                    <h3>2</h3>
                                    <p class="mb-0 font-size-12">Anthologies</p>
                                </div>
                            </div>
                        </div>
                        <div class="customColScoreSingle">
                            <div class="card mb-0">
                                <div class="card-body p-1 innerCardScore">
                                    <h3>X</h3>
                                    <p class="mb-0 font-size-12">Dummy story?</p>
                                </div>
                            </div>
                        </div> -->
            </div>
        </div>
        <!-- <div class="text-end mt-2">
                    <button type="button" class="btn btn-theme" @click="storeJournalScore">Algorithm</button>
                </div> -->
    </layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import axios from "axios";
import CryptoJS from 'crypto-js';
// import EasyDataTable from 'vue3-easy-data-table';
// import 'vue3-easy-data-table/dist/style.css';
// import Select2 from 'vue3-select2-component';

export default ({
    page: {
        title: "Journal Information",
        meta: [
            {
                name: "description",
                content: 'description',
            },
        ],
    },
    data() {
        return {
            title: "Journal Information",
            url: process.env.VUE_APP_URL,
            message3: '',
            dataFetched: false,
            journal: [],
            disabled: false,
            journalScore: {
                journal_id: null,
                stories: 0,
                anthologies: 0,
                dummyStory: '',
                quality: 0,
                stylized: 0,
                difficulty: 0,
                topical: 0,
                experimental: 0,
                humor: 0,
                strange: 0,
                dark: 0,
                international: 0,
                margins: 0,
                historical: 0,
                long: 0,
                short: 0,
            }
        }
    },
    components: {
        Layout,
        PageHeader,
    },
    beforeMount() {
        this.getSingleJournal()
    },
    watch: {
        $route() {
            this.getSingleJournal()
        },
    },
    methods: {
        getSingleJournal() {
            if(this.$store.state.normalStore.journals.length)
            {
                this.journal = this.$store.state.normalStore.journals.find(item=>item.id==this.decode(this.$route.params.id))
                this.journalScore = this.calculateJournalScore();
            }
            else
            {
                let data = new FormData()
                data.append('id', this.decode(this.$route.params.id))
                axios.post(this.url + 'api/getSingleJournal', data).then(res => {
                    this.journal = res.data.journal
                    axios.post(this.url + 'api/getJournalsWithScore', data).then(res => {
                        if (res.data[0]) {
                            this.journalScore = res.data[0].journal_scores
                        }
                        this.dataFetched = true
                    })
                })
            } 
        },
        calculateJournalScore()
        {
            let journalScore = {
                quality: 0,
                stylized: 0,
                difficulty: 0,
                topical: 0,
                experimental: 0,
                humor: 0,
                strange: 0,
                dark: 0,
                international: 0,
                margins: 0,
                historical: 0,
                long: 0,
                short: 0,
                stories: 0,
                anthologies: 0,
                dummyStory: 0
            };

            let journalStories = this.$store.state.normalStore.journalStories.filter(item=>item.journal_id==this.decode(this.$route.params.id))
            if(journalStories.length>0)
            {
                for (let i = 0; i < journalStories.length; i++) {
                    let story = journalStories[i];
                    journalScore.quality += parseInt(story.quality);
                    journalScore.stylized += parseInt(story.stylized);
                    journalScore.difficulty += parseInt(story.difficulty);
                    journalScore.topical += parseInt(story.topical);
                    journalScore.experimental += parseInt(story.experimental);
                    journalScore.humor += parseInt(story.humor);
                    journalScore.strange += parseInt(story.strange);
                    journalScore.dark += parseInt(story.dark);
                    journalScore.international += parseInt(story.international);
                    journalScore.margins += parseInt(story.margins);
                    journalScore.historical += parseInt(story.historical);
                    journalScore.long += parseInt(story.long);
                    journalScore.short += parseInt(story.short);
                    journalScore.anthologies += story.isAnthologies === 'true' ? 1 : 0;
                    journalScore.dummyStory += story.dummyStory === 'true' ? 1 : 0;
                }
                journalScore.quality = (journalScore.quality / journalStories.length).toFixed(1);
                journalScore.stylized = (journalScore.stylized / journalStories.length).toFixed(1);
                journalScore.difficulty = (journalScore.difficulty / journalStories.length).toFixed(1);
                journalScore.topical = (journalScore.topical / journalStories.length).toFixed(1);
                journalScore.experimental = (journalScore.experimental / journalStories.length).toFixed(1);
                journalScore.humor = (journalScore.humor / journalStories.length).toFixed(1);
                journalScore.strange = (journalScore.strange / journalStories.length).toFixed(1);
                journalScore.dark = (journalScore.dark / journalStories.length).toFixed(1);
                journalScore.international = (journalScore.international / journalStories.length).toFixed(1);
                journalScore.margins = (journalScore.margins / journalStories.length).toFixed(1);
                journalScore.historical = (journalScore.historical / journalStories.length).toFixed(1);
                journalScore.long = (journalScore.long / journalStories.length).toFixed(1);
                journalScore.short = (journalScore.short / journalStories.length).toFixed(1);
            }
            journalScore.stories = journalStories.length;
            return journalScore;
        },
        decode(id) {
            return decodeURIComponent(CryptoJS.AES.decrypt(String(id), "Secret Passphrase").toString(CryptoJS.enc.Utf8));
        },
        encode(id) {
            return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
        }
    }
})
</script>
