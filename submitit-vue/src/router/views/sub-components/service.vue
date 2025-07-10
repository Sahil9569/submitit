<template>
    <div class="row gx-3">
        <div class="col-lg-4 mb-3 mb-lg-0" v-if="isSignUp">
            <div class="package-card-main light-blue-pack">
                <h3 class="form-label font-size-15 font-weight-600 d-flex align-items-center"><i
                        class="mdi mdi-file-certificate text-theme-dark font-size-24 me-1"></i>Submission Packages</h3>
                <label :for="`sub${storyPackage.id}`" class="mb-0 p-relative labelFieldCol"
                    v-for="storyPackage in getSubmissionPackage()" :key="storyPackage.id">
                    <input type="radio" name="submission" v-model="submission" :value="storyPackage.stripe_package_id" class="inputRadioCstm d-none" :id="`sub${storyPackage.id}`"
                        @click="$emit('submissionPackage', storyPackage.stripe_package_id)" />
                    <span class="radioCircleIcon">
                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                        <i class="mdi mdi-checkbox-marked-circle text-theme font-size-20"></i>
                    </span>
                    <div class="row flex-1 align-items-center flex-nowrap">
                        <div class="col">
                            <h4 class="text-name-journl mb-0">{{ storyPackage.title }}</h4>
                        </div>
                        <div class="col-auto">
                            <h3 class="price-style-cstm mb-0">${{ storyPackage.amount }}</h3>
                        </div>
                    </div>
                </label>
                <button @click="uncheckSubmission"  style="background-color: white;" class="btn btn-light w-100 btn-sm mt-2" type="button"> Clear Selection </button>
            </div>
        </div>
        <div class="col-lg-4 mb-3  mb-lg-0" v-if="isSignUp">
            <div class="package-card-main light-warn-pack">
                <h3 class="form-label font-size-15 font-weight-600 d-flex align-items-center"><i
                        class="mdi mdi-flash text-theme-dark font-size-24 me-1"></i>Flash Fiction Packages</h3>
                <label :for="`sub${fictionPackage.id}`" class="mb-0 p-relative labelFieldCol"
                    v-for="fictionPackage in getFictionPackage()" :key="fictionPackage.id">
                    <input type="radio" name="fiction" class="inputRadioCstm d-none" v-model="fiction" :value="fictionPackage.stripe_package_id" :id="`sub${fictionPackage.id}`"
                        @click="$emit('fictionPackage', fictionPackage.stripe_package_id)" />
                    <span class="radioCircleIcon">
                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                        <i class="mdi mdi-checkbox-marked-circle text-theme font-size-20"></i>
                    </span>
                    <div class="row flex-1 align-items-center flex-nowrap">
                        <div class="col">
                            <h4 class="text-name-journl mb-0">{{ fictionPackage.title }}</h4>
                        </div>
                        <div class="col-auto">
                            <h3 class="price-style-cstm mb-0">${{ fictionPackage.amount }}</h3>
                        </div>
                    </div>
                </label>
                <button @click="uncheckFiction"  style="background-color: white;" class="btn btn-light w-100 btn-sm mt-2" type="button"> Clear Selection </button>
            </div>
        </div>
        <div class="col-lg-4 mb-3  mb-lg-0" v-if="isSignUp">
            <div class="package-card-main light-success-pack">
                <h3 class="form-label font-size-15 font-weight-600 d-flex align-items-center"><i
                        class="mdi mdi-account-supervisor-circle text-theme-dark font-size-24 me-1"></i>Editing & Consulting
                    <sup class="subContent">(discounts may apply)</sup>
                </h3>
                <label :for="`sub${editingPackage.id}`" class="mb-0 p-relative labelFieldCol"
                    v-for="editingPackage in getEditingPackage()" :key="editingPackage.id">
                    <input type="radio" name="editingPack" v-model="editing" :value="editingPackage.stripe_package_id" class="inputRadioCstm d-none" :id="`sub${editingPackage.id}`"
                        @click="$emit('editingPackage', editingPackage.stripe_package_id)"
                    />
                    <span class="radioCircleIcon">
                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                        <i class="mdi mdi-checkbox-marked-circle text-theme font-size-20"></i>
                    </span>
                    <div class="row flex-1 align-items-center flex-nowrap">
                        <div class="col">
                            <h4 class="text-name-journl mb-0">{{ editingPackage.title }}</h4>
                        </div>
                        <div class="col-auto">
                            <h3 class="price-style-cstm mb-0">${{ editingPackage.amount }}/<span
                                    class="font-size-12 font-weight-500"><span
                                        v-if="editingPackage.amount == 100 || editingPackage.amount==10">/hour</span><span v-else>1,000
                                        words</span></span>
                            </h3>
                        </div>
                    </div>
                </label>
                <button @click="uncheckEditing" style="background-color: white;" class="btn btn-light w-100 btn-sm mt-2" type="button"> Clear Selection </button>
            </div>
        </div>
        <div class="col-lg-4 mb-3  mb-lg-0" v-if="!isSignUp">
            <div class="package-card-main light-warn-pack">
                <h3 class="form-label font-size-15 font-weight-600 d-flex align-items-center"><i
                        class="mdi mdi-flash text-theme-dark font-size-24 me-1"></i>Super Packages</h3>
                <label :for="`sub${superPackage.id}`" class="mb-0 p-relative labelFieldCol"
                    v-for="superPackage in getSuperPackage()" :key="superPackage.id">
                    <input type="radio" name="fiction" v-model="superSelect" :value="superPackage.stripe_package_id" class="inputRadioCstm d-none" :id="`sub${superPackage.id}`"
                        @click="$emit('superPackage', superPackage.stripe_package_id)" />
                    <span class="radioCircleIcon">
                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                        <i class="mdi mdi-checkbox-marked-circle text-theme font-size-20"></i>
                    </span>
                    <div class="row flex-1 align-items-center flex-nowrap">
                        <div class="col">
                            <h4 class="text-name-journl mb-0">{{ superPackage.title }}</h4>
                        </div>
                        <div class="col-auto">
                            <h3 class="price-style-cstm mb-0">${{ superPackage.amount }}</h3>
                        </div>
                    </div>
                </label>
                <button @click="uncheckSuper" class="btn btn-danger w-100 btn-sm mt-2" type="button"> Clear Selection </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default ({

    data() {
        return {
            url: process.env.VUE_APP_URL,
            packages: [],
            submission: null,
            fiction: null,
            editing: null,
            superSelect: null,
        }
    },
    props:['isSignUp'],
    created() {
        this.getPackages()
    },
    activated() {
        this.getPackages()
        this.submission = null
        this.fiction = null
        this.editing = null
        this.superSelect = null
    },
    methods: {
        uncheckSubmission(){
            this.submission = false;
            this.$emit('submissionPackage', null)
        },
        uncheckFiction(){
            this.fiction = false;
            this.$emit('fictionPackage', null)
        },
        uncheckEditing(){
            this.editing = false;
            this.$emit('editingPackage', null)
        },
        uncheckSuper(){
            this.superSelect = false;
            this.$emit('superPackage', null)
        },
        getPackages() {
            axios.get(this.url + 'api/getPackages').then(res => {
                this.packages = res.data
            })
        }
    },
    computed: {
        getSubmissionPackage() {
            return () => this.packages.filter(storyPackage => storyPackage.type == 'submission')
        },
        getFictionPackage() {
            return () => this.packages.filter(storyPackage => storyPackage.type == 'flash')
        },
        getEditingPackage() {
            return () => this.packages.filter(storyPackage => storyPackage.type == 'editing')
        },
        getSuperPackage() {
            return () => this.packages.filter(storyPackage => storyPackage.type == 'super')
        }
    }
})
</script>