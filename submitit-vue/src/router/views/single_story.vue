<template>
    <layout>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <PageHeader :title="title" />
            </div>
            <div class="col-auto">
                <router-link to="/stories" class="btn btn-theme">
                    <i class="mdi mdi-arrow-left font-size-14 align-middle me-2"></i>
                    Back to Stories List
                </router-link>
            </div>
        </div>
        <div class="row" v-if="story">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <span class="text-theme" v-if="story.storyGenre == 'fiction'">Fiction</span>
                                <span class="text-theme" v-else>Non-Fiction</span>
                                <h2 class="my-1 font-size-24 text-dark-cstm">
                                    {{ story.storyTitle }}
                                </h2>
                            </div>
                            <div class="col-auto">
                                <button type="button"
                                     class="btn btn-theme btn-sm" @click="copyClientInfo">
                                    <i class="mdi mdi-content-copy font-size-12 align-middle me-1"></i>
                                    Copy
                                </button>
                                <router-link :to="`/editStory/${encode(story.id)}`"
                                    @click="story_id = null, editStory = true" class="btn btn-theme btn-sm ms-3">
                                    <i class="mdi mdi-pencil font-size-12 align-middle me-1"></i>
                                    Edit
                                </router-link>
                            </div>
                        </div>
                        <div class="mb-4 py-3">
                            <table class="table mb-0 table-bordered">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 160px">Client Name</th>
                                        <td v-if="clientInfo"><router-link :to="`/single_client/${encode(story.user_id)}`"
                                                class="text-link-cstm">{{ clientInfo.firstName }} {{clientInfo.lastName}} {{clientInfo.penName}}</router-link></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Service</th>
                                        <td>
                                            <div class="d-flex flex-wrap align-items-center"
                                                v-if="story.submissionPackageId == null && story.fictionPackageId == null && story.editingPackageId == null">
                                                <h6 class="mb-0">Currently you have no active package</h6>
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center" v-if="story.submissionPackageId && story.submissionPackageId != 'null' &&
                                                story.submissionPackageId != null
                                                ">
                                                <h6 class="mb-0">Submission Packages :</h6>
                                                <span class="d-inline-block ms-2 me-1">{{ story.submissionPackage.title }}
                                                    ,<i class="mdi mdi-circle-d"></i></span>
                                                <span class="text-dark-cstm font-weight-500">${{
                                                    story.submissionPackage.amount }}</span>
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center" v-if="story.fictionPackageId && story.fictionPackageId != 'null' &&
                                                story.fictionPackageId != null
                                                ">
                                                <h6 class="mb-0">Flash Fiction Packages :</h6>
                                                <span class="d-inline-block ms-2 me-1">{{ story.fictionPackage.title }} ,<i
                                                        class="mdi mdi-circle-d"></i></span>
                                                <span class="text-dark-cstm font-weight-500">${{ story.fictionPackage.amount
                                                }}</span>
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center" v-if="story.editingPackageId && story.editingPackageId != 'null' &&
                                                story.editingPackageId != null
                                                ">
                                                <h6 class="mb-0">Editing Packages :</h6>
                                                <span class="d-inline-block ms-2 me-1">{{ story.editingPackage.title }} ,<i
                                                        class="mdi mdi-circle-d"></i></span>
                                                <span class="text-dark-cstm font-weight-500">${{ story.editingPackage.amount
                                                }}</span>
                                            </div>
                                            <!-- <template> -->
                                            <div class="d-flex flex-wrap align-items-center"
                                                v-for="service in extraServices" :key="service.id">
                                                <h6 class="mb-0" :class="{ 'text-secondary': service.status == 'Pending' }">
                                                    Extra Packages :</h6>
                                                <span class="d-inline-block ms-2 me-1"
                                                    :class="{ 'text-secondary': service.status == 'Pending' }">{{
                                                        service.title }} ,<i class="mdi mdi-circle-d"></i></span>
                                                <span class="text-dark-cstm font-weight-500"
                                                    :class="{ 'text-secondary': service.status == 'Pending' }">${{
                                                        service.amount
                                                    }}</span>
                                            </div>
                                            <!-- </template> -->
                                            <div>
                                                <button type="button" class="btn btn-theme btn-sm"
                                                    @click="extraServiceAdd = true">+ Add Extra Service</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Word Count</th>
                                        <td>{{ story.wordCount }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Submission Strategy</th>
                                        <td v-if="story.submissionStrategy">{{ story.submissionStrategy }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Strategy Notes</th>
                                        <td v-if="story.strategyNotes">{{ story.strategyNotes }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Story Information</th>
                                        <td v-if="story.storyInfo">{{ story.storyInfo }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Story Blurb</th>
                                        <td>
                                            <p class="text-muted mb-0" v-if="story.storyBlurb">{{ story.storyBlurb }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Bio</th>
                                        <td>
                                            <p class="text-muted mb-0" v-if="clientInfo">{{ clientInfo.bio }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Correspondence</th>
                                        <td>
                                            <p class="text-muted mb-0" v-if="story.correspondence">{{ story.correspondence
                                            }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Notes</th>
                                        <td>
                                            <p class="text-muted mb-0" v-if="story.notesFromClient">{{ story.notesFromClient
                                            }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Cover Letter</th>
                                        <td>
                                            <p class="text-muted mb-0" v-if="story.coverLetter">{{ story.coverLetter }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Final Version Saved</th>
                                        <td>
                                            <input type="checkbox" true-value="1" false-value="0"
                                                v-model="story.finalVersionSaved" onclick="return false">
                                        </td>



                                    </tr>
                                    <tr>

                                        <th scope="row">Word Count Checked</th>
                                        <td>
                                            <input type="checkbox" v-model="story.wordCountChecked" true-value="1"
                                                false-value="0" onclick="return false">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="">
                            <h5 class="font-size-15">Docs Files :</h5>
                            <div class="row gx-2" v-if="story.files">
                                <div class="col-md-4 mb-2" v-for="file in story.files.split(',')" :key="file.id">
                                    <div class="p-2 mb-3 bg-success bg-soft">
                                        <div class="row gx-2 align-items-center">
                                            <div class="col">
                                                <p class="mb-0 text-success font-size-14">
                                                    <strong>File name:</strong>{{ file.split("01submitit10")[1] }}
                                                </p>
                                            </div>
                                            <div class="col-auto">
                                                <a class="border-0 me-1 p-0 bg-transparent" :href="`${url}files/${file}`"
                                                    download type="button">
                                                    <i class="mdi mdi-download font-size-20 text-theme-dark"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-2" v-else>
                                <p class="mb-0 text-center text-danger font-size-14">
                                    <strong>No Files</strong>
                                </p>
                            </div>
                            <div></div>
                        </div>
                        <!-- end row -->
                    </div>
                </div>
                <!-- end card -->
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="p-3">
                            <div class="row">
                                <div class="col">
                                    <h2 class="my-1 font-size-18 text-dark-cstm">All Task</h2>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-theme btn-sm" v-if="progressTasks.filter((e) => e.round == 3).length == 0 &&
                                        progressTasks.filter((e) => e.round == 1).length != 0 &&
                                        progressTasks.filter((e) => e.round == 2).length != 0
                                        " v-on:click="resetInputField">
                                        <span @click="addRound3Task">+ Add Round 3 Task</span>
                                    </button>
                                    <button type="button" @click="modalTaskAdd = true" class="btn btn-theme btn-sm"
                                        v-on:click="resetInputField">
                                        <span @click="edit = false">+ Add Task</span>
                                    </button>
                                    <div class="undoFxdStyle" v-if="undoBtn == true">
                                        <span class="textConentUndo">Please Click on Undo button for Revert changes</span>
                                        <button type="button" class="btn btn-theme btn-sm"> <span @click="undoDelete">Undo
                                                Task</span>
                                        </button>
                                    </div>
                                    <div class="undoFxdStyle" v-if="undoRunBtn == true">
                                        <span class="textConentUndo">Please Click on Undo button for Revert changes</span>
                                        <button type="button" class="btn btn-theme btn-sm"> <span @click="undoRunTask">Undo
                                                Run Task</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 pt-2 custom-scroll-list">
                            <draggable class="dragArea topPosLabel list-group list-group-custom" :list="progressTasks"
                                v-model="progressTaskss" @change="changeTaskOrder" draggable=".disabledItem">
                                <template v-if="progressTasks.length > 0">
                                    <div v-for="task in progressTasks" :key="task.id" class="card task-box-custom mb-3"
                                        :class="`${task.status != 1 ? 'disabledItem' : null}`">
                                        <div class="card-body p-relative px-2 pt-4">
                                            <span
                                                class="bg-theme text-white p-1 tagLabelCstm line-height-1 d-inline-block">{{
                                                    task.type }}</span>
                                            <div class="row gx-2">
                                                <div class="col">
                                                    <h5 class="font-size-15">
                                                        <a href="javascript: void(0);" class="text-dark">{{
                                                            task.title
                                                        }}</a>
                                                    </h5>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="ms-auto d-flex">
                                                        <button type="button" v-if="task.type == 'Manual' && task.status != 1"
                                                            @click="runTask(task.id)" class="btn btn-sm btn-soft-info">
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                        <button type="button" @click="modalTaskAdd = true" v-if="task.isEditable || task.status==2 || task.status==3"
                                                            v-on:click="editTask(task.id)" href="#" id="taskedit"
                                                            class="btn btn-sm btn-soft-info ms-1">
                                                            <i @click="edit = true" class="mdi mdi-pencil-outline"></i>
                                                        </button>

                                                        <button class="btn btn-sm btn-soft-danger ms-1 deletetask" v-if="task.status != 1"
                                                            type="button" data-id="#uptask-1"
                                                            @click="deleteTask(task.id)"><i
                                                                class="mdi mdi-trash-can-outline"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <p class="col text-muted mb-0">
                                                    <i class="mdi mdi-calendar-month-outline me-1"></i>{{ new
                                                        Date(task.date).getDate() }}
                                                    {{
                                                        new Date(task.date).toLocaleString("en-US", {
                                                            month: "short",
                                                        })
                                                    }}, {{ new Date(task.date).getFullYear() }}
                                                </p>
                                                <div class="col-auto">
                                                    <span class="badge rounded-pill font-size-12 badge-soft-success"
                                                        v-if="task.status == 1">Task Done</span>
                                                    <span class="badge rounded-pill font-size-12 badge-soft-danger"
                                                        v-else-if="task.isDue">Due
                                                    </span>
                                                    <span v-else-if="task.status==3"
                                                        class="badge rounded-pill font-size-12 badge-soft-warning">In Progress</span>
                                                    <span v-else
                                                        class="badge rounded-pill font-size-12 badge-soft-warning">Upcoming</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <p class="text-center">No Story Tasks Scheduled Yet</p>
                                </template>
                            </draggable>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
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

        <div class="card">
            <div class="card-body">
                <div class="main-search-card">
                    <div class="mb-3">
                        <div class="row gx-2">
                            <div class="col">
                                <h3 class="my-1 font-size-18 text-dark-cstm">
                                    Suggested Journals
                                </h3>
                            </div>
                            <div class="col">
                                <div class="searchBarStyle topBarCstmTable position-relative">
                                    <input type="text" v-model="searchValueTable" class="form-control"
                                        placeholder="Search..." />
                                    <span class="iconSearch bx bx-search-alt"></span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <router-link to="/addMoreJournal" class="btn btn-theme">+ Add More Journals</router-link>
                            </div>
                            <div class="col-auto" v-if="suggestedJournals.length > 0">
                                <button type="button" class="btn btn-theme" @click="copyTableData">Copy</button>
                            </div>
                        </div>
                    </div>
                </div>
                <EasyDataTable :headers="headers" ref="dataTable" :data="suggestedJournals" :items="suggestedJournals"
                    :search-value="searchValueTable"  :sort-by="sortBy" :sort-type="sortType" border-cell theme-color="#3aafa9" :rows-per-page="25"
                    buttons-pagination must-sort table-class-name="table-custom-style"
                    >
                    <template #item-journalMain="{id, jrnlName, rating, matchScore }">
                        <router-link :to="`/single_journal/${encode(id)}`">
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
                        </router-link>
                    </template>
                    <template #item-possibleThemes="{possibleThemes}">
                        <p v-if="possibleThemes==1">Yes</p>
                        <p v-else>No</p>
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
                    <template #item-acceptance="{ acceptance }">
                        <p>{{ (acceptance * 100).toFixed(2) }}%</p>
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
                <div class="main-search-card">
                    <div class="mb-3">
                        <div class="row gx-2">
                            <div class="col">
                                <h3 class="my-1 font-size-18 text-dark-cstm">
                                    Submissions
                                </h3>
                            </div>
                            <div class="col">
                                <div class="searchBarStyle topBarCstmTable position-relative">
                                    <input type="text" v-model="searchValueTableSub" class="form-control"
                                        placeholder="Search..." />
                                    <span class="iconSearch bx bx-search-alt"></span>
                                </div>
                            </div>
                            <!-- <div class="col-auto">
                        <button @click="modalJournalAdd = true" type="button" class="btn btn-theme">+ Add More Journals</button>
                      </div> -->
                        </div>
                    </div>
                </div>
                <EasyDataTable :headers="headerSub" :items="submissionResult" :search-value="searchValueTableSub"
                    border-cell theme-color="#3aafa9" :rows-per-page="25" buttons-pagination
                    table-class-name="table-custom-style">
                    <template #item-journalMain="{journal, matchPercent }">
                        <router-link  :to="`/single_journal/${encode(journal.id)}`">
                            <span class="badge badge-soft-success">Match Percent: {{ matchPercent }}%
                            </span>
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
                        </router-link>
                    </template>
                    <template #item-acceptance="{ journal }">
                        <p>{{ (journal.acceptance * 100).toFixed(2) }}%</p>
                    </template>
                    <template #item-submissionDate="{ created_at }">
                        {{ new Date(created_at).getDate() }}
                        {{
                            new Date(created_at).toLocaleString("en-US", { month: "short" })
                        }}, {{ new Date(created_at).getFullYear() }}
                    </template>
                    <template #item-responseDate="{ updated_at, status }">
                        <div v-if="status == 0">
                        </div>
                        <div v-else>
                            {{ new Date(updated_at).getDate() }}
                            {{
                                new Date(updated_at).toLocaleString("en-US", { month: "short" })
                            }}, {{ new Date(updated_at).getFullYear() }}
                        </div>
                    </template>
                    <template #item-status="{ status }">
                        <span class="text-warning" v-if="status == 0">Pending</span>
                        <span class="text-primary" v-if="status == 1">Active</span>
                        <span class="text-danger" v-if="status == 2">Withdrawn</span>
                        <span class="text-danger" v-if="status == 3">Rejected</span>
                        <span class="text-success" v-if="status == 4">Accepted</span>
                    </template>
                    <template #item-dateSub="{ dateSub }">
                        <span>
                            <i class="mdi mdi-calendar"></i>
                            {{ dateSub }}
                        </span>
                    </template>
                    <template #item-dateRes="{ dateRes }">
                        <span v-if="dateRes">
                            <i class="mdi mdi-calendar"></i>
                            {{ dateRes }}
                        </span>
                        <span v-else> No Date </span>
                    </template>
                    <template #item-action="{ id }">
                        <span class="ms-auto d-flex">
                            <button @click="editSubmissionData(id)" v-on:click="editSubmissionId = id"
                                class="btn btn-sm btn-soft-info">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-danger ms-1" type="button">
                                <i class="mdi mdi-alpha-x font-size-16" @click="rejectSubmissionJournal(id)"></i>
                            </button>
                            <button class="btn btn-sm btn-soft-danger ms-1" type="button">
                                <i class="mdi mdi-trash-can-outline" @click="removeSubmissionJournal(id)"></i>
                            </button>
                        </span>
                    </template>
                </EasyDataTable>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- <div class="row mb-3">
                    <div class="col"></div>
                    <div class="col-auto">
                        <button @click="addRoundModal = true" type="button" class="btn btn-theme">+ Add Round</button>
                    </div>
                </div> -->
                <div class="row gx-2">
                    <div class="col-md-4 mb-3">
                        <div class="p-2 border">
                            <label class="font-size-14 mb-1">Round 1</label>
                            <p class="mb-0">
                                <a href="javascript:void(0)" @click="(journalListRndShow = 1), (journalListRnd = true)"
                                    type="button" class="text-theme">{{ pendingRound1.length }} journals</a>
                                pending out of {{ round1.length }} journals
                            </p>
                            <button type="button" class="btn btn-theme btn-sm"
                                @click="(journalListRndShow = 1), (journalListRnd = true)">View Results</button>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-2 border">
                            <label class="font-size-14 mb-1">Round 2</label>
                            <p class="mb-0">
                                <a href="javascript:void(0)" @click="(journalListRndShow = 2), (journalListRnd = true)"
                                    class="text-theme">{{ pendingRound2.length }} journals</a>
                                pending out of {{ round2.length }} journals
                            </p>
                            <button type="button" class="btn btn-theme btn-sm"
                                @click="(journalListRndShow = 2), (journalListRnd = true)">View Results</button>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-2 border">
                            <label class="font-size-14 mb-1">Round 3</label>
                            <p class="mb-0">
                                <a href="javascript:void(0)" @click="(journalListRndShow = 3), (journalListRnd = true)"
                                    class="text-theme">{{ pendingRound3.length }} journals</a>
                                journals pending out of {{ round3.length }} journals
                            </p>
                            <button type="button" class="btn btn-theme btn-sm"
                                @click="(journalListRndShow = 3), (journalListRnd = true)">View Results</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-modal v-model="modalTaskAdd" id="modalTaskAdd" centered scrollable size="md"
            :title="titleTask == true ? 'Add Task' : 'Update Task'" title-class="font-18" hide-footer>
            <form>
                <div class="mainFormModal">
                    <div class="mb-3">
                        <label for="task-title-input">Task Title</label>
                        <input id="task-title-input" type="text" class="form-control" placeholder="Type Task Title"
                            v-model="task.title" :class="{ 'is-invalid': submitted && v$.task.title.$error, }" :disabled="task.status==1" />
                        <div v-if="submitted && v$.task.title.$error" class="invalid-feedback">
                            <span v-if="v$.task.title.required.$message">{{ v$.task.title.required.$message
                            }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Duration</label>
                        <div class="input-group w-100 inputFieldDate">
                            <input id="task-title-input" type="number" class="form-control" placeholder="In Days"
                                v-model="task.duration" :class="{ 'is-invalid': submitted && v$.task.duration.$error, }" :disabled="task.status==1"/>
                            <div v-if="submitted && v$.task.duration.$error" class="invalid-feedback">
                                <span v-if="v$.task.duration.required.$message">{{ v$.task.duration.required.$message
                                }}</span>
                            </div>
                            <span class="input-group-text bg-theme"><i class="mdi mdi-calendar text-white"></i></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="task-title-input">Task Type</label>
                        <select class="form-select" v-model="task.type" :disabled="task.status==1">
                            <option value="Manual">Manual</option>
                            <option value="Automate">Automate</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="task-title-input">Select Email Template</label>
                        <select class="form-select" v-model="task.email_template_id" :disabled="task.status==1">
                            <option value="" selected>No Email Template</option>
                            <option v-for="template in emailTemplates" :key="template.id" :value="template.id">
                                {{ template.title }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-3" v-if="task.status==1">
                        <label>Completion Date</label>
                        <div class="input-group w-100 inputFieldDate">
                            <datepicker v-model="task.date" name="createdDate" format="MMMM dd yyyy" :disabled-dates="{
                                to: new Date(this.enabledDate),
                                from: new Date()}"
                                @input="completionDateSelected" input-class="customClass" placeholder="MM-DD-YYYY" :prevent-disable-date-selection="preventDisableDateSelection"></datepicker>
                            <span class="input-group-text bg-theme"><i class="mdi mdi-calendar text-white"></i></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end border-top pt-3">
                        <button type="button" class="btn btn-theme" @click.prevent="addTask">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </b-modal>
        <b-modal v-model="modalJournalAdd" id="journalModalAdd" centered scrollable size="md" title="All Journals"
            title-class="font-18" hide-footer>
            <div class="row gx-2">
                <div class="col-md-6 mb-1">
                    <label for="addJournal1" class="labelCheckboxCstm d-flex font-size-13 mb-1 border p-2">
                        <input type="checkbox" class="d-none" id="addJournal1" name="addJournal" />
                        <div class="checkmark me-2">
                            <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                            <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                        </div>
                        <div class="">
                            <span class="d-block">Peatsmoke Journal</span>
                            <span class="badge badge-soft-success">Match Percent: 40%</span>
                            <div class="d-flex">
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star-outline font-size-13 text-warning"></i>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="col-md-6 mb-1">
                    <label for="addJournal2" class="labelCheckboxCstm d-flex font-size-13 mb-1 border p-2">
                        <input type="checkbox" class="d-none" id="addJournal2" name="addJournal" />
                        <div class="checkmark me-2">
                            <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                            <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                        </div>
                        <div class="">
                            <span class="d-block">North Dakota Quarterly </span>
                            <span class="badge badge-soft-success">Match Percent: 30%</span>
                            <div class="d-flex">
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star-outline font-size-13 text-warning"></i>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="col-md-6 mb-1">
                    <label for="addJournal3" class="labelCheckboxCstm d-flex font-size-13 mb-1 border p-2">
                        <input type="checkbox" class="d-none" id="addJournal3" name="addJournal" />
                        <div class="checkmark me-2">
                            <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                            <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                        </div>
                        <div class="">
                            <span class="d-block">Shrapnel Magazine</span>
                            <span class="badge badge-soft-success">Match Percent: 35%</span>
                            <div class="d-flex">
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star-outline font-size-13 text-warning"></i>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="col-md-6 mb-1">
                    <label for="addJournal4" class="labelCheckboxCstm d-flex font-size-13 mb-1 border p-2">
                        <input type="checkbox" class="d-none" id="addJournal4" name="addJournal" />
                        <div class="checkmark me-2">
                            <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                            <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                        </div>
                        <div class="">
                            <span class="d-block">The Militant Grammarian</span>
                            <span class="badge badge-soft-success">Match Percent: 25%</span>
                            <div class="d-flex">
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star-outline font-size-13 text-warning"></i>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="col-md-6 mb-1">
                    <label for="addJournal5" class="labelCheckboxCstm d-flex font-size-13 mb-1 border p-2">
                        <input type="checkbox" class="d-none" id="addJournal5" name="addJournal" />
                        <div class="checkmark me-2">
                            <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                            <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                        </div>
                        <div class="">
                            <span class="d-block">Peatsmoke Journal</span>
                            <span class="badge badge-soft-success">Match Percent: 40%</span>
                            <div class="d-flex">
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star-outline font-size-13 text-warning"></i>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="col-md-6 mb-1">
                    <label for="addJournal6" class="labelCheckboxCstm d-flex font-size-13 mb-1 border p-2">
                        <input type="checkbox" class="d-none" id="addJournal6" name="addJournal" />
                        <div class="checkmark me-2">
                            <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                            <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                        </div>
                        <div class="">
                            <span class="d-block">North Dakota Quarterly </span>
                            <span class="badge badge-soft-success">Match Percent: 30%</span>
                            <div class="d-flex">
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star-outline font-size-13 text-warning"></i>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="col-md-6 mb-1">
                    <label for="addJournal7" class="labelCheckboxCstm d-flex font-size-13 mb-1 border p-2">
                        <input type="checkbox" class="d-none" id="addJournal7" name="addJournal" />
                        <div class="checkmark me-2">
                            <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                            <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                        </div>
                        <div class="">
                            <span class="d-block">Shrapnel Magazine</span>
                            <span class="badge badge-soft-success">Match Percent: 35%</span>
                            <div class="d-flex">
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star-outline font-size-13 text-warning"></i>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="col-md-6 mb-1">
                    <label for="addJournal8" class="labelCheckboxCstm d-flex font-size-13 mb-1 border p-2">
                        <input type="checkbox" class="d-none" id="addJournal8" name="addJournal" />
                        <div class="checkmark me-2">
                            <i class="mdi mdi-checkbox-blank-outline text-theme font-size-18"></i>
                            <i class="mdi mdi-checkbox-marked-outline text-theme font-size-18"></i>
                        </div>
                        <div class="">
                            <span class="d-block">The Militant Grammarian</span>
                            <span class="badge badge-soft-success">Match Percent: 25%</span>
                            <div class="d-flex">
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star font-size-13 text-warning"></i>
                                <i class="mdi mdi-star-outline font-size-13 text-warning"></i>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="border-top pt-2 mt-2">
                <div class="row justify-content-end">
                    <div class="col-auto">
                        <button type="button" class="btn btn-theme">+ Add</button>
                    </div>
                </div>
            </div>
        </b-modal>
        <b-modal v-model="submitJournalCol" id="submitJournalCol" centered scrollable size="md" title="Journals"
            title-class="font-18" hide-footer>
            <div class="">
                <label class="form-label">Add Note <sup>(If Any)</sup></label>
                <textarea type="textarea" class="resize-none form-control" rows="6"></textarea>
                <button type="button" class="btn btn-theme mt-3">Submit</button>
            </div>
        </b-modal>
        <b-modal v-model="journalListRnd" id="journalListRnd" centered scrollable size="lg" title="Journals Listing"
            title-class="font-18" hide-footer>
            <div class="">
                <EasyDataTable :headers="headerModal" :items="journalListRndShow == 1
                    ? round1
                    : journalListRndShow == 2
                        ? round2
                        : round3
                    " border-cell theme-color="#3aafa9" :rows-per-page="25" buttons-pagination
                    table-class-name="table-custom-style">
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
                        <span class="text-warning" v-if="status == 0">Pending</span>
                        <span class="text-primary" v-if="status == 1">Active</span>
                        <span class="text-danger" v-if="status == 2">Withdrawn</span>
                        <span class="text-danger" v-if="status == 3">Rejected</span>
                        <span class="text-success" v-if="status == 4">Accepted</span>
                    </template>
                    <template #item-dateSubModal="{ created_at }">
                        <span>
                            <i class="mdi mdi-calendar"></i>
                            {{ new Date(created_at).getDate() }}
                            {{
                                new Date(created_at).toLocaleString("en-US", {
                                    month: "short",
                                })
                            }}, {{ new Date(created_at).getFullYear() }}
                        </span>
                    </template>
                    <template #item-dateResModal="{ updated_at, status }">
                        <span v-if="status != 0">
                            <i class="mdi mdi-calendar"></i>
                            {{ new Date(updated_at).getDate() }}
                            {{
                                new Date(updated_at).toLocaleString("en-US", {
                                    month: "short",
                                })
                            }}, {{ new Date(updated_at).getFullYear() }}
                        </span>
                        <span v-else>No Date</span>
                    </template>
                </EasyDataTable>
            </div>
        </b-modal>
        <b-modal v-model="journalEditSts" id="journalEditSts" centered scrollable size="md" title="Edit"
            title-class="font-18" hide-footer>
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">Submission Date</label>
                    <div class="input-group w-100 inputFieldDate">
                        <datepicker v-model="createdDate" name="createdDate" format="MMMM dd yyyy"
                            @input="createdDateSelected" input-class="customClass" placeholder="MM-DD-YYYY"></datepicker>
                        <span class="input-group-text bg-theme"><i class="mdi mdi-calendar text-white"></i></span>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Reply Date</label>
                    <div class="input-group w-100 inputFieldDate">
                        <datepicker v-model="submissionDate" name="submissionDate" format="MMMM dd yyyy"
                            @input="dateSelected" input-class="customClass" placeholder="MM-DD-YYYY"></datepicker>
                        <span class="input-group-text bg-theme"><i class="mdi mdi-calendar text-white"></i></span>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" v-model="status">
                        <option selected value="">Select Status</option>
                        <option value="0">Pending</option>
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
        <b-modal v-model="extraServiceAdd" id="modalTaskTotalAdd" centered scrollable size="md" title="Add Extra Service"
            title-class="font-18" hide-footer>
            <form>
                <div class="mainFormModal">
                    <div class="mb-3">
                        <label for="task-title-input">Service Title</label>
                        <input id="task-title-input" type="text" class="form-control" placeholder="Type Service Title"
                            v-model="service.title">
                    </div>
                    <div class="mb-3">
                        <label>Amount</label>
                        <div class="input-group w-100 inputFieldDate">
                            <input id="task-title-input" type="number" class="form-control" placeholder="Amount"
                                v-model="service.amount">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end border-top pt-3">
                        <button type="submit" @click.prevent="addExtraService()" class="btn btn-theme">Submit</button>
                    </div>
                </div>
            </form>
        </b-modal>
    </layout>
</template>
<script>
import Layout from "../layouts/main";
import PageHeader from "@/components/admin/header/page-header";
import { VueDraggableNext } from "vue-draggable-next";
import Datepicker from 'vuejs3-datepicker';
import EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
import CryptoJS from "crypto-js";
// import Select2 from 'vue3-select2-component';
import axios from "axios";
import useVuelidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";

export default {
    page: {
        title: "Story Information",
        meta: [
            {
                name: "description",
                content: "description",
            },
        ],
    },
    setup() {
        return { v$: useVuelidate() };
    },
    data() {
        return {
            isDue: 0,
            enabledDate: new Date(),
            showDue: false,
            submitted: false,
            dataFetched: false,
            modalTaskAdd: false,
            modalJournalAdd: false,
            submitJournalCol: false,
            journalEditSts: false,
            journalListRnd: false,
            extraServiceAdd: false,
            sortBy: ["acceptance", "weight"],
      sortType: ["desc", "asc"],
            journalListRndShow: 0,
            titleTask: true,
            title: "Story Information",
            url: process.env.VUE_APP_URL,
            pickeddd: new Date(),
            story: [],
            addRoundNumber: [{ id: 1 }],
            preview: [],
            image: [],
            i: 0,
            searchValueTable: "",
            searchValueTableSub: "",
            dateselecttask: new Date(),
            enabled: true,
            dragging: false,
            progressTasks: [],
            oldTaskCompletionDate: new Date(),
            tasks: [],
            doNotSubmitList: [],
            rejectMainJournals: [],
            task: {
                title: "",
                date: new Date(),
                type: "Manual",
                email_template_id: '',
                duration: "",
                status: 2,
                story_id: null,
                enabledDate: new Date()
            },
            unmutableJournal: [],
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
            deleteItem: [],
            undoBtn: false,
            undoRunTasks: [],
            undoRunBtn: false,
            clientLimit: [],
            journals: [],
            suggestedJournals: [],
            selectedJournals: [],
            unmutableselectedJournals: [],
            bonusScore: 0,
            edit: false,
            submissionResult: [],
            otherJournals: [],
            round1: [],
            round2: [],
            round3: [],
            pendingRound1: [],
            pendingRound2: [],
            pendingRound3: [],
            editSubmissionId: null,
            status: 0,
            submissionDate: new Date(),
            createdDate: new Date(),
            editId: null,
            editButton: true,
            headers: [
                { text: "Journals", value: "journalMain" },
                { text: "Year", value: "yearOfInception" },
                { text: "Accep. %", value: "acceptance" },
                { text: "Volume", value: "volume" },
                { text: "Style", value: "permanentThemes" },
                { text: "Submission Details", value: "submissionGuidlines" },
                { text: "Flash", value: "flash" },
                { text: "Possible Themes", value: "possibleThemes" },
                { text: "Themes", value: "theme" },
                { text: "Action", value: "actionJournal" },
            ],
            headerSub: [
                { text: "Journals", value: "journalMain" },
                { text: "Year", value: "journal.yearOfInception" },
                { text: "Accep. %", value: "acceptance" },
                { text: "Volume", value: "journal.volume" },
                { text: "Style", value: "journal.permanentThemes" },
                { text: "Submission Date", value: "submissionDate" },
                { text: "Response Date", value: "responseDate" },
                { text: "Round", value: "round" },
                { text: "Status", value: "status" },
                { text: "Action", value: "action" },
            ],
            headerModal: [
                { text: "Journals", value: "journalMain" },
                { text: "Submission Date", value: "dateSubModal" },
                { text: "Response Date", value: "dateResModal" },
                { text: "Status", value: "statusModal" },
            ],
            service: {
                title: null,
                amount: null,
                story_id: null,
                user_id: null
            },
            extraServices: [],
            isSuccess: false,
            story_id: null,
            editStory: false,
            superPackage: null,
            customPackage: ["price_1N90ymSGmK7KROFRZP7BnS5g", "price_1N90zySGmK7KROFRfWPL7fVn", "price_1N910nSGmK7KROFRFEsmDeW0"],
            algoRun: false,
            show: false,
            preventDisableDateSelection: true
        };
    },
    validations: {
        task: {
            title: {
                required: helpers.withMessage("Task Title is required", required)
            },
            duration: {
                required: helpers.withMessage("Duration is required", required)
            }
        }
    },
    beforeMount() {
        if (!this.dataFetched) {
            this.getStory();
            this.getTasks();
            this.getAddMoreJournal();
            this.$store.dispatch('getStoryJournal')

        }
    },
    activated() {
        this.isDue = 0
        if (this.dataFetched) {
            if (this.story_id != null && this.story_id != this.decode(this.$route.params.id)) {
                this.resetInputField();
                this.$store.state.otherJournals = []
                this.suggestedJournals = []
                this.getStory();
                this.getTasks();
                this.getAddMoreJournal();

            }
            else if (this.editStory) {
                this.getStory()
                this.getTasks()
                this.getAddMoreJournal();

            }
            else {
                this.getJournalByMatch()
                this.getStory();
                this.getTasks()
                this.getAddMoreJournal();

            }
        }
    },
    watch: {
        $route() {

        },
    },
    components: {
        Layout,
        PageHeader,
        Datepicker,
        draggable: VueDraggableNext,
        EasyDataTable,
    },
    methods: {
        copyClientInfo()
        {
            let client = this.$store.state.normalStore.clients.find(item=>item.id==this.story.user_id)
            client = JSON.parse(JSON.stringify(client, (key, value) => value === null || value === undefined ? '' : value));
            const textarea  = document.createElement('textarea');
            textarea.value=client.firstName+' '+client.lastName+'\n'+client.street+'\n'+client.city+', '+client.state+' '+client.zipCode+'\n'+client.phone+'\n'+client.sharedEmail
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            this.$store.state.isAccepted = true
            this.$store.state.notificationData = 'Copied Successfully'
            this.getTasks();
            setTimeout(() => {
                this.$store.state.isAccepted = false
                this.$store.state.notificationData = null
            }, 2500)
        },
        copyTableData() {
            const tempTable = document.createElement('table');
            const tempTableBody = document.createElement('tbody');
            const customHeaderContent = ['Match', 'Journal', 'Year', 'Acceptance', 'Volume'];
            const excludedFieldIndexes = [5, 6, 7, 8];
            const tempHeader = document.createElement('tr');
            customHeaderContent.forEach((content, index) => {
                if (!excludedFieldIndexes.includes(index)) {
                    const tempCell = document.createElement('th');
                    tempCell.innerHTML = content;
                    tempCell.style.backgroundColor = 'lightgray';
                    tempCell.style.fontWeight = 'bold';
                    tempHeader.appendChild(tempCell);
                }
            });
            tempTableBody.appendChild(tempHeader);
            this.suggestedJournals.forEach((item, index) => {
                const dataRow = document.createElement('tr');

                Object.keys(item).forEach((data, pos) => {
                    var dataCell = document.createElement('td');
                    if (pos == 0) {
                        dataCell.innerHTML = this.suggestedJournals[index]['matchScore'] + '%';
                        dataRow.appendChild(dataCell);
                    }
                    else if (pos == 1) {
                        dataCell.innerHTML = this.suggestedJournals[index]['jrnlName'];
                        dataRow.appendChild(dataCell);
                    }
                    else if (pos == 2) {
                        dataCell.innerHTML = this.suggestedJournals[index]['yearOfInception'];
                        dataRow.appendChild(dataCell);
                    }
                    else if (pos == 3) {
                        if ((this.suggestedJournals[index]['acceptance'] * 100).toFixed(2) < 1) {
                            dataCell.innerHTML = '<1%';
                        }
                        else if ((this.suggestedJournals[index]['acceptance'] * 100).toFixed(2) >= 1 && (this.suggestedJournals[index]['acceptance'] * 100).toFixed(2) <= 3) {
                            dataCell.innerHTML = '1-3%';
                        }
                        else if ((this.suggestedJournals[index]['acceptance'] * 100).toFixed(2) > 3 && (this.suggestedJournals[index]['acceptance'] * 100).toFixed(2) <= 5) {
                            dataCell.innerHTML = '3-5%';
                        }
                        else {
                            dataCell.innerHTML = '>5%';
                        }
                        dataRow.appendChild(dataCell);
                    }
                    else if (pos == 4) {
                        if (parseInt(this.suggestedJournals[index]['volume']) >= 0 && parseInt(this.suggestedJournals[index]['volume']) <= 10) {
                            dataCell.innerHTML = 'very low'
                        }
                        else if (parseInt(this.suggestedJournals[index]['volume']) > 10 && parseInt(this.suggestedJournals[index]['volume']) <= 50) {
                            dataCell.innerHTML = 'low'
                        }
                        else if (parseInt(this.suggestedJournals[index]['volume']) > 50 && parseInt(this.suggestedJournals[index]['volume']) <= 100) {
                            dataCell.innerHTML = 'med'
                        }
                        else if (parseInt(this.suggestedJournals[index]['volume']) > 100 && parseInt(this.suggestedJournals[index]['volume']) <= 200) {
                            dataCell.innerHTML = 'high'
                        }
                        else {
                            dataCell.innerHTML = 'very high'
                        }
                        dataRow.appendChild(dataCell);
                    }
                })
                tempTableBody.appendChild(dataRow);
            });

            tempTable.setAttribute('style', 'border-collapse: collapse; border: 1px solid black;');
            tempTable.appendChild(tempTableBody);
            const tempCells = tempTable.querySelectorAll('td, th');
            tempCells.forEach(cell => {
                cell.style.border = '1px solid black';
                cell.style.padding = '5px';
            });

            document.body.appendChild(tempTable);

            const range = document.createRange();
            range.selectNode(tempTable);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);

            document.execCommand('copy');
            window.getSelection().removeAllRanges();

            document.body.removeChild(tempTable);
            this.$store.state.isAccepted = true
            this.$store.state.notificationData = 'Copied Successfully'
            this.getTasks();
            setTimeout(() => {
                this.$store.state.isAccepted = false
                this.$store.state.notificationData = null
            }, 2500)
        },
        previewImage(event) {
            this.id.push(this.i++);
            var input = event.target;
            if (input.files) {
                var reader = new FileReader();
                reader.onload = (e) => {
                    this.preview.push(e.target.result);
                };
                this.image.push(input.files[0]);
                reader.readAsDataURL(input.files[0]);
            }
        },
        editSubmissionData(id) {
            axios
                .post(this.url + "api/editSubmissionData", {
                    id: id,
                })
                .then((res) => {
                    this.createdDate = res.data.created_at
                    this.status = res.data.status
                    this.journalEditSts = true
                })
        },
        undoRunTask() {
            // if (this.undoRunTasks.length > 0) {
            //     const runTask = this.undoRunTasks.pop();
            //     this.progressTasks.push(runTask);
            //     this.progressTasks.sort((a, b) => (a.date > b.date) ? 1 : -1)
            //     this.undoRunBtn = false;
            //     this.getTasks();

            // } else {
            this.undoRunBtn = false;

            // }

        },
        runTask(id) {
            let tasks = this.progressTasks
            this.progressTasks = tasks.filter((task) => {
                if (task.id == id) {
                    task.status = 3
                }
                return task
            })

            this.undoRunBtn = true;
            setTimeout(() => {
                if (this.undoRunBtn !== false) {
                    axios.post(this.url + 'api/runTask', { id: id }).then((res) => {
                        if(res.data=="1")
                        {
                            this.progressTasks = tasks.filter((task) => {
                                if (task.id == id) {
                                    task.status = 3
                                }
                                return task
                            })
                            this.$store.state.isAccepted = true
                            this.$store.state.notificationData = 'Task Email Has Been Send Successfully'
                            this.undoRunBtn = false;
                            this.getTasks()
                            setTimeout(() => {
                                this.$store.state.isAccepted = false
                                this.$store.state.notificationData = null
                            }, 2500)
                        }
                        else
                        {
                            this.progressTasks = tasks.filter((task) => {
                                if (task.id == id) {
                                    task.status = 2
                                }
                                return task
                            })
                            this.$store.state.isDeclined = true
                            this.$store.state.notificationData = 'Something Went Wrong'
                            this.undoRunBtn = false;
                            this.getTasks()
                            setTimeout(() => {
                                this.$store.state.isDeclined = false
                                this.$store.state.notificationData = null
                            }, 2500)
                        }
                      
                    })
                }
            }, 5000)
        },
        getStory() {
            let data = new FormData();
            data.append("id", this.decode(this.$route.params.id));
            axios.post(this.url + "api/getSingleStory", data).then((res) => {
                this.story_id = res.data.story.id
                this.story = res.data.story;
                this.superPackage = res.data.superPackage
                this.extraServices = res.data.extraServices
                if (res.data.storyScore !== null) {
                    this.algoRun = true
                    this.storyScore = res.data.storyScore;
                    this.getSuggestedJournals();
                }
                this.getDoNotSubmitList();
                this.getDoNotSubmitJournals()
            });
        },
        getDoNotSubmitJournals() {
            axios.post(this.url + 'api/getDoNotSubmitJournals', { id: this.story.user_id, storyId: this.decode(this.$route.params.id) }).then(res => {
                this.doNotSubmitList = res.data.journals
                this.rejectMainJournals = res.data.otherJournals
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
        addTask() {
            this.submitted = true;
            this.v$.$touch();
            if (this.v$.$invalid) {
                return;
            } else {
                this.task.story_id = this.decode(this.$route.params.id);
                if (this.edit == true) {
                    this.task.enabledDate = this.oldTaskCompletionDate
                    axios.post(this.url + "api/updateTask", this.task).then(() => {
                        this.modalTaskAdd = false
                        this.submitted = false
                        this.$store.state.isAccepted = true
                        this.$store.state.notificationData = 'Task Updated Successfully'
                        this.getTasks();
                        setTimeout(() => {
                            this.$store.state.isAccepted = false
                            this.$store.state.notificationData = null
                        }, 2500)
                    });
                } else {
                    axios.post(this.url + "api/addTask", this.task).then(() => {
                        this.modalTaskAdd = false
                        this.submitted = false
                        this.$store.state.isAccepted = true
                        this.$store.state.notificationData = 'Task Added Successfully'
                        this.getTasks();
                        setTimeout(() => {
                            this.$store.state.isAccepted = false
                            this.$store.state.notificationData = null
                        }, 2500)
                    });
                }
            }
        },
        getTasks() {
            this.isDue = 0
            this.editButton =true
            this.editId = null
            this.oldTaskCompletionDate = new Date()
            let data = new FormData();
            data.append("story_id", this.decode(this.$route.params.id));
            axios.post(this.url + "api/getTasks", data).then((res) => {
                this.progressTasks = res.data;
                this.progressTasks = this.progressTasks.map(obj => ({
                    ...obj,
                    isDue: this.shouldDisplayDue(obj),
                    isEditable: this.shouldEditable(obj)
                }));
                this.progressTasks = this.progressTasks.map(obj => {
                    if (obj.id === this.editId) {
                        return { ...obj, isEditable: true };
                    } else {
                        return { ...obj, isEditable: false };
                    }
                });
                this.dataFetched = true;
            });



        },
        storeStoryScore() {
            this.storyScore.story_id = this.decode(this.$route.params.id);
            axios.post(this.url + "api/storeStoryScore", this.storyScore).then(() => {
                this.algoRun = true
                this.getSuggestedJournals();
            });
        },
        getAllJournals() {
            let data = new FormData()
            data.append('storyId', this.decode(this.$route.params.id))
            axios.post(this.url + 'api/getJournalsWithScore', data).then(response => {
                let data = new FormData()
                data.append('id', this.decode(this.$route.params.id))
                axios.post(this.url + 'api/getStorySubmissionResult', data).then(res => {
                    let submittedJournals = res.data
                    let allJournals = response.data
                    this.unmutableJournal = filterByReference(allJournals, submittedJournals)
                    this.unmutableJournal = this.unmutableJournal.filter((element) => {
                        return !this.doNotSubmitList.map((item) => item.id).includes(element.id);
                    });
                    this.submissionResult = res.data
                    this.submissionResult.sort((a, b) => (a.matchPercent < b.matchPercent && a.round < b.round) ? 1 : -1)
                    this.getJournalByMatch()
                    this.getAddMoreJournal();

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
            if (this.algoRun == true) {
                this.getSuggestedJournals()
            }
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
             
                this.journals[i].story_id = this.decode(this.$route.params.id);
            }
            this.journals.sort((a, b) => (a.matchScore < b.matchScore) ? 1 : -1)
           
            let selectedIds = this.selectedJournals.flat().map((item) => item.id);
            let journals1 = this.journals.filter((journal) => {
                return !selectedIds.includes(journal.id);
            });
            let rejectIds = this.rejectMainJournals.flat().map((item) => item.id);
            let journals2 = this.journals.filter((journal) => {
                return !rejectIds.includes(journal.id);
            });
            this.journals = this.journals.filter((journal) => {
                return !rejectIds.includes(journal.id);
            });
            journals2.forEach((journal)=>{
                this.$store.state.rejectJournals.push(journal)
            })
            journals1.forEach((journal) => {
                this.$store.state.otherJournals.push(journal);
            });

            if (this.selectedJournals.length > 0) {
                let selectedJournals = this.unmutableselectedJournals;
                for (var j = 0; j < selectedJournals.length; j++) {
                    let mainJournal = this.journals.filter((element) => {
                        return selectedJournals[j].map((item) => item.id).includes(element.id);
                    });
                    
                    this.selectedJournals = mainJournal
                    mainJournal.filter((journal) =>
                        this.suggestedJournals.push(journal)
                    );
                    this.suggestedJournals.sort((a, b) => (a.matchScore < b.matchScore) ? 1 : -1)
                }
            }
            this.getClientLimit(this.story.user_id);
        },
        storySubmission(id, matchPercent) {
            this.submitToJournal(id, matchPercent)
        },
        removeSubmissionJournal(id) {
            this.submissionResult = this.submissionResult.filter(
                (item) => item.id !== id
            )
            axios.post(this.url + 'api/removeSubmissionJournal', { id: id }).then(() => {
                this.getStory()
            })
        },
        rejectSubmissionJournal(id) {
            this.submissionResult = this.submissionResult.map(item=>
            {
                if(item.id==id)
                {
                    return ({ ...item, status: 3, updated_at:new Date() })
                }
                else
                {
                    return item
                }
            })
            axios.post(this.url + 'api/rejectSubmissionJournal', { id: id })
        },
        submitToJournal(id, matchPercent) {
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
                // let journals = this.$store.state.selectedJournals
                // this.$store.state.selectedJournals = journals.filter((journal) => {
                //     return journal.id != id
                // })
                let journals = this.selectedJournals
                this.selectedJournals = journals.filter((journal) => {
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
        dateSelected(date) {
            var getYear = date.toLocaleString("default", { year: "numeric" });
            var getMonth = date.toLocaleString("default", { month: "2-digit" });
            var getDay = date.toLocaleString("default", { day: "2-digit" });
            this.submissionDate = getYear + "-" + getMonth + "-" + getDay;
        },
        createdDateSelected(date) {
            var getYear = date.toLocaleString("default", { year: "numeric" });
            var getMonth = date.toLocaleString("default", { month: "2-digit" });
            var getDay = date.toLocaleString("default", { day: "2-digit" });
            this.createdDate = getYear + "-" + getMonth + "-" + getDay;
        },
        completionDateSelected(date) {
            var getYear = date.toLocaleString("default", { year: "numeric" });
            var getMonth = date.toLocaleString("default", { month: "2-digit" });
            var getDay = date.toLocaleString("default", { day: "2-digit" });
            this.task.date = getYear + "-" + getMonth + "-" + getDay;
        },
        updateSubmissionData() {
            this.journalEditSts = false
            axios
                .post(this.url + "api/updateSubmissionData", {
                    id: this.editSubmissionId,
                    status: this.status,
                    date: this.submissionDate,
                    createdDate: this.createdDate
                })
                .then(() => {
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = 'Status Updated Successfully'
                    this.getDoNotSubmitJournals();
                    this.getSuggestedJournals();
                    this.getClientLimit(this.story.user_id);
                    this.getJournalByMatch();
                    setTimeout(() => {
                        this.$store.state.isAccepted = false
                        this.$store.state.notificationData = null
                    }, 2500)
                });
        },
        getJournalByAlgorithm() {
            let journals = this.submissionResult;
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

        removeSuggestedJournal(key) {

            // this.$store.state.selectedJournals = this.$store.state.selectedJournals.filter(
            //     (item) => item.id !== key
            // )

            this.selectedJournals = this.selectedJournals.filter(
                (item) => item.id !== key
            )
            this.suggestedJournals = this.suggestedJournals.filter(
                (item) => item.id !== key
            )
            axios.post(this.url + 'api/removeAddMoreJournal', { id: key, storyId: this.decode(this.$route.params.id)}).then((res) => {
                // this.getStory();
                this.selectedJournals = res.data
                this.unmutableselectedJournals = res.data
                this.getSuggestedJournals();
            })
        },
        editTask(id) {
            this.edit = true;
            this.titleTask = false;
            axios.post(this.url + "api/editTask", { id: id }).then((res) => {
                this.task = res.data;
            });
        },
        updateTask() {
            this.submitted = true;
            this.v$.$touch();
            if (this.v$.$invalid) {
                return;
            } else {
                axios.post(this.url + "api/updateTask", this.task).then(() => {
                    this.$store.state.isAccepted = true
                    this.$store.state.notificationData = 'Task Updated Successfully'
                    this.getTasks();
                    setTimeout(() => {
                        this.$store.state.isAccepted = false
                        this.$store.state.notificationData = null
                    }, 2500)
                });
            }
        },
        undoDelete() {
            if (this.deleteItem.length > 0) {
                const deletedItem = this.deleteItem.pop();
                this.progressTasks.push(deletedItem);
                this.progressTasks.sort((a, b) => (a.date > b.date) ? 1 : -1)
                this.undoBtn = false;
            } else {
                this.undoBtn = false;
            }

        },
        deleteTask(id) {
            const objWithIdIndex = this.progressTasks.findIndex((obj) => obj.id === id);
            if (objWithIdIndex > -1) {
                const deletedItem = this.progressTasks.splice(objWithIdIndex, 1)[0];
                this.deleteItem.push(deletedItem);
            }
            this.undoBtn = true;
            setTimeout(() => {
                for (var i = 0; i < this.deleteItem.length; i++) {
                    axios.post(this.url + "api/deleteTask", { id: this.deleteItem[i].id }).then(() => {
                        this.$store.state.isDeclined = true
                        this.$store.state.notificationData = 'Task Deleted Successfully'
                        this.undoBtn = false;
                        this.getTasks();
                        setTimeout(() => {
                            this.$store.state.isDeclined = false
                            this.$store.state.notificationData = null
                        }, 2000)
                    });
                }
            }, 5000)

        },
        changeTaskOrder() {
            let tasks = {
                id: this.decode(this.$route.params.id),
                changeOrder: [],
            };
            tasks.changeOrder = this.progressTasks;
            axios.post(this.url + "api/changeTaskOrder", tasks).then(() => {
                this.$store.state.isAccepted = true
                this.$store.state.notificationData = 'Task Updated Successfully'
                this.getTasks();
                setTimeout(() => {
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = null
                }, 2500)
            });
        },
        addExtraService() {
            this.service.story_id = this.decode(this.$route.params.id)
            this.service.user_id = this.story.user_id
            this.extraServiceAdd = false
            axios.post(this.url + 'api/addExtraService', this.service).then(() => {
                this.$store.state.isAccepted = true
                this.$store.state.notificationData = 'Payment Email Has Been Send Successfully'
                this.service.title = null
                this.service.amount = null
                this.getStory()
                setTimeout(() => {
                    this.$store.state.isAccepted = false
                    this.$store.state.notificationData = null
                }, 2500)
            })
        },
        addRound3Task() {
            axios
                .post(this.url + "api/addRound3", {
                    story_id: this.decode(this.$route.params.id),
                })
                .then(() => {
                    this.$store.state.isAccepted = true
                    this.$store.state.notificationData = 'Task for Round 3 Added Successfully'
                    setTimeout(() => {
                        this.$store.state.isAccepted = false
                        this.$store.state.notificationData = null
                    }, 2500)
                    this.getTasks();
                });
        },
        async getAddMoreJournal() {
            let journals = [];
            if(this.$store.state.normalStore.addMoreJournals.length)
            {
                 journals = this.$store.state.normalStore.addMoreJournals.filter(item => item.story_id == this.decode(this.$route.params.id))
                .reduce((result, item) => {
                    const existingArray = result.find(arr => arr.length > 0 && arr[0].story_id === item.story_id);
                    if (existingArray) {
                    existingArray.push(item);
                    } else {
                    result.push([item]);
                    }
                    return result;
                }, []);
            }
            else
            {
                const response = await axios.post(this.url + 'api/getAddMoreJournal', { story_id: this.decode(this.$route.params.id) })
                journals = response.data
            }
            this.selectedJournals = journals;
            this.unmutableselectedJournals = journals;
            this.getJournalByMatch();
        },
        date(date) {
            this.submissionDate = date;
        },
        getMinutes(minute) {
            if (parseInt(minute) <= 9) {
                return "0" + minute;
            } else {
                return minute;
            }
        },
        resetInputField() {
            this.editId = null
            this.editButton = true
            this.isDue = 0
            this.showDue = false
            this.titleTask = true;
            this.task = {
                title: "",
                date: new Date(),
                type: "Manual",
                emailTemplate: 1,
                story_id: null,
            };
            this.journals = [],
                this.editStory = false
            this.storyScore = {
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
            }
            this.algoRun = false
            this.deleteItem = []
        },
        shouldDisplayDue(task) {

            if (this.isDue == 0 && new Date() > new Date(task.date) && task.status == 2) {
                this.isDue = 1
                return true
            }
            return false
        },
        shouldEditable(task)
        {
            if(task.status==1 && this.editButton==true)
            {
                this.editId = task.id
                const previousTaskIndex = this.progressTasks.indexOf(task) - 1;
                if (previousTaskIndex >= 0) {
                    const previousTask = this.progressTasks[previousTaskIndex];
                    this.oldTaskCompletionDate = previousTask.date
                    var date = new Date(previousTask.date)
                    this.enabledDate =  date.setDate(date.getDate())
                }
            }
            else
            {
                this.editButton = false
            }
        },
        encode(id) {
            return encodeURIComponent(
                CryptoJS.AES.encrypt(String(id), "Secret Passphrase")
            );
        },
        decode(id) {
            return decodeURIComponent(
                CryptoJS.AES.decrypt(String(id), "Secret Passphrase").toString(
                    CryptoJS.enc.Utf8
                )
            );
        },
    },
    computed: {
        progressTaskss: {
            get() {
                return this.$store.state.elements;
            },
            set(value) {
                this.$store.dispatch("updateElements", value);
            },
        },
        clientInfo()
        {
            return this.$store.state.normalStore.clients.find(item=>item.id==this.story.user_id)
        },
        emailTemplates()
        {
            return this.$store.state.normalStore.emailTemplates
        }
    },
};
</script>
