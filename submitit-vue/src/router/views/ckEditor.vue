<template>
    <Layout>
        <PageHeader :title="title" />
        <div class="">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" v-model="emailTitle">
        </div>
        <br>
        <div class="row mb-3 align-items-center">
            <div class="col">
                <div id="toolbar-container"></div>
                <DecoupledEditor tag-name="textarea" id="editors" v-model="editorData" class="editor" :editor="editor"
                    :confing="editorConfig">
                </DecoupledEditor>
                <div class="col-auto">
                    <button class="btn btn-theme mt-3" type="submit" @click="updateEmailTemplate">Submit</button>
                </div>
            </div>
        </div>
    </Layout>
</template>
<style>
.editor {
    border: 1px salmon;
    background-color: navajowhite;
    width: 200px;
    height: 200px;
    margin-top: 0;
    margin-bottom: 0;
}

.ck.ck-content:not(.ck-comment__input *) {
    height: 300px;
    overflow-y: auto;
    margin-top: 0;
    margin-bottom: 0;
}
</style>

<script>
import Layout from "../layouts/main"
import PageHeader from "@/components/admin/header/page-header";
import axios from 'axios';
import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
import CryptoJS from 'crypto-js';

export default {
    page: {
        title: "Ckeditor",
        meta: [
            {
                name: "description",
                content: 'description',
            },
        ],
    },
    name: 'editor',

    data() {

        return {
            title: "Update Email Template", 
            editor: DecoupledEditor,
            editorData: '',
            emailTitle: '',
            editorConfig: {
                fillEmptyBlocks: false,
            },
            url: process.env.VUE_APP_URL
        }
    },
    components: {
        PageHeader,
        Layout
    },
    mounted() {
        DecoupledEditor.create(document.querySelector('#editors'), {
        }).then(editor => {
            window.editor = editor;
            const toolbarContainer = document.querySelector('#toolbar-container');
            toolbarContainer.appendChild(editor.ui.view.toolbar.element);
            editor.model.document.on('change:data', () => {
                const data = editor.getData().trim();
                const cleanedData = data.replace(/<p>(&nbsp;|\s)*<\/p>/g, '');
                this.editorData = cleanedData;
            });
        }).catch(error => {
            console.error('Editor initialization error.', error);
        });
    },
    activated() {
        this.editEmailTemplate();
    },

    methods: {
        updateEmailTemplate() {
            axios.post(this.url + 'api/updateEmailTemplate', {
                'id': this.decode(this.$route.params.id),
                'templateDesign': this.editorData,
                'title': this.emailTitle
            }).then(() => {
                this.$router.push({ path: '/emailTemplate' });
            });
        },
        editEmailTemplate() {
            axios.post(this.url + 'api/editEmailTemplate', { 'id': this.decode(this.$route.params.id) }).then(res => {
                this.emailTitle = res.data.title;
                window.editor.setData(res.data.templateDesign);
            })
        },
        decode(id) {
            return decodeURIComponent(CryptoJS.AES.decrypt(String(id), "Secret Passphrase").toString(CryptoJS.enc.Utf8));
        },
    },

}
</script>

