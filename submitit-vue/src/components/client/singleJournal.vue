<template>
    <layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title" />
            </div>
            <!-- <div class="col-auto">
                <router-link to="/journals" class="btn btn-theme">
                    <i class="mdi  mdi-arrow-left font-size-14 align-middle me-2"></i>
                    Back to Journal List
                </router-link>
            </div> -->
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mt-4 mt-xl-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h4 class="my-1 font-size-24 text-dark-cstm">Shrapnel Magazine</h4>
                                            <p class="text-muted d-flex mb-1">
                                                <span class="mdi mdi-star text-warning"></span>
                                                <span class="mdi mdi-star text-warning"></span>
                                                <span class="mdi mdi-star text-warning"></span>
                                                <span class="mdi mdi-star text-warning"></span>
                                                <span class="mdi mdi-star-outline text-warning"></span>
                                            </p>
                                        </div>
                                        <!-- <div class="col-auto">
                                            <button type="button"  class="btn btn-theme">
                                                <i class="mdi mdi-pencil font-size-12 align-middle me-1"></i> Edit
                                            </button>
                                        </div> -->
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
                                                        <p>02</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Open for fiction submissions?</h3>
                                                        <span class="px-1">:</span>
                                                        <p>Yes</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Open for nonfiction submissions? </h3>
                                                        <span class="px-1">:</span>
                                                        <p>Yes</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Fiction only?</h3>
                                                        <span class="px-1">:</span>
                                                        <p>N/A</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Nonfiction only?</h3>
                                                        <span class="px-1">:</span>
                                                        <p>Yes</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Online submissions?</h3>
                                                        <span class="px-1">:</span>
                                                        <p>No</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Simultaneous submissions?</h3>
                                                        <span class="px-1">:</span>
                                                        <p>N/A</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Defunct?</h3>
                                                        <span class="px-1">:</span>
                                                        <p>N/A</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Not included</h3>
                                                        <span class="px-1">:</span>
                                                        <p>Yes</p>
                                                    </li>
                                                    <li class="border-bottom">
                                                        <h3>Possible themes?</h3>
                                                        <span class="px-1">:</span>
                                                        <p>No</p>
                                                    </li>
                                                    <li>
                                                        <h3>Flash</h3>
                                                        <span class="px-1">:</span>
                                                        <p>N/A</p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <table class="table mb-0 table-bordered tabelJrnlCstm">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row" width="200">Year of inception</th>
                                                        <td>2022</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Acceptance %</th>
                                                        <td>5%</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Submission volume</th>
                                                        <td>5</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Minimum length</th>
                                                        <td>2000</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Maximum length</th>
                                                        <td>15000</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Style</th>
                                                        <td>literary/flash</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Notes about journal</th>
                                                        <td>
                                                            <p class="text-muted mb-0">To achieve this, it would be
                                                                necessary</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Journal information</th>
                                                        <td>
                                                            <p class="text-muted mb-0">To achieve this, it would be
                                                                necessary</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Theme</th>
                                                        <td>02 Mar,2023</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Submission Details</th>
                                                        <td><span class="d-block"><strong class="font-weight-500">Platform
                                                                    :</strong> Submittable</span> <span
                                                                class="d-block"><strong class="font-weight-500">Guidelines :
                                                                </strong> Note: Azure = "Lazuli Literary Group" (not "Azure
                                                                Magazine")</span> <span class="d-block"><strong
                                                                    class="font-weight-500">Cost : </strong> $0</span></td>
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
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col">
                        <h2 class="font-size-18 mb-0">Journal Scoring:</h2>
                    </div>
                    <!-- <div class="col-12 mt-3">
                    <p class="text-center">No story score added yet Please click add score button for add story score</p>
                </div>  -->
                </div>
                <div class="d-flex flex-wrap mainScoreRowCstm">
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>4</h3>
                                <p class="mb-0 font-size-12">Quality</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>1</h3>
                                <p class="mb-0 font-size-12">Stylized</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>2</h3>
                                <p class="mb-0 font-size-12">Difficulty</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>5</h3>
                                <p class="mb-0 font-size-12">Topical</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>1</h3>
                                <p class="mb-0 font-size-12">Experimental</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>4</h3>
                                <p class="mb-0 font-size-12">Humor</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>2</h3>
                                <p class="mb-0 font-size-12">Strange</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>4</h3>
                                <p class="mb-0 font-size-12">Dark</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>1</h3>
                                <p class="mb-0 font-size-12">International</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>3</h3>
                                <p class="mb-0 font-size-12">Margins</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>1</h3>
                                <p class="mb-0 font-size-12">Historical</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>3</h3>
                                <p class="mb-0 font-size-12">Long</p>
                            </div>
                        </div>
                    </div>
                    <div class="customColScoreSingle">
                        <div class="card mb-0">
                            <div class="card-body p-1 innerCardScore">
                                <h3>1</h3>
                                <p class="mb-0 font-size-12">Flash</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>
<script>
import Layout from "../../router/layouts/main";
import PageHeader from "@/components/admin/header/page-header";
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
            disabled3: false,
            message3: '',
        }
    },
    components: {
        Layout,
        PageHeader,
        // EasyDataTable,
        // Select2
    },
})
</script>
