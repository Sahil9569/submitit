<template>
    <Layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader title="Pending Payments" />
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
                <EasyDataTable :headers="headers" :items="tableItems" :search-value="searchValueTable" border-cell
                    theme-color="#3aafa9" :rows-per-page="25" buttons-pagination table-class-name="table-custom-style"
                    show-index>
                    <template #item-title="{ email, title, packages }">
                        <template v-if="email == null">{{ title }}</template>
                        <template v-else>
                            <template v-for="packageData in packages" :key="packageData.id">
                                <span v-if="packages.length == 1">{{ packageData.title }}</span>
                                <span v-else>{{ packageData.title }}</span>
                            </template>
                        </template>
                    </template>
                    <template #item-amount="{ amount, email, packages }">
                        <template v-if="email == null">{{ amount }}</template>
                        <template v-else>
                            <template v-for="packageData in packages" :key="packageData.id">
                                <!-- <span v-if="packages.length == 1">{{ packageData.amount }}</span> -->
                                <!-- <span>{{ packageData.amount }}</span> -->
                            </template>
                            <p>{{ sumAmount(packages) }}</p>
                        </template>
                    </template>
                    <template #item-story_id="{ story_id }">
                        <span>{{ story_id.storyTitle }}</span>
                    </template>
                    <template #item-status="{ status }">
                        <span>{{ status }}</span>
                    </template>
                    <template #item-action="{ email, status, paymentLink, token }">
                        <template v-if="email == null">
                            <router-link :to="`/payment/${token}`" :disabled="status == 'Success'">Proceed To
                                Payment</router-link>
                        </template>
                        <template v-else>
                            <router-link :to="`/payment/${paymentLink}`" :disabled="status == 'Success'">Proceed To
                                Payment</router-link>
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
// import CryptoJS from 'crypto-js';
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
                { text: "Service", value: "title" },
                { text: "Amount", value: "amount" },
                { text: "Story", value: "story_id" },
                { text: "Status", value: "status" },
                { text: "Payment Link", value: "action" },
            ],

        };

    },
    components: {
        PageHeader,
        Layout,
        EasyDataTable,
    },
    mounted() {
        this.getPendingPayment()
    },
    activated() {
        this.getPendingPayment();
    },

    methods: {
        sumAmount(packages) {
            let totalAmount = 0;
            for (const packageData of packages) {
                totalAmount += parseInt(packageData.amount);
            }
            return totalAmount;
        },

        getPendingPayment() {
            axios
                .post(this.url + "api/getPendingPayment", {
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
    },
};
</script>
