<template>
    <layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title" />
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="topBarCstmTable mb-3">
                    <div class="row gx-2">
                        <div class="col">
                            <div class="searchBarStyle position-relative">
                                <input type="text" v-model="searchValueTable" class="form-control" placeholder="Search...">
                                <span class="iconSearch bx bx-search-alt"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <EasyDataTable
                    :headers="headers"
                    :items="tableItems"
                    :search-value="searchValueTable"
                    border-cell
                    theme-color="#3aafa9"
                    :rows-per-page="25"
                    buttons-pagination
                    table-class-name="table-custom-style hide-head-table"
                    >
                    <template #item-mailResponseAll="{mailResponseName, mailResponseIcon, mailResponseUrl, subjectAdd, paraContent, userFname, userNameIcon, dateResNum, dateResIcon}">  
                        <router-link :to="mailResponseUrl" class="d-flex align-items-center">
                            <div class="w-25 name-email-res d-flex align-items-center">
                                <i :class="`mdi ${mailResponseIcon} font-size-16 text-theme me-2`"></i>
                                <h4 class="font-size-14 text-dark-cstm mb-0 text-truncate">{{mailResponseName}}</h4>
                            </div>
                            <div class="w-50 subject-main-res">
                                <h5 class="font-size-13 text-dark-cstm sub-title-inner">{{subjectAdd}}</h5>
                                <span class="d-inline-block px-1">-</span>
                                <p class="text-truncate text-muted sub-desc-inner">{{paraContent}}</p>
                            </div>
                            <div class="w-25 date-main-res d-flex">
                                <h3 class="font-size-13 mb-0 text-dark-cstm"><i :class="`mdi ${userNameIcon}`"></i> {{userFname}}</h3>
                                <span class="d-inline-block px-1">-</span>
                                <p class="mb-0 text-muted"><i :class="`mdi ${dateResIcon}`"></i> {{dateResNum}}</p>
                            </div>
                        </router-link>
                    </template>
                </EasyDataTable> -->
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
                    <template #item-replyStatus="{ status }">
                        <span class="text-primary" v-if="status == 1">Active</span>
                        <span class="text-warning" v-if="status == 2">Withdrawn</span>
                        <span class="text-danger" v-if="status == 3">Rejected</span>
                        <span class="text-success" v-if="status == 4">Accepted</span>
                    </template>
                    <template #item-dateMail="{ updated_at }">
                        <div class="">
                            <p class="mb-0 text-muted"><i class="mdi mdi-calendar "></i> {{ new
                                Date(updated_at).getDate() }} {{ new
        Date(updated_at).toLocaleString('en-US', { month: 'short' }) }}, {{ new
        Date(updated_at).getFullYear() }}</p>
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
    </layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/page-header";
import axios from "axios";
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
/**
 * Tabs & accordions component
 */
export default {
    page: {
        title: "All Responses",
        meta: [{ name: "description" }]
    },
    components: {
        Layout,
        PageHeader,
        EasyDataTable
    },
    data() {
        return {
            title: "All Responses",
            url: process.env.VUE_APP_URL,
            dataFetched: false,
            headers: [
                { text: 'Journal Name', value: "journalMain" },
                { text: 'Client Name', value: "story.clientName" },
                { text: 'Story Name', value: "story.storyTitle" },
                { text: 'Round', value: "round" },
                { text: 'Reply ', value: "replyStatus" },
                { text: 'Date', value: "dateMail" },
            ],
            tableItems: [],
            searchValueTable: "",
        };
    },
    beforeMount() {
        if (!this.dataFetched) {
            this.getStorySubmissionResult()
        }
    },
    activated() {
        if (this.dataFetched) {
            this.getStorySubmissionResult()
        }
    },
    methods: {
        getStorySubmissionResult() {
            axios.get(this.url + 'api/getSubmissionResult').then(res => {
                this.tableItems = res.data
                this.tableItems.sort((a, b) => (a.updated_at < b.updated_at) ? 1 : -1)
                this.dataFetched = true
            })
        }
    }
};
</script>
