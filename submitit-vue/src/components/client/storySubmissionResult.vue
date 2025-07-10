<script>
import Layout from "../../router/layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import { ref } from "vue";

export default {
page: {
    // title: "Dashboard",
    meta: [
      {
        name: "description",
        content: 'description',
      },
    ],
  },
  setup(){
        const journalListRnd= ref(false);
        
        return{
            journalListRnd,
        }
    },
  data() {
    return {
      title: "Story Submission Result",
      totalClients : 0,
      headers :[
            { text: "Journal Name", value: "journalMain"},
            { text: "Status", value: "status"},
            { text: "Submission date", value: "subDate"},
            { text: "Response date", value: "resDate"},
            { text: "Year of inception", value: "YOI"},
            { text: "Acceptance %", value: "acceptPre"},
            { text: "Volume", value: "subVolume"},
        ], 
        tableItems :[
            {journalName: 'Writing Disorder', journalUrl: '/single_journal', matchPre:'70',status:'Active',subDate:'02 Mar,2023',resDate:'20 Mar,2023', YOI:'2022',acceptPre:'1–3%',subVolume:'20',},
            {journalName: 'Apricity Magazine', journalUrl: '/single_journal',    matchPre:'60',status:'Rejected',subDate:'03 Mar,2023',resDate:'21 Mar,2023', YOI:'2020',acceptPre:'3–5%',subVolume:'26',},
            {journalName: 'Berkeley Fiction Review', journalUrl: '/single_journal', matchPre:'80',status:'Withdrawn',subDate:'04 Mar,2023',resDate:'22 Mar,2023', YOI:'2019',acceptPre:'1–3%',subVolume:'28',},
            {journalName: 'North American Review', journalUrl: '/single_journal', matchPre:'50',status:'Active',subDate:'05 Mar,2023',resDate:'', YOI:'2022',acceptPre:'1–3%',subVolume:'30',},
            {journalName: 'The Georgia Review', journalUrl: '/single_journal',  matchPre:'60',status:'Accepted',subDate:'06 Mar,2023',resDate:'24 Mar,2023', YOI:'2021',acceptPre:'< 1%',subVolume:'32',},
            {journalName: 'Oxford American Magazine', journalUrl: '/single_journal', matchPre:'70',status:'Active',subDate:'07 Mar,2023',resDate:'', YOI:'2022',acceptPre:'3–5%',subVolume:'16',},
            {journalName: 'The Massachusetts Review', journalUrl: '/single_journal',  matchPre:'80',status:'Accepted',subDate:'08 Mar,2023',resDate:'26 Mar,2023', YOI:'2023',acceptPre:' >5%',subVolume:'24',},
        ],
        headerModal :[
            { text: "Jounrals", value: "journalMainModal"},
            { text: "Submission Date", value: "dateSubModal"},
            { text: "Response Date", value: "dateResModal"},
            { text: "Status", value: "statusModal"},
        ], 
        tableItemsModal :[
            {journalName:'Shrapnel Magazine', journalYear:'2021', Jrating:['mdi-star', 'mdi-star','mdi-star', 'mdi-star','mdi-star-outline'], dateSubModal:'2 Mar,2023', dateResModal:'', statusModal: 'Active',},
            {journalName:'Apricity Magazine', journalYear:'2022', Jrating:['mdi-star', 'mdi-star','mdi-star', 'mdi-star-outline','mdi-star-outline'], dateSubModal:'4 Mar,2023', dateResModal:'4 Mar,2023', statusModal: 'Rejected',},
            {journalName:'Pembroke Magazine', journalYear:'2023', Jrating:['mdi-star', 'mdi-star','mdi-star', 'mdi-star','mdi-star'],dateSubModal:'6 Mar,2023', dateResModal:'6 Mar,2023',  statusModal: 'Accepted', },
            {journalName:'Shrapnel Magazine', journalYear:'2021', Jrating:['mdi-star', 'mdi-star','mdi-star', 'mdi-star','mdi-star-outline'], dateSubModal:'2 Mar,2023', dateResModal:'2 Mar,2023', statusModal: 'Withdrawn',},
            {journalName:'Apricity Magazine', journalYear:'2022', Jrating:['mdi-star', 'mdi-star','mdi-star', 'mdi-star-outline','mdi-star-outline'], dateSubModal:'4 Mar,2023', dateResModal:'4 Mar,2023', statusModal: 'Rejected',},
            {journalName:'Pembroke Magazine', journalYear:'2023', Jrating:['mdi-star', 'mdi-star','mdi-star', 'mdi-star','mdi-star'],dateSubModal:'6 Mar,2023', dateResModal:'',  statusModal: 'Active', },
        ],
      searchValueTable : "",
    }
  },
  components: {
      Layout,
      PageHeader,
      EasyDataTable
  },
};
</script>

<template>
  <Layout>
    <PageHeader :title="title" />
    <div class="card">
        <div class="card-body">
            <div class=" mb-3">
                <div class="row gx-2">
                  <div class="col"><h3 class="my-1 font-size-18 text-dark-cstm">The Wreckage of Attraction</h3></div>
                  <div class="col-auto">
                    <div class="topBarCstmTable searchBarStyle position-relative">
                        <input type="text" v-model="searchValueTable" class="form-control" placeholder="Search...">
                        <span class="iconSearch bx bx-search-alt"></span>
                    </div>
                  </div>
                </div>
            </div>
            <EasyDataTable
                :headers="headers"
                :items="tableItems"
                :search-value="searchValueTable"
                border-cell
                theme-color="#3aafa9"
                :rows-per-page="25"
                buttons-pagination
                table-class-name="table-custom-style"
                >
                <template  #item-journalMain="{ journalName, journalUrl, matchPre }">
                    <span class="badge badge-soft-success">Match Percent: {{matchPre}}%</span>
                    <router-link :to="`${journalUrl}`" class="text-dark d-block">{{journalName}}</router-link>
                </template>
                <template #item-status="{status}">
                  <span :class="{
                    'text-danger': `${status}` === 'Rejected',
                    'text-success': `${status}` === 'Accepted',
                    'text-primary': `${status}` === 'Active',
                    'text-warning': `${status}` === 'Withdrawn',
                    }">
                    {{status}}
                  </span>
                </template>
                <template #item-subDate="{subDate}">
                  <span class="d-flex text-muted"><i class="mdi mdi-calendar me-1"></i> {{subDate}}</span>
                </template>
                <template #item-resDate="{resDate}">
                  <span v-if="resDate" class="d-flex text-muted"><i class="mdi mdi-calendar me-1"></i> {{resDate}}</span>
                  <span v-else ><p class="mb-0 text-muted">No Date</p></span>
                </template>
                <template #item-subResult="{ subResult }">
                    <span class="ms-auto d-flex">
                        <router-link :to="`${subResult}`" class="btn btn-sm btn-soft-info">
                            <i class="mdi mdi-eye me-1"></i> View Result
                        </router-link>
                    </span>
                </template>
            </EasyDataTable>
        </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="row gx-2">
            <div class="col-md-4 mb-3">
                <div class="p-2 border">
                    <div class="row">
                        <div class="col">
                            <label class="font-size-14 mb-1">Round 1</label>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-theme btn-sm" @click="journalListRnd  = true">View</button>
                        </div>
                    </div>
                    <p class="mb-0"><a href="javascript:void(0)" @click="journalListRnd  = true" type="button" class="text-theme">06 journals</a> pending out of 20 journals</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-2 border">
                    <div class="row">
                        <div class="col">
                            <label class="font-size-14 mb-1">Round 2</label>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-theme btn-sm" @click="journalListRnd  = true">View</button>
                        </div>
                    </div>
                    <p class="mb-0"><a href="javascript:void(0)" @click="journalListRnd  = true" class="text-theme">07 journals</a> pending out of 14 journals</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="p-2 border">
                    <div class="row">
                        <div class="col">
                            <label class="font-size-14 mb-1">Round 3</label>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-theme btn-sm" @click="journalListRnd  = true">View</button>
                        </div>
                    </div>
                    <p class="mb-0"><a href="javascript:void(0)" @click="journalListRnd  = true" class="text-theme">07 journals</a> journals pending out of 07 journals</p>
                </div>
            </div>
        </div>
      </div>
    </div>
    <!-- <div class="card">
      <div class="card-body">
        <div class="row mb-3">
          <div class="col">
              <h2 class="font-size-18 mb-0">Story Scoring:</h2>
          </div>
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
    </div> -->
    <b-modal 
            v-model="journalListRnd" 
            id="journalListRnd"
            centered
            scrollable
            size="lg"
            title="Submission Result Round 1"
            title-class="font-18"
            hide-footer >
            <div class="">
                <EasyDataTable
                    :headers="headerModal"
                    :items="tableItemsModal"
                    border-cell
                    theme-color="#3aafa9"
                    :rows-per-page="25"
                    buttons-pagination
                    table-class-name="table-custom-style"
                    >
                    <template #item-journalMainModal="{ journalName, Jrating }">
                        <!-- <span class="badge badge-soft-success">Match Percent: {{JournalPercent}}%</span> -->
                        <span class="text-dark-cstm d-block">
                            {{journalName}} 
                        </span>
                        <div class="d-flex">
                            <template v-for="(Jratings,index) in Jrating.length" :key="index">
                                <i :class="`mdi ${Jrating[index]} font-size-13 text-warning`" ></i>
                            </template>
                        </div>
                        
                    </template>
                    <template #item-statusModal="{statusModal}">
                      <span :class="{
                        'text-danger': `${statusModal}` === 'Rejected',
                        'text-primary': `${statusModal}` === 'Active',
                        'text-warning': `${statusModal}` === 'Withdrawn',
                        'text-success': `${statusModal}` === 'Accepted',
                      }">{{statusModal}}</span>
                    </template>
                    <template #item-dateSubModal="{dateSubModal}">
                        <span>
                            <i class="mdi mdi-calendar"></i>
                            {{dateSubModal}}
                        </span>
                    </template>
                    <template #item-dateResModal="{dateResModal}">
                        <span v-if="dateResModal">
                            <i class="mdi mdi-calendar"></i>
                            {{dateResModal}}
                        </span>
                        <span v-else>No Date</span>
                    </template>
                </EasyDataTable>
            </div>
    </b-modal>
   </Layout>
</template>


