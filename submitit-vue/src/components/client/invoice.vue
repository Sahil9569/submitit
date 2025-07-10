<template>
    <layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title" />
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class=" mb-3">
                    <div class="row gx-2">
                        <!-- <div class="col"><h3 class="my-1 font-size-18 text-dark-cstm">The Wreckage of Attraction</h3></div> -->
                        <div class="col text-end">
                            <div class="topBarCstmTable searchBarStyle position-relative">
                                <input type="text" v-model="searchValueTable" class="form-control" placeholder="Search...">
                                <span class="iconSearch bx bx-search-alt"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <EasyDataTable :headers="headers" :items="tableItems" :search-value="searchValueTable" border-cell
                    theme-color="#3aafa9" :rows-per-page="25" buttons-pagination table-class-name="table-custom-style"
                    show-index>
                    <template #item-amount="{ amount }">
                        <span class="ms-auto d-flex">${{ amount }}</span>
                    </template>
                    <template #item-actionView="{ id }">
                        <span class="ms-auto d-flex">
                            <button @click="downloadInvoice(id)" class="btn btn-sm btn-soft-info">
                                <i class="mdi mdi-eye me-1"></i>
                            </button>
                        </span>
                    </template>
                </EasyDataTable>
            </div>
        </div>
    </layout>
</template>
<script>
import Layout from "../../router/layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import axios from "axios";

export default ({
    page: {
        title: "Invoice",
        meta: [
            {
                name: "description",
                content: 'description',
            },
        ],
    },
    data() {
        return {
            title: "Invoice",
            url: process.env.VUE_APP_URL,
            searchValueTable: '',
            headers: [
                { text: "Transaction id", value: "transactionId" },
                { text: "Amount", value: "amount" },
                { text: "Service Name", value: "packageName" },
                { text: "Action", value: "actionView" },
            ],
            tableItems: [],
        }
    },
    components: {
        Layout,
        PageHeader,
        EasyDataTable,
    },
    mounted() {
        this.getAllInvoices()
    },
    activated() {
        this.getAllInvoices()
    },
    methods: {
        getAllInvoices() {
            axios.post(this.url + 'api/getAllInvoices', { id: atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId')))) }).then(res => {
                this.tableItems = res.data
            })
        },
        downloadInvoice(id) {
            axios({
                url: this.url + 'api/downloadInvoice',
                method: 'POST',
                data: { id: id },
                responseType: 'blob'
            }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'invoice.pdf');
                document.body.appendChild(link);
                this.$store.state.isAccepted = true
                this.$store.state.notificationData = 'Invoice Download Successfully'
                link.click();
                setTimeout(()=>{
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = null
                }, 2500)
            })
        }
    }
})
</script>
