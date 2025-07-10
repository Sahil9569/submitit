<template>
    <Layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader title="Active Subscription" />
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <div class="row gx-2">
                        <!-- <div class="col"><h3 class="my-1 font-size-18 text-dark-cstm">The Wreckage of Attraction</h3></div> -->
                        <div class="col text-end">
                            <div class="topBarCstmTable searchBarStyle position-relative">
                                <input type="text" v-model="searchValueTable" class="form-control"
                                    placeholder="Search..." />
                                <span class="iconSearch bx bx-search-alt"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <EasyDataTable :headers="headers" :items="tableItems" :search-value="searchValueTable" border-cell theme-color="#3aafa9" :rows-per-page="25"
                    buttons-pagination table-class-name="table-custom-style" show-index>
                    <template #item-title="{email, title, packages}">
                        <template v-if="email==null">{{title}}</template>
                        <template v-else>
                            <template v-for="packageData in packages" :key="packageData.id">
                                <span v-if="packages.length==1">{{packageData.title}}</span>
                                <span v-else>{{packageData.title}}</span>
                            </template>
                        </template>
                    </template>
                    <template #item-amount="{amount, email, packages}">
                        <template v-if="email==null">{{amount}}</template>
                        <template v-else>
                            <template v-for="packageData in packages" :key="packageData.id">
                                <span v-if="packages.length==1">{{packageData.amount}}</span>
                                <span v-else>{{amountData = packageData.amount+amountData}}</span>
                            </template>
                        </template>
                    </template>
                    <template #item-story_id="{story_id}">
                        <router-link :to="`/story_information/${encode(story_id.id)}`" class="text-theme">{{ story_id.storyTitle }}</router-link>
                    </template>
                    <template #item-status="{status}">
                        <span>{{status}}</span>
                    </template>
                    <template #item-action="{isCanceled, subscriptionId}">
                        <template v-if="isCanceled==0">
                            <span class="text-danger" role="button" @click="cancelSubscription(subscriptionId)">Proceed To Cancel</span>
                        </template>
                        <template v-else>
                            <span role="button" class="text-success">Already Canceled</span>
                        </template>
                    </template>
                </EasyDataTable>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from "../../router/layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import axios from "axios";
import CryptoJS from 'crypto-js';
import EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
export default {
    page: {
        title: "Add Extra Services",
        meta: [
            {
                name: "description",
                content: "description",
            },
        ],
    },
    data() {
        return {
            title: "Add Extra Services",
            url: process.env.VUE_APP_URL,
            submitted: false,
            show: [],
            tableItems: [],
            headers: [
                { text: "Service", value: "packageName" },
                { text: "Amount", value: "amount" },
                { text: "Story", value: "story_id" },
                { text: "Status", value: "status" },
                { text: "Cancel Link", value: "action" },
            ],
        };

    },
    components: {
        PageHeader,
        Layout,
        EasyDataTable,
    },

    activated() {
        this.getActiveSubscription();
    },

    methods: {
        getActiveSubscription() {
            axios
                .post(this.url + "api/getActiveSubscription", {
                    id: atob(
                        decodeURIComponent(
                            JSON.parse(localStorage.getItem("authenticatedUserId"))
                        )
                    ),
                })
                .then((res) => {
                    this.tableItems = res.data;
                });
        },
        cancelSubscription(id)
        {
            axios
                .post(this.url + "api/cancelSubscription", {
                    subscriptionId: id
                })
                .then(() => {
                    this.getActiveSubscription()
                });
        },
        encode(id) {
            return encodeURIComponent(CryptoJS.AES.encrypt(String(id), "Secret Passphrase"));
        },
    },
};
</script>
