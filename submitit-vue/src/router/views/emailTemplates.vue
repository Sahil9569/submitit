<template>
    <layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title" />
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="card task-box-custom mb-3" v-for="(template, index) in  this.emailTemplate  " :key="index">
                    <!-- <div class="card-body p-relative px-lg-3 px-2 pt-4"> -->
                    <div class="innerTaskMainLst">
                        <div class="row flex-nowrap align-items-center">
                            <div class="col-9">
                                <div class="d-flex align-items-center">
                                    <span class="bg-theme-soft squareListTask text-theme">
                                        {{ index + 1 }}
                                    </span>
                                    <div class="row gx-2 justify-content-between align-items-center w-100">
                                        <div class="col-md-7">
                                            <h5 class="taskTitleCstm"> {{ template.title }} </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <span class="justify-content-end ms-auto d-flex">
                                    <router-link class="btn btn-sm btn-soft-info"
                                        :to="`/editEmailTemplate/${encode(template.id)}`">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </router-link>
                                </span>
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/page-header";
import axios from "axios";
import CryptoJS from 'crypto-js';

export default {
    page: {
        title: "All Email Template",
        meta: [{ name: "description" }]
    },
    components: {
        Layout,
        PageHeader,
        // Select2
    },
    data() {
        return {
            title: "All Email Template",
            url: process.env.VUE_APP_URL,
            dataFetched : false,
            emailTemplate: []
        };
    },
    mounted() {
        if(!this.dataFetched)
        {
            this.getEmailTemplate();
        }
    },
    activated() {
        if(this.dataFetched)
        {
            this.getEmailTemplate();
        }
    },
    methods: {

        getEmailTemplate() {
            axios.get(this.url + 'api/getEmailTemplate').then(res => {
                this.emailTemplate = res.data
                this.dataFetched = true
            })
        },

        encode(id) {
            return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
        }
    },
};
</script>
