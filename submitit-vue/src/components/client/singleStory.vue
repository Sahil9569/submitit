<template>
    <layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title" />
            </div>
            <!-- <div class="col-auto">
                <router-link to="/stories"  class="btn btn-theme">
                 <i class="mdi mdi-arrow-left font-size-14 align-middle me-2"></i>
                  Back to Stories List
                </router-link>
            </div> -->
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <span class="text-theme" v-if="story.storyGenre == 'fiction'">Fiction 1</span>
                        <span class="text-theme" v-else>Non-Fiction</span>
                        <h2 class="my-1 font-size-24 text-dark-cstm">{{ story.storyTitle }}</h2>
                    </div>
                    <div class="col-auto">
                        <router-link :to="`/editClientStory/${encode(story.id)}`" class="btn btn-theme btn-sm">
                            <i class="mdi mdi-pencil font-size-12 align-middle me-1"></i> Edit
                        </router-link>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="mb-4 py-3">
                            <table class="table mb-0 table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width:200px;">Service</th>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="d-flex flex-wrap align-items-center"
                                                        v-if="story.submissionPackageId == null && story.fictionPackageId == null && story.editingPackageId == null">
                                                        <h6 class="mb-0">Currently You have no active package</h6>
                                                    </div>
                                                    <div class="d-flex flex-wrap align-items-center"
                                                        v-if="story.submissionPackageId != null && story.submissionPackageId != 'null'">
                                                        <h6 class="mb-0">Submission Packages :</h6>
                                                        <span
                                                            class="d-inline-block ms-2 me-1">{{ story.submissionPackage.title }}
                                                            ,<i class="mdi mdi-circle-d"></i></span>
                                                        <span
                                                            class="text-dark-cstm font-weight-500">{{ story.submissionPackage.amount }}</span>
                                                    </div>
                                                    <div class="d-flex flex-wrap align-items-center"
                                                        v-if="story.fictionPackageId != null && story.fictionPackageId != 'null'">
                                                        <h6 class="mb-0">Flash Fiction Packages :</h6>
                                                        <span class="d-inline-block ms-2 me-1">{{ story.fictionPackage.title
                                                        }} ,<i class="mdi mdi-circle-d"></i></span>
                                                        <span class="text-dark-cstm font-weight-500">${{
                                                            story.fictionPackage.amount
                                                        }}</span>
                                                    </div>
                                                    <div class="d-flex flex-wrap align-items-center"
                                                        v-if="story.editingPackageId != null && story.editingPackageId != 'null'">
                                                        <h6 class="mb-0">Flash Fiction Packages :</h6>
                                                        <span class="d-inline-block ms-2 me-1">{{ story.editingPackage.title
                                                        }} ,<i class="mdi mdi-circle-d"></i></span>
                                                        <span class="text-dark-cstm font-weight-500">${{
                                                            story.editingPackage.amount
                                                        }}</span>
                                                    </div>
                                                    <div class="d-flex flex-wrap align-items-center"
                                                        v-for="service in extraServices" :key="service.id">
                                                        <h6 class="mb-0" :class="{'text-secondary': service.status == 'Pending'}">Extra Packages :</h6>
                                                        <span class="d-inline-block ms-2 me-1" :class="{'text-secondary': service.status == 'Pending'}">{{ service.title
                                                        }} ,<i class="mdi mdi-circle-d"></i></span>
                                                        <span class="text-dark-cstm font-weight-500" :class="{'text-secondary': service.status == 'Pending'}">${{
                                                            service.amount
                                                        }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Word Count</th>
                                        <td>{{ story.wordCount }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Story Information</th>
                                        <td>{{ story.storyInfo }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Story Genre</th>
                                        <td>
                                            <p class="text-muted mb-0"> {{ story.storyGenre }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Journals Submitted</th>
                                        <td> {{story.submitted}} </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Journals Rejected</th>
                                        <td>{{story.rejected}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Journals Withdrawn</th>
                                        <td>{{story.withdrawn}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Journals Accepted</th>
                                        <td>{{story.accepted}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="">
                            <h5 class="font-size-15">Docs Files :</h5>
                            <div class="row gx-2">
                                <template v-if="story.files">
                                    <div class="col-12 mb-2" v-for="file in story.files.split(',')" :key="file.id">
                                        <div class="p-2 mb-3 bg-success bg-soft">
                                            <div class="row gx-2 align-items-center">
                                                <div class="col">
                                                    <p class="mb-0 text-success font-size-14"><strong>File name:</strong>
                                                        {{ file.split('01submitit10')[1] }}
                                                    </p>
                                                </div>
                                                <!-- <div class="col-auto">
                                                <div class=""><button class="border-0 me-1 p-0 bg-transparent"
                                                        type="button"><i
                                                            class="mdi mdi-download font-size-20 text-theme-dark"></i></button><button
                                                        class="border-0 p-0 bg-transparent" type="button"><i
                                                            class="mdi mdi-delete font-size-20 text-danger"></i></button>
                                                </div>
                                            </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="col-12 mb-2">
                                        <div class="p-2 mb-3 bg-soft">
                                            <div class="row gx-2 align-items-center">
                                                <div class="col">
                                                    <p class="mb-0 text-center text-danger font-size-15"><strong>No Files</strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <!-- <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label for="my-file" class="labelUploadFile">
                                            <input type="file"
                                                accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                                @change="previewImage($event)" class="form-control-file" id="my-file">
                                            <div class="uploadImageFile">
                                                <span><i class="mdi mdi-arrow-up-circle"></i></span>
                                                Upload File
                                            </div>
                                        </label>
                                        <span class="text-danger font-size-12 d-block text-center">doc or docx (Max
                                            Size: 15MB)</span>
                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>

        <div class="card" v-if="customPackage.includes(userType)">
            <div class="card-body">
                <label class="form-label mb-2 font-size-16">Story Scoring</label>
                <div class="row gx-3 mb-3">
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Quality</label>
                        <select class="form-select form-select-sm" v-model="storyScore.quality">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Stylized</label>
                        <select class="form-select form-select-sm" v-model="storyScore.stylized">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Difficulty</label>
                        <select class="form-select form-select-sm" v-model="storyScore.difficulty">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Topical</label>
                        <select class="form-select form-select-sm" v-model="storyScore.topical">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Experimental</label>
                        <select class="form-select form-select-sm" v-model="storyScore.experimental">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Humor</label>
                        <select class="form-select form-select-sm" v-model="storyScore.humor">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Strange</label>
                        <select class="form-select form-select-sm" v-model="storyScore.strange">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Dark</label>
                        <select class="form-select form-select-sm" v-model="storyScore.dark">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">International</label>
                        <select class="form-select form-select-sm" v-model="storyScore.international">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Margins</label>
                        <select class="form-select form-select-sm" v-model="storyScore.margins">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Historical</label>
                        <select class="form-select form-select-sm" v-model="storyScore.historical">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Long</label>
                        <select class="form-select form-select-sm" v-model="storyScore.long">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4-custom col-lg-3-custom col-xl-2-custom mb-2">
                        <label class="form-label mb-0 font-size-12">Short</label>
                        <select class="form-select form-select-sm" v-model="storyScore.short">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                </div>

                <div class="text-end mt-2">
                    <button type="button" class="btn btn-theme" @click="storeStoryScore">
                        Save
                    </button>
                </div>
            </div>
        </div>

        <div class="card" v-if="customPackage.includes(userType)">
            <div class="card-body">
                <div class="main-search-card">
                    <div class="mb-3">
                        <div class="row gx-2">
                            <div class="col">
                                <h3 class="my-1 font-size-18 text-dark-cstm">
                                    Suggested Journals Result
                                </h3>
                            </div>
                            <div class="col">
                                <div class="searchBarStyle topBarCstmTable position-relative">
                                    <input type="text" v-model="searchValueTableSuggested" class="form-control"
                                        placeholder="Search..." />
                                    <span class="iconSearch bx bx-search-alt"></span>
                                </div>
                            </div>
                            <div class="col-auto" v-if="$store.state.otherJournals.length>0">
                                <router-link to="/addMoreJournal" class="btn btn-theme">+ Add More Journals</router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <EasyDataTable :headers="suggestedHeaders" ref="dataTable" :data="suggestedJournals" :items="suggestedJournals" :search-value="searchValueTableSuggested" border-cell
                    theme-color="#3aafa9" :rows-per-page="25" buttons-pagination table-class-name="table-custom-style">
                    <template #item-journalMain="{ jrnlName, rating, matchScore }">
                        <span class="badge badge-soft-success">Match Percent: {{ matchScore }}%</span>
                        <span class="text-dark-cstm d-block">
                            {{ jrnlName }}
                        </span>
                        <div class="d-flex">
                            <template v-for="jRating in parseInt(rating.slice(0, 1))" :key="jRating">
                                <i :class="`mdi mdi-star font-size-13 text-warning`"></i>
                            </template>
                            <template v-for="jRating in 5 - parseInt(rating.slice(0, 1))" :key="jRating">
                                <i :class="`mdi mdi-star-outline font-size-13 text-warning`"></i>
                            </template>
                        </div>
                    </template>
                    <template #item-submissionGuidlines="{
                        submissionPlatform,
                        submissionGuidelines,
                        submissionCost,
                    }">
                        <p>
                            {{ submissionPlatform }}, {{ submissionGuidelines }},
                            {{ submissionCost }}
                        </p>
                    </template>
                    <template #item-actionJournal="{ id, matchScore }">
                        <span class="ms-auto d-flex">
                            <button @click="storySubmission(id, matchScore)" class="btn btn-sm btn-soft-info">
                                <i class="mdi mdi-send"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-danger ms-1" type="button">
                                <i class="mdi mdi-trash-can-outline" @click="removeSuggestedJournal(id)"></i>
                            </button>
                        </span>
                    </template>
                </EasyDataTable>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="main-search-card ">
                    <div class="mb-3">
                        <div class="row gx-2">
                            <div class="col">
                                <h3 class="my-1 font-size-18 text-dark-cstm">Submission Journals Result</h3>
                            </div>
                            <div class="col">
                                <div class="searchBarStyle topBarCstmTable  position-relative">
                                    <input type="text" v-model="searchValueTableSub" class="form-control"
                                        placeholder="Search...">
                                    <span class="iconSearch bx bx-search-alt"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <EasyDataTable :headers="customPackage.includes(userType)?headerSub:normalHeader" :items="tableItemsSub" :search-value="searchValueTableSub" border-cell
                    theme-color="#3aafa9" :rows-per-page="25" buttons-pagination table-class-name="table-custom-style">
                    <template #item-journalMain="{ journal, matchPercent }">
                        <span class="badge badge-soft-success">Match Percent: {{ matchPercent }}%</span>
                        <div class="text-dark-cstm d-block">
                            {{ journal.jrnlName }}
                        </div>
                    </template>
                    <template #item-status="{ status }">
                        <span class="text-danger" v-if="status == 0">Pending</span>
                        <span class="text-primary" v-if="status == 1">Active</span>
                        <span class="text-danger" v-if="status == 2">Withdrawn</span>
                        <span class="text-warning" v-if="status == 3">Rejected</span>
                        <span class="text-success" v-if="status == 4">Accepted</span>
                    </template>
                    <template #item-created_at="{ created_at }">
                        <span>
                            <i class="mdi mdi-calendar"></i>
                            {{ new
                                Date(created_at).getDate() }} {{ new
        Date(created_at).toLocaleString('en-US', { month: 'short' }) }}, {{ new
        Date(created_at).getFullYear() }}
                        </span>
                    </template>
                    <template #item-updated_at="{ created_at, updated_at }">
                        <span v-if="created_at != updated_at">
                            <i class="mdi mdi-calendar"></i>
                            {{ new
                                Date(updated_at).getDate() }} {{ new
                                Date(updated_at).toLocaleString('en-US', { month: 'short' }) }}, {{ new
                                Date(updated_at).getFullYear() }}
                        </span>
                        <span v-else>No Date</span>
                    </template>
                    <template #item-action="{ id }">
                        <span class="ms-auto d-flex">
                            <button @click="journalEditSts = true" v-on:click="editSubmissionId = id"
                                class="btn btn-sm btn-soft-info">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                        </span>
                    </template>
                </EasyDataTable>
            </div>
        </div>

        <b-modal v-model="journalEditSts" id="journalEditSts" centered scrollable size="md" title="Edit"
            title-class="font-18" hide-footer>
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">Date</label>
                    <div class="input-group w-100 inputFieldDate">
                        <datetime format="DD-MM-YYYY" width="130px" v-model="submissionDate" @input="date($event)" lang="en"
                            class="form-control custom-datetimepicker"></datetime>
                        <span class="input-group-text bg-theme"><i class="mdi mdi-calendar text-white"></i></span>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" v-model="status">
                        <option selected value="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="2">Withdrawn</option>
                        <option value="3">Rejected</option>
                        <option value="4">Accepted</option>
                    </select>
                </div>
            </div>
            <div class="border-top mt-3 text-end pt-3">
                <button type="button" class="btn btn-theme" @click="updateSubmissionData">
                    Save
                </button>
            </div>
        </b-modal>
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
                                    <button type="button" class="btn btn-theme btn-sm"
                                    @click="journalListRnd = true, journalListRndShow = 1">View</button>
                                </div>
                            </div>
                            <p class="mb-0"><a href="javascript:void(0)" @click="journalListRnd = true, journalListRndShow = 1" type="button"
                                    class="text-theme">{{pendingRound1.length}} journals</a> pending out of {{round1.length}} journals</p>
                            </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-2 border">
                            <div class="row">
                                <div class="col">
                                    <label class="font-size-14 mb-1">Round 2</label>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-theme btn-sm"
                                    @click="journalListRnd = true, journalListRndShow = 2">View</button>
                                </div>
                            </div>
                            <p class="mb-0"><a href="javascript:void(0)" @click="journalListRnd = true, journalListRndShow = 2"
                                    class="text-theme">{{pendingRound2.length}} journals</a> pending out of {{round2.length}} journals</p>
                            </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-2 border">
                            <div class="row">
                                <div class="col">
                                    <label class="font-size-14 mb-1">Round 3</label>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-theme btn-sm"
                                    @click="journalListRnd = true, journalListRndShow = 3">View</button>
                                </div>
                            </div>
                            <p class="mb-0"><a href="javascript:void(0)" @click="journalListRnd = true, journalListRndShow = 3"
                                    class="text-theme">{{pendingRound3.length}} journals</a> journals pending out of {{round3.length}} journals</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-modal v-model="journalListRnd" id="journalListRnd" centered scrollable size="lg"
            :title="`Submission Result Round ${journalListRndShow}`" title-class="font-18" hide-footer>
            <div class="">
                <EasyDataTable :headers="headerModal" :items="journalListRndShow==1?round1:journalListRndShow==2?round2:round3" border-cell theme-color="#3aafa9"
                    :rows-per-page="25" buttons-pagination table-class-name="table-custom-style">
                    <template #item-journalMain="{ journal, matchPercent }">
                        <span class="badge badge-soft-success">Match Percent: {{ matchPercent }}%</span>
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
                    <template #item-statusModal="{ status }">
                        <span class="text-danger" v-if="status == 0">Pending</span>
                        <span class="text-primary" v-if="status == 1">Active</span>
                        <span class="text-danger" v-if="status == 2">Rejected</span>
                        <span class="text-warning" v-if="status == 3">Withdrawn</span>
                        <span class="text-success" v-if="status == 4">Accepted</span>
                    </template>
                    <template #item-dateSubModal="{ created_at }">
                        <span>
                            <i class="mdi mdi-calendar"></i>
                            {{ new
                                Date(created_at).getDate() }} {{ new
            Date(created_at).toLocaleString('en-US', { month: 'short' }) }}, {{ new
            Date(created_at).getFullYear() }}
                        </span>
                    </template>
                    <template #item-dateResModal="{ updated_at , status}">
                        <span v-if="status!=0">
                            <i class="mdi mdi-calendar"></i>
                            {{ new
                                Date(updated_at).getDate() }} {{ new
            Date(updated_at).toLocaleString('en-US', { month: 'short' }) }}, {{ new
            Date(updated_at).getFullYear() }}
                        </span>
                        <span v-else>No Date</span>
                    </template>
                </EasyDataTable>
            </div>
        </b-modal>
    </layout>
</template>
<script>
import Layout from "../../router/layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import axios from "axios";
import CryptoJS from 'crypto-js';
// import Select2 from 'vue3-select2-component';

export default ({
    page: {
        title: "Story Information",
        meta: [
            {
                name: "description",
                content: 'description',
            },
        ],
    },
    data() {
        return {
            url: process.env.VUE_APP_URL,
            userType: null,
            journalListRnd: false,
            journalListRndShow : 0,
            journalEditSts: false,
            title: "Story Information",
            preview: [],
            image: [],
            i: 0,
            initialize: false,
            searchValueTable: "",
            searchValueTableSub: "",
            searchValueTableSuggested: '',
            story: [],
            round1 : [],
            round2 : [],
            round3 : [],
            pendingRound1 : [],
            pendingRound2 : [],
            pendingRound3 : [],
            headerSub: [
                { text: "Journals", value: "journalMain" },
                { text: "Year", value: "journal.yearOfInception" },
                { text: "Accep. %", value: "journal.acceptancePercent" },
                { text: "Volume", value: "journal.submissionVolume" },
                { text: "Style", value: "journal.permanentThemes" },
                { text: "Round", value: "round" },
                { text: "Submission Date", value: "created_at" },
                { text: "Response Date", value: "updated_at" },
                { text: "Status", value: "status" },
                { text: "Action", value: "action" },
            ],
            normalHeader: [
                { text: "Jounrals", value: "journalMain" },
                { text: "Year", value: "journal.yearOfInception" },
                { text: "Accep. %", value: "journal.acceptancePercent" },
                { text: "Volume", value: "journal.submissionVolume" },
                { text: "Style", value: "journal.permanentThemes" },
                { text: "Submission Date", value: "created_at" },
                { text: "Response Date", value: "updated_at" },
                { text: "Status", value: "status" }
            ],
            tableItemsSub: [],

            headerModal: [
                { text: "Journals", value: "journalMain" },
                { text: "Submission Date", value: "dateSubModal" },
                { text: "Response Date", value: "dateResModal" },
                { text: "Status", value: "statusModal" },
            ],
            extraServices: [],
            suggestedJournals: [],
            bonusScore: null,
            storyScore: {
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
                story_id: null,
            },
            suggestedHeaders: [
                { text: "Journals", value: "journalMain" },
                { text: "Year", value: "yearOfInception" },
                { text: "Accep. %", value: "acceptancePercent" },
                { text: "Volume", value: "submissionVolume" },
                { text: "Style", value: "permanentThemes" },
                { text: "Submission Details", value: "submissionGuidlines" },
                { text: "Flash", value: "flash" },
                { text: "Themes", value: "theme" },
                { text: "Action", value: "actionJournal" },
            ],
            topJournalId: [],
            journals: [],
            unmutableJournal: [],
            story_id: null,
            customPackage: ["price_1N90ymSGmK7KROFRZP7BnS5g", "price_1N90zySGmK7KROFRfWPL7fVn", "price_1N910nSGmK7KROFRFEsmDeW0"],
            superUser: null
        }
    },
    mounted() {
        this.userType = atob(decodeURIComponent(JSON.parse(localStorage.getItem('customUser'))))
        this.getSingleStory()
    },
    activated() {
        this.superUser = atob(decodeURIComponent(JSON.parse(localStorage.getItem('customUser'))))
        if(this.story_id!=null && this.story_id!=this.decode(this.$route.params.id))
        {
            this.getSingleStory()
        }
        else
        {
            this.getJournalByMatch()
        }
    },
    components: {
        Layout,
        PageHeader,
        EasyDataTable,
        // Select2
    },
    methods: {
        previewImage(event) {
            this.id.push(this.i++)
            var input = event.target;
            if (input.files) {
                var reader = new FileReader();
                reader.onload = (e) => {
                    this.preview.push(e.target.result);
                }
                this.image.push(input.files[0]);
                reader.readAsDataURL(input.files[0]);
            }
        },
        getSingleStory() {
            let data = new FormData()
            data.append('id', this.decode(this.$route.params.id))
            axios.post(this.url + 'api/getSingleStory', data).then(res => {
                this.story = res.data.story
                this.story_id = res.data.story.id
                this.extraServices = res.data.extraServices
                this.getClientLimit(this.story.user_id)
                if(this.customPackage.includes(this.userType))
                {
                    this.getDoNotSubmitList()
                    this.getDoNotSubmitJournals()
                    this.getStorySubmissionResult()
                }
                this.initialize = true
            })
        },
        getDoNotSubmitJournals() {
            axios.post(this.url + 'api/getDoNotSubmitJournals', { id: this.story.user_id, storyId: this.decode(this.$route.params.id) }).then(res => {
                this.doNotSubmitList = res.data
                this.getAllJournals();
            })
        },
        getDoNotSubmitList() {
            axios.post(this.url + 'api/putDoNotSubmitList', { id: this.story.user_id })
        },
        getClientLimit(id) {
            axios.post(this.url + "api/getClientLimit", { id: id }).then((res) => {
                this.clientLimit = res.data.user;
            });
        },
        storeStoryScore() {
            this.storyScore.story_id = this.decode(this.$route.params.id);
            axios.post(this.url + "api/storeStoryScore", this.storyScore).then(() => {
                this.getSuggestedJournals();
            });
        },
        getAllJournals() {
            axios.post(this.url + 'api/getJournalsWithScore').then(response => {
                let data = new FormData()
                data.append('id', this.decode(this.$route.params.id))
                axios.post(this.url + 'api/getStorySubmissionResult', data).then(res => {
                    let submittedJournals = res.data
                    let allJournals = response.data
                    this.unmutableJournal = filterByReference(allJournals, submittedJournals)
                    this.unmutableJournal = this.unmutableJournal.filter((element) => {
                        return !this.doNotSubmitList.map((item) => item.id).includes(element.id);
                    });
                    this.tableItemsSub = res.data
                    this.getJournalByMatch()
                })
            })
            const filterByReference = (arr1, arr2) => {
                let res = [];
                res = arr1.filter((el) => {
                    return !arr2.find((element) => {
                        if (
                            element.status == 0 ||
                            element.status == 1 ||
                            element.round >= 3
                        ) {
                            return element.journal_id === el.id;
                        }
                    });
                });
                return res;
            };
        },
        getJournalByMatch() {
            this.getSuggestedJournals()
            this.getJournalByAlgorithm()
        },
        getSuggestedJournals() {
            this.journals = this.unmutableJournal
            let journals = this.journals;
            this.suggestedJournals = []
            this.$store.state.otherJournals = []
            for (var i = 0; i < this.journals.length; i++) {
                this.journals[i].matchScore = 0;
                let journalScore = this.journals[i].journal_scores;
                let storyScore = this.storyScore;
                if (journals[i].acceptancePercent == 5 && journals[i].submissionVolume == 5) {
                    this.bonusScore = 10 / 100;
                } else if (
                    (journals[i].acceptancePercent == 5 &&
                        journals[i].submissionVolume == 4) ||
                    (journals[i].acceptancePercent == 4 &&
                        journals[i].submissionVolume == 5)
                ) {
                    this.bonusScore = 9 / 100;
                } else if (
                    (journals[i].acceptancePercent == 5 &&
                        journals[i].submissionVolume == 3) ||
                    (journals[i].acceptancePercent == 4 &&
                        journals[i].submissionVolume == 4)
                ) {
                    this.bonusScore = 8 / 100;
                } else if (
                    (journals[i].acceptancePercent == 5 &&
                        journals[i].submissionVolume == 2) ||
                    (journals[i].acceptancePercent == 4 &&
                        journals[i].submissionVolume == 3)
                ) {
                    this.bonusScore = 7 / 100;
                } else if (
                    (journals[i].acceptancePercent == 5 &&
                        journals[i].submissionVolume == 1) ||
                    (journals[i].acceptancePercent == 4 &&
                        journals[i].submissionVolume == 2)
                ) {
                    this.bonusScore = 6 / 100;
                } else if (
                    (journals[i].acceptancePercent == 4 &&
                        journals[i].submissionVolume == 1) ||
                    (journals[i].acceptancePercent == 3 &&
                        journals[i].submissionVolume <= 6 &&
                        journals[i].submissionVolume >= 1) ||
                    (journals[i].acceptancePercent == 2 &&
                        journals[i].submissionVolume == 1)
                ) {
                    this.bonusScore = 5 / 100;
                } else if (
                    (journals[i].acceptancePercent == 2 &&
                        journals[i].submissionVolume == 2) ||
                    (journals[i].acceptancePercent == 1 &&
                        journals[i].submissionVolume == 1)
                ) {
                    this.bonusScore = 4 / 100;
                } else if (
                    (journals[i].acceptancePercent == 2 &&
                        journals[i].submissionVolume == 3) ||
                    (journals[i].acceptancePercent == 1 &&
                        journals[i].submissionVolume == 2)
                ) {
                    this.bonusScore = 3 / 100;
                } else if (
                    (journals[i].acceptancePercent == 2 &&
                        journals[i].submissionVolume == 4) ||
                    (journals[i].acceptancePercent == 1 &&
                        journals[i].submissionVolume == 3)
                ) {
                    this.bonusScore = 2 / 100;
                } else if (
                    (journals[i].acceptancePercent == 2 &&
                        journals[i].submissionVolume == 5) ||
                    (journals[i].acceptancePercent == 1 &&
                        journals[i].submissionVolume == 4)
                ) {
                    this.bonusScore = 1 / 100;
                } else {
                    this.bonusScore = 0 / 100;
                }
                let mainScore =
                    Math.abs((journalScore.quality - storyScore.quality) * 3) +
                    Math.abs(journalScore.stylized - storyScore.stylized) +
                    Math.abs(journalScore.difficulty - storyScore.difficulty) +
                    Math.abs(journalScore.topical - storyScore.topical) +
                    Math.abs(journalScore.experimental - storyScore.experimental) +
                    Math.abs(journalScore.humor - storyScore.humor) +
                    Math.abs(journalScore.strange - storyScore.strange) +
                    Math.abs(journalScore.dark - storyScore.dark) +
                    Math.abs(journalScore.international - storyScore.international) +
                    Math.abs(journalScore.margins - storyScore.margins) +
                    Math.abs(journalScore.historical - storyScore.historical) +
                    Math.abs(journalScore.long - storyScore.long) +
                    Math.abs(journalScore.short - storyScore.short);
                let matchScore = (0.9 + -0.02 * mainScore + this.bonusScore) * 100;
                this.journals[i].matchScore = Math.round(
                    (journals[i].yearOfInception == new Date().getFullYear() ||
                        journals[i].yearOfInception == new Date().getFullYear() - 1
                        ? 5
                        : 0) + matchScore
                );
            }
            this.journals.sort((a, b) => (a.matchScore < b.matchScore) ? 1 : -1)
            let topJournals = this.journals.filter((journal) => {
                return this.topJournalId.includes(String(journal.id))
            })
            topJournals.map((journal) => {
                this.suggestedJournals.push(journal)
            })
            this.journals = this.journals.filter((journal) => {
                return !this.topJournalId.includes(String(journal.id))
            })
            if (this.story.submissionStrategy == 'top') {
                let journals = this.journals.filter((journal) => {
                    return journal.matchScore > 65
                })
                journals.map((journal) => {
                    this.suggestedJournals.push(journal)
                })
                let journals1 = this.journals.filter((journal) => {
                    return parseInt(journal.matchScore) <= 65
                })
                journals1.map((journal) => {
                    this.$store.state.otherJournals.push(journal)
                })
            }
             else if (this.story.submissionStrategy == 'mid-range') {
                let journals = this.journals.filter((journal) => {
                    return journal.matchScore >= 35 && journal.matchScore < 65
                })
                journals.map((journal) => {
                    this.suggestedJournals.push(journal)
                })
                let journals1 = this.journals.filter((journal) => {
                    return journal.matchScore >= 65 || journal.matchScore < 35
                })
                journals1.map((journal) => {
                    this.$store.state.otherJournals.push(journal)
                })
            }
            else if (this.story.submissionStrategy == 'accessible') {
                let journals = this.journals.filter((journal) => {
                    return journal.matchScore <= 35
                })
                journals.map((journal) => {
                    this.suggestedJournals.push(journal)
                })
                let journals1 = this.journals.filter((journal) => {
                    return journal.matchScore > 35
                })
                journals1.map((journal) => {
                    this.$store.state.otherJournals.push(journal)
                })
            }
            else {
                let journals = this.journals.filter((journal) => {
                    return journal.matchScore > 65
                })
                journals.map((journal) => {
                    this.suggestedJournals.push(journal)
                })
                let journals1 = this.journals.filter((journal) => {
                    return journal.matchScore <= 65
                })
                journals1.map((journal) => {
                    this.$store.state.otherJournals.push(journal)
                })
            }
            if (this.$store.state.selectedJournals.length > 0) {
                let selectedJournals = this.$store.state.selectedJournals;
                let mainJournal = this.journals.filter((element) => {
                        return selectedJournals.map((item) => item.id).includes(element.id);
                    });
                this.suggestedJournals = this.suggestedJournals.filter((element) => {
                        return !selectedJournals.map((item) => item.id).includes(element.id);
                    });
                this.$store.state.selectedJournals = mainJournal
                mainJournal.filter((journal) =>
                    this.suggestedJournals.push(journal)
                );
            }
        },
        storySubmission(id, matchPercent) {
            if (
                this.clientLimit.journalLimit > 0 && (this.clientLimit.storyLimit > 0 || this.clientLimit.stories.includes(this.story.id))) {
                this.submitToJournal(id, matchPercent)
            }
            else if(this.customPackage.includes(this.superUser))
            {
                this.submitToJournal(id, matchPercent)
            }
            else {
                alert('Sorry! Your Limit Has been finished')
            }
        },
        submitToJournal(id, matchPercent)
        {
            let data = new FormData();
                data.append('journal_id', id)
                data.append('story_id', this.decode(this.$route.params.id))
                data.append('matchPercent', matchPercent)
                axios.post(this.url + 'api/storySubmission', data).then(() => {
                    this.$store.state.isAccepted = true
                    this.$store.state.notificationData = 'Story Submitted Successfully'
                    this.getClientLimit(this.story.user_id)
                    this.getAllJournals()
                    this.suggestedJournals = this.suggestedJournals.filter((journal) => {
                        return journal.id !== id
                    })
                    let journals = this.$store.state.selectedJournals
                    this.$store.state.selectedJournals = journals.filter((journal) => {
                        return journal.id != id
                    })
                    this.$store.state.otherJournals = this.$store.state.otherJournals.filter((journal) => {
                        return journal.id !== id
                    })
                    setTimeout(() => {
                        this.$store.state.isAccepted = false
                        this.$store.state.notificationData = null
                    }, 2500)
                })
        },
        updateSubmissionData() {
            this.journalEditSts = false
            axios
                .post(this.url + "api/updateSubmissionData", {
                    id: this.editSubmissionId,
                    status: this.status,
                    date: this.submissionDate,
                })
                .then(() => {
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = 'Status Updated Successfully'
                    this.getStorySubmissionResult()
                    setTimeout(() => {
                        this.$store.state.isAccepted = false
                        this.$store.state.notificationData = null
                    }, 2500)
                });
        },
        removeSuggestedJournal(key) {
            this.suggestedJournals = this.suggestedJournals.filter(
                (item) => item.id !== key
            )
            this.$store.state.selectedJournals = this.$store.state.selectedJournals.filter(
                (item) => item.id !== key
            )
            axios.post(this.url + 'api/addInDoNotSubmitList', { id: key, storyId: this.decode(this.$route.params.id) }).then(() => {
                this.getStory()
            })
        },
        getStorySubmissionResult() {
            axios.post(this.url + 'api/getStorySubmissionResult', { 'id': this.decode(this.$route.params.id) }).then(res => {
                this.tableItemsSub = res.data
                this.getJournalByAlgorithm()
            })
        },
        getJournalByAlgorithm()
        {
            let journals = this.tableItemsSub;
            this.round1 = journals.filter((journal) => journal.round == 1);
            this.round2 = journals.filter((journal) => journal.round == 2);
            this.round3 = journals.filter((journal) => journal.round == 3);
            this.pendingRound1 = journals.filter(
                (journal) => journal.round == 1 && journal.status == 0
            );
            this.pendingRound2 = journals.filter(
                (journal) => journal.round == 2 && journal.status == 0
            );
            this.pendingRound3 = journals.filter(
                (journal) => journal.round == 3 && journal.status == 0
            );
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