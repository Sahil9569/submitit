import axios from "axios";

export default {
    state() {
        return {
            clients: [],
            stories: [],
            storyScores: [],
            journals: [],
            journalStories: [],
            tasks: [],
            storySubmission: [],
            singleClient: [],
            packages:[],
            emailTemplates :[],
            addMoreJournals:[]
        }
    },
    actions: {
        getAllData(context) {
            axios.get(context.rootState.BASE_URL + 'api/getAllData')
                .then(res => {
                    context.commit('updateClients', res.data.users);
                    context.commit('updateStories', res.data.stories);
                    context.commit('updateJournals', res.data.journals);
                    context.state.journalStories = res.data.journalStories
                    context.state.storyScores = res.data.storyScores
                    context.state.tasks = res.data.tasks
                    context.state.storySubmission = res.data.storySubmission
                    context.state.emailTemplates = res.data.emailTemplates
                })
                .catch(error => {
                    console.error("Error fetching data:", error);
                });
        },
        getStoryJournal(context)
        {
            axios.get(context.rootState.BASE_URL + 'api/getStoryJournalData')
            .then(res=>{
                context.state.addMoreJournals = res.data.addMoreJournals
            })
        },
        getData(context) {
            axios.get(context.rootState.BASE_URL + 'api/getData')
                .then(res => {
                    context.state.packages = res.data.packages
                })
                .catch(error => {
                    console.error("Error fetching data:", error);
                });
        },
        addClient({ commit, state }, newClient) {
            let updatedClients = [...state.clients, { ...newClient, arrType: 'Client' }];
            updatedClients.sort((a, b) => (a.id > b.id) ? 1 : -1)
            commit('updateClients', updatedClients);
        },
        addJournal({ commit, state }, newJournal) {
            let updatedJournals = [...state.journals, { ...newJournal, arrType: 'Journal' }];
            updatedJournals.sort((a, b) => (a.id > b.id) ? 1 : -1)
            commit('updateJournals', updatedJournals);
        },
        updateClient({ commit, state }, data) {
            let updatedClients = state.clients.map(client => (client.id == data.id ? { ...client, ...data.newData, arrType: 'Client' } : client));
            updatedClients.sort((a, b) => (a.id > b.id) ? 1 : -1);
            commit('updateClients', updatedClients);
        },          
        updateJournal({ commit, state }, data) {
            let updatedJournals = state.journals.map(journal => (journal.id == data.id ? { ...journal, ...data.newData, arrType: 'Journal' } : journal));
            updatedJournals.sort((a, b) => (a.id > b.id) ? 1 : -1);
            commit('updateJournals', updatedJournals);
        },          
        deleteJournalStories({ commit, state }, id) {
            let updatedJournalStories = state.journalStories.filter(item => (item.id != id));
            updatedJournalStories.sort((a, b) => (a.id > b.id) ? 1 : -1);
            commit('updateJournalStories', updatedJournalStories);
        },          
        deleteStory({ commit, state }, id) {
            let updatedStories = state.stories.filter(item => (item.id != id));
            updatedStories.sort((a, b) => (a.id > b.id) ? 1 : -1);
            commit('updateStories', updatedStories);
        },          
    },
    mutations: {
        updateClients(state, clients) {
            state.clients = clients.map(obj => ({ ...obj, arrType: 'Client' }));
        },
        updateStories(state, stories) {
            state.stories = stories.map(obj => ({ ...obj, arrType: 'Story' }));
        },
        updateJournals(state, journals) {
            state.journals = journals.map(obj => ({ ...obj, arrType: 'Journal' }));
        },
        updateJournalStories(state, journalStories) {
            state.journalStories = journalStories.map(obj => ({ ...obj}));
        }
    }
}
