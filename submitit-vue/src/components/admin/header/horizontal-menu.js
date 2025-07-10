export const menuItemsAdmin = [
    {
        id: 1,
        label: 'Dashboard',
        link: '/',
        icon: 'bx bx-home-circle',
    },
    {
        id: 2,
        label: 'Client',
        icon: 'bx bx-user-plus',
        subItems: [
            {
                id: 1,
                label: "Add Client",
                link: "/addClient",
                icon: 'bx bx-user-plus',
                parentId: 2
            },
            {
                id: 2,
                label: 'Clients',
                link: '/client',
                icon: 'bx bx-user',
                parentId: 2
            },
        ]
    },
    {
        id: 3,
        label: 'Story',
        icon: 'mdi mdi-file-document-outline',
        subItems: [
            {
                id: 1,
                label: 'Add Story',
                link: '/addStory',
                icon: 'mdi mdi-file-plus-outline',
                parentId: 3
            },
            {
                id: 2,
                label: "Stories",
                link: "/stories",
                icon: 'mdi mdi-file-document-outline',
                parentId: 3
            },
        ]
        
    },
    {
        id: 4,
        label: 'Journals',
        icon: 'mdi mdi-book-outline',
        subItems: [
            {
                id: 1,
                label: 'Add Journal',
                link: '/addJournal',
                icon: 'mdi mdi-book-edit-outline',
                parentId: 4
            },
            {
                id: 2,
                label: "Journals",
                link: "/journals",
                icon: 'mdi mdi-book-outline',
                parentId: 4
            },
            {
                id: 3,
                label: "Manage Journals",
                link: "/manageJournals",
                icon: 'mdi mdi-book-outline',
                parentId: 4
            },
            
        ]
    },
    {
        id: 5,
        label: 'All Tasks',
        link: '/allTask',
        icon: 'mdi mdi-file-document-outline'
    },
    {
        id: 6,
        label: 'All Responses',
        link: '/allResponse',
        icon: 'mdi mdi-message-text-clock'
    }
];

