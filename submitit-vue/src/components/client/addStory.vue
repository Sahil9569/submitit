<template>
    <layout>
        <PageHeader :title="title ? 'Add Story' : 'Update Story'" />
        <div class="card">
            <div class="main-from-group card-body">
                <form enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-5 col-xl-4">
                            <div class="d-block mb-3">
                                <label for="essay-title-input">Story or Essay Title<sup>("TBD" if undecided)</sup>
                                    *</label>
                                <input id="essay-title-input" v-model="story.storyTitle" type="text" class="form-control"
                                    placeholder="Story or Essay Title" :class="{
                                        'is-invalid': submitted && v$.story.storyTitle.$error,
                                    }" />
                                <div v-if="submitted && v$.story.storyTitle.$error" class="invalid-feedback">
                                    <span v-if="v$.story.storyTitle.required.$message">
                                        {{ v$.story.storyTitle.required.$message }}
                                    </span>
                                </div>
                            </div>
                            <div class="d-block mb-3">
                                <label for="word-count-input">Approx. Word Count</label>
                                <input id="word-count-input" type="number" class="form-control" placeholder="Word Count"
                                    v-model="story.wordCount"
                                    :class="{ 'is-invalid': submitted && v$.story.wordCount.$error, }" />
                                <div v-if="submitted && v$.story.wordCount.$error" class="invalid-feedback">
                                    <span v-if="v$.story.wordCount.required.$message">{{
                                        v$.story.wordCount.required.$message }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-xl-8">
                            <div class="">
                                <h4 class="font-size-14">Genre *</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="genreLitery" class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                                            <input type="radio" name="LiteryFiction" class="inputRadioCstm d-none"
                                                id="genreLitery" v-model="story.storyGenre" value="fiction" :class="{
                                                    'is-invalid': submitted && v$.story.storyGenre.$error,
                                                }" />
                                            <span class="radioCircleIcon">
                                                <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                                                <i
                                                    class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                                            </span>
                                            <h4 class="font-size-12 mb-0 ms-2">
                                                Literary Fiction
                                                <sup>(short stories and flash fiction)</sup>
                                            </h4>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="genreNonLitery"
                                            class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                                            <input type="radio" name="LiteryFiction" class="inputRadioCstm d-none"
                                                id="genreNonLitery" v-model="story.storyGenre" value="nonfiction" :class="{
                                                    'is-invalid': submitted && v$.story.storyGenre.$error,
                                                }" />
                                            <span class="radioCircleIcon">
                                                <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                                                <i
                                                    class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                                            </span>
                                            <h4 class="font-size-12 mb-0 ms-2">
                                                Creative Nonfiction <sup>(essays)</sup>
                                            </h4>
                                        </label>
                                    </div>
                                    <div v-if="submitted && v$.story.storyGenre.$error"
                                        class="help text-danger font-size-11">
                                        <span v-if="v$.story.storyGenre.required.$message">
                                            {{ v$.story.storyGenre.required.$message }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-block mt-2">
                                    <label class="mb-0 font-size-12" for="additional-note font-weight-400">Additional notes,
                                        including journals you have already
                                        submitted this piece to.</label>
                                    <textarea id="additional-note" type="textarea" class="form-control resize-none"
                                        v-model="story.notesFromClient"
                                        :class="{ 'is-invalid': submitted && v$.story.notesFromClient.$error, }"></textarea>
                                    <div v-if="submitted && v$.story.notesFromClient.$error" class="invalid-feedback">
                                        <span v-if="v$.story.notesFromClient.required.$message">{{
                                            v$.story.notesFromClient.required.$message }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label font-size-15">Upload Files </label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group" v-if="!data[0]">
                                        <template v-if="!id.includes('1')">
                                            <label for="my-file" class="labelUploadFile">
                                                <input type="file" accept="image/*" @change="previewImage($event, '1')"
                                                    class="form-control-file" id="my-file" />
                                                <div class="uploadImageFile">
                                                    <span><i class="mdi mdi-arrow-up-circle"></i></span>
                                                    Upload File
                                                </div>
                                            </label>
                                            <span class="text-danger font-size-12 d-block text-center">doc or docx (Max
                                                Size:
                                                15MB)</span>
                                        </template>
                                        <template v-if="id.includes('1')">
                                            <div class="p-2 mb-3 bg-success bg-soft">
                                                <div class="row gx-2 align-items-center">
                                                    <div class="col">
                                                        <p class="mb-0 text-success font-size-14">
                                                            file name: {{ image[id.indexOf("1")].name }}
                                                        </p>
                                                        <p class="mb-0 text-success font-size-14">
                                                            size: {{ image[id.indexOf("1")].size / 1024 }}KB
                                                        </p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="">
                                                            <button @click="reset('1')" class="border-0 p-0 bg-transparent"
                                                                type="button">
                                                                <i class="mdi mdi-delete font-size-20 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="form-group" v-else>
                                        <template v-if="data[0]">
                                            <div class="p-2 mb-3 bg-success bg-soft">
                                                <div class="row gx-2 align-items-center">
                                                    <div class="col">
                                                        <p class="mb-0 text-success font-size-14">
                                                            file name: {{ data[0] }}
                                                        </p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="">
                                                            <button @click="reset('1')" class="border-0 p-0 bg-transparent"
                                                                type="button">
                                                                <i class="mdi mdi-delete font-size-20 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" v-if="!data[1]">
                                        <template v-if="!id.includes('2')">
                                            <label for="file-upload-input" class="labelUploadFile">
                                                <input type="file" accept="image/*" @change="previewImage($event, '2')"
                                                    class="form-control-file" id="file-upload-input" />
                                                <div class="uploadImageFile">
                                                    <span><i class="mdi mdi-arrow-up-circle"></i></span>
                                                    Upload File
                                                </div>
                                            </label>
                                            <span class="text-danger font-size-12 d-block text-center">Flash fiction only (2
                                                of
                                                3)</span>
                                        </template>
                                        <template v-if="id.includes('2')">
                                            <div class="p-2 mb-3 bg-success bg-soft">
                                                <div class="row gx-2 align-items-center">
                                                    <div class="col">
                                                        <p class="mb-0 text-success font-size-14">
                                                            file name: {{ image[id.indexOf("2")].name }}
                                                        </p>
                                                        <p class="mb-0 text-success font-size-14">
                                                            size: {{ image[id.indexOf("2")].size / 1024 }}KB
                                                        </p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="">
                                                            <button @click="reset('2')" class="border-0 p-0 bg-transparent"
                                                                type="button">
                                                                <i class="mdi mdi-delete font-size-20 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="form-group" v-else>
                                        <template v-if="data[1]">
                                            <div class="p-2 mb-3 bg-success bg-soft">
                                                <div class="row gx-2 align-items-center">
                                                    <div class="col">
                                                        <p class="mb-0 text-success font-size-14">
                                                            file name: {{ data[1] }}
                                                        </p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="">
                                                            <button @click="reset('2')" class="border-0 p-0 bg-transparent"
                                                                type="button">
                                                                <i class="mdi mdi-delete font-size-20 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" v-if="!data[2]">
                                        <template v-if="!id.includes('3')">
                                            <label for="file-upload-input1" class="labelUploadFile">
                                                <input type="file" accept="image/*" @change="previewImage($event, '3')"
                                                    class="form-control-file" id="file-upload-input1" />
                                                <div class="uploadImageFile">
                                                    <span><i class="mdi mdi-arrow-up-circle"></i></span>
                                                    Upload File
                                                </div>
                                            </label>
                                            <span class="text-danger font-size-12 d-block text-center">Flash fiction only (3
                                                of
                                                3)</span>
                                        </template>
                                        <template v-if="id.includes('3')">
                                            <div class="p-2 mb-3 bg-success bg-soft">
                                                <div class="row gx-2 align-items-center">
                                                    <div class="col">
                                                        <p class="mb-0 text-success font-size-14">
                                                            file name: {{ image[id.indexOf("3")].name }}
                                                        </p>
                                                        <p class="mb-0 text-success font-size-14">
                                                            size: {{ image[id.indexOf("3")].size / 1024 }}KB
                                                        </p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="">
                                                            <button @click="reset('3')" class="border-0 p-0 bg-transparent"
                                                                type="button">
                                                                <i class="mdi mdi-delete font-size-20 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="form-group" v-else>
                                        <template v-if="data[2]">
                                            <div class="p-2 mb-3 bg-success bg-soft">
                                                <div class="row gx-2 align-items-center">
                                                    <div class="col">
                                                        <p class="mb-0 text-success font-size-14">
                                                            file name: {{ data[2] }}
                                                        </p>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="">
                                                            <button @click="reset('3')" class="border-0 p-0 bg-transparent"
                                                                type="button">
                                                                <i class="mdi mdi-delete font-size-20 text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="((parseInt(storyLimit) != 0) && !customPackage.includes(superPackage))">
                        <h4 class="font-size-14">
                            Do You want to Proceed with your old Package ?
                        </h4>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="useAvailabelPackage" class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                                    <input type="radio" name="useAvailablePackage" v-model="story.useAvailablePackage"
                                        class="inputRadioCstm d-none" id="useAvailabelPackage" :value="true" />
                                    <span class="radioCircleIcon">
                                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                                        <i class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                                    </span>
                                    <h4 class="font-size-12 mb-0 ms-2">Yes</h4>
                                </label>
                            </div>

                            <div class="col-md-6">
                                <label for="nonUseAvailabelPackage"
                                    class="mb-0 p-relative border w-100 labelStyleRadioCstm">
                                    <input type="radio" name="useAvailablePackage" v-model="story.useAvailablePackage"
                                        class="inputRadioCstm d-none" id="nonUseAvailabelPackage" :value="false" />
                                    <span class="radioCircleIcon">
                                        <i class="mdi mdi-circle-outline text-theme font-size-20"></i>
                                        <i class="mdi mdi-checkbox-marked-circle text-theme theme-check font-size-20"></i>
                                    </span>
                                    <h4 class="font-size-12 mb-0 ms-2">No</h4>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2" v-if="((!this.$route.params.id) && !customPackage.includes(superPackage))">
                        <template v-if="story.useAvailablePackage == false">
                            <div class="innerGroupFeilds p-2 p-md-3 mb-4">
                                <h2 class="main-title-head">Services</h2>
                                <ServiceSub :isSignUp="true" @submissionPackage="setSubmissionPackage"
                                    @fictionPackage="setFictionPackage" @editingPackage="setEditingPackage" />
                            </div>
                        </template>
                    </div>
                    <div class="mb-2">
                        <div class="mt-4 text-end">
                            <label class="labelCheckboxCstm">
                                <input type="checkbox" name="agreeAccept" v-model="agreeAccept" />
                                <span class="checkmark pe-1">
                                    <i class="mdi mdi-checkbox-blank-outline font-size-20 text-danger"
                                        v-if="this.v$.agreeAccept.$error"></i>
                                    <i class="mdi mdi-checkbox-blank-outline font-size-20 text-theme" v-else></i>
                                    <i class="mdi mdi-checkbox-marked-outline font-size-20 text-theme"></i>
                                </span>
                                I agree to Submitit's
                                <a href="https://www.submititnow.com/terms-of-service" class="text-theme ps-1">
                                    Terms of Service.</a>
                            </label>
                        </div>
                    </div>
                    <div class="text-end mt-2" v-if="!this.$route.params.id">
                        <button type="button" @click="submitStory" class="btn btn-theme" v-if="!this.v$.$error">
                            Submitit
                        </button>
                        <button type="button" class="btn" style="background-color: #dae8eb; text-color: #ffffff" v-else>
                            Submitit
                        </button>
                    </div>
                    <div class="text-end mt-2" v-else>
                        <button type="button" @click.prevent="updateStory" class="btn btn-theme">
                            Update 
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </layout>
</template>
<script>
import Layout from "../../router/layouts/main";
import PageHeader from "@/components/admin/header/page-header";
// import Select2 from 'vue3-select2-component';
import ServiceSub from "../../router/views/sub-components/service";
import axios from "axios";
import _ from "lodash";
import CryptoJS from "crypto-js";
import useVuelidate from "@vuelidate/core";
import { required, helpers } from "@vuelidate/validators";

export default {
    page: {
        title: "Add Story",
        meta: [
            {
                name: "description",
                content: "description",
            },
        ],
    },

    data() {
        return {
            title: true,
            url: process.env.VUE_APP_URL,
            v$: useVuelidate(),
            id: [],
            data: [],
            preview: [],
            image: [],
            newImage: [],
            submitted: false,
            i: 0,
            storyLimit: 0,
            agreeAccept: false,
            story: {
                clientName: "",
                storyTitle: "",
                storyGenre: "",
                finalVersionSaved: false,
                wordCountChecked: false,
                useAvailablePackage: false,
                wordCount: "",
                notesFromClient: "",
                files: [],
                submissionPackageId: null,
                fictionPackageId: null,
                editingPackageId: null,
                user_id: null,
            },
            superPackage: null,
            customPackage: ["price_1N90ymSGmK7KROFRZP7BnS5g", "price_1N90zySGmK7KROFRfWPL7fVn", "price_1N910nSGmK7KROFRFEsmDeW0"]
        };
    },
    validations: {
        story: {
            storyTitle: {
                required: helpers.withMessage("Story Title is required", required),
            },
            storyGenre: {
                required: helpers.withMessage("Story Genre is required", required),
            },
            wordCount: {
                required: helpers.withMessage("Word Count is required", required),
            },
            notesFromClient: {
                required: helpers.withMessage("Additional Notes is required", required),
            }
        },
        agreeAccept: {
            checked: (value) => value === true,
        },
    },
    props: ["email"],
    components: {
        Layout,
        PageHeader,
        ServiceSub,
        // Select2
    },
    watch: {
        $route() {
            this.resetFields();
        },
    },
    activated() {
        this.clientLimitId = atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId'))))
        this.superPackage = atob(decodeURIComponent(JSON.parse(localStorage.getItem('customUser'))))
        this.editStory();
        this.getClientLimit();
    },
    beforeMount() {
        this.clientLimitId = atob(decodeURIComponent(JSON.parse(localStorage.getItem('authenticatedUserId'))))
        this.superPackage = atob(decodeURIComponent(JSON.parse(localStorage.getItem('customUser'))))
        this.editStory();
        this.getClientLimit();
    },
    methods: {
        submitStory() {
            this.submitted = true;
            this.v$.$touch();
            if (this.v$.$invalid) {
                return;
            } else {
                this.story.clientName = this.$store.state.authenticatedUser.firstName + " " + this.$store.state.authenticatedUser.lastName;
                this.story.user_id = this.$store.state.authenticatedUser.id;
                let data = new FormData();
                _.each(this.story, (value, key) => {
                    data.append(key, value);
                });
                for (let i = 0; i < this.image.length; i++) {
                    data.append("files[]", this.image[i]);
                }
                axios.post(this.url + "api/storeStory", data).then(() => {
                    this.$store.state.isAccepted = true
                    this.$store.state.notificationData = 'Story Added Successfully'
                    this.$router.push({ path: "/stories_listing" });
                    setTimeout(() => {
                        this.$store.state.isAccepted = false
                        this.$store.state.notificationData = null
                    }, 2500)
                });
            }
        },
        previewImage(event, id) {
            this.id.push(id);
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
        reset(id) {
            const index = this.id.indexOf(id);
            this.id.splice(index, 1);
            this.preview.splice(index, 1);
            this.image.splice(index, 1);
            this.newImage.splice(index, 1);
            this.data.splice(index, 1);
        },
        setSubmissionPackage(id) {
            this.story.submissionPackageId = id;
        },
        setFictionPackage(id) {
            this.story.fictionPackageId = id;
        },
        setEditingPackage(id) {
            this.story.editingPackageId = id;
        },
        editStory() {
            if (this.$route.params.id) {
                this.title = false;
                this.agreeAccept = true;
                axios
                    .post(this.url + "api/editClientStory", {
                        id: this.decode(this.$route.params.id),
                    })
                    .then((res) => {
                        this.story = res.data;
                        if (res.data.files) {
                            this.data = res.data.files.split(",");
                            this.newImage = res.data.files.split(",");
                        }
                    });
            }
        },
        updateStory() {
            this.submitted = true;
            this.v$.$touch();
            if (this.v$.$invalid) {
                return;
            } else {
                this.story.clientName =
                    this.$store.state.authenticatedUser.firstName +
                    " " +
                    this.$store.state.authenticatedUser.lastName;
                this.story.user_id = this.$store.state.authenticatedUser.id;

                let data = new FormData();
                _.each(this.story, (value, key) => {
                    data.append(key, value);
                });
                for (let i = 0; i < this.image.length; i++) {
                    data.append("files[]", this.image[i]);
                }
                for (let j = 0; j < this.newImage.length; j++) {
                    data.append("images[]", this.newImage[j]);
                }
                data.append("id", this.decode(this.$route.params.id));
                axios.post(this.url + "api/updateClientStory", data).then(() => {
                    this.$store.state.isAccepted = true
                    this.$store.state.notificationData = 'Story Updated Successfully'
                    this.$router.go(-1);
                    setTimeout(() => {
                        this.$store.state.isAccepted = false
                        this.$store.state.notificationData = null
                    }, 2500)
                }).catch(error => {
                    console.log(error);
                });
            }
        },

        async getClientLimit() {
            try {
                const response = await axios.post(this.url + "api/getClientLimit", {
                    id: this.clientLimitId,
                });
                this.storyLimit = response.data.user.storyLimit;
            } catch (error) {
                console.log(error);
            }
        },

        resetFields() {
            this.submitted = false;
            this.agreeAccept = false;
            this.title = true;
            (this.id = []),
                this.data = [],
                this.preview = [],
                this.image = [],
                this.newImage = [],
                this.story = {
                    clientName: "",
                    storyTitle: "",
                    storyGenre: "",
                    finalVersionSaved: false,
                    wordCountChecked: false,
                    useAvailablePackage: false,
                    wordCount: "",
                    notesFromClient: "",
                    files: [],
                    submissionPackageId: "",
                    fictionPackageId: "",
                    editingPackageId: "",
                    user_id: null,
                };
        },

        decode(id) {
            return decodeURIComponent(
                CryptoJS.AES.decrypt(String(id), "Secret Passphrase").toString(
                    CryptoJS.enc.Utf8
                )
            );
        },
    },
};
</script>
