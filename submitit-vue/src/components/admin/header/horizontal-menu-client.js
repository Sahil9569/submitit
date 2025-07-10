export const menuItemsClient = [
    {
        id: 1,
        label: 'Dashboard',
        link: '/dashboard',
        icon: 'bx bx-home-circle',
    },
    {
        id: 2,
        label: 'Story',
        icon: 'mdi mdi-file-document-outline',
        subItems: [
            {
                id: 1,
                label: 'Add Story',
                link: '/add_story',
                icon: 'mdi mdi-file-plus-outline',
                parentId: 2
            },
            {
                id: 2,
                label: "Stories",
                link: "/stories_listing",
                icon: 'mdi mdi-file-document-outline',
                parentId: 2
            }
        ]
        
    },
    {
        id: 3,
        label: 'Active Subscription',
        link: '/activeSubscription',
        icon: 'mdi mdi-file-document-outline',
    },
    {
        id: 3,
        label: 'Invoice',
        link: '/invoice',
        icon: 'mdi mdi-file-document-outline',
    },
    
    // {
    //     id: 2,
    //     label: 'Client',
    //     icon: 'bx bx-user-plus',
    //     subItems: [
    //         {
    //             id: 1,
    //             label: "Add Client",
    //             link: "/addClient",
    //             icon: 'bx bx-user-plus',
    //             parentId: 2
    //         },
    //         {
    //             id: 2,
    //             label: 'Client',
    //             link: '/client',
    //             icon: 'bx bx-user',
    //             parentId: 2
    //         },
    //     ]
    // },
    // {
    //     id: 2,
    //     label: 'Story',
    //     icon: 'mdi mdi-file-document-outline',
    //     subItems: [
    //         {
    //             id: 1,
    //             label: 'Add Story',
    //             link: '/addStory',
    //             icon: 'mdi mdi-file-plus-outline',
    //             parentId: 2
    //         },
    //         {
    //             id: 2,
    //             label: "Stories",
    //             link: "/stories",
    //             icon: 'mdi mdi-file-document-outline',
    //             parentId: 2
    //         },
    //     ]
        
    // },
    // {
    //     id: 4,
    //     label: 'Journals',
    //     icon: 'mdi mdi-book-outline',
    //     subItems: [
    //         {
    //             id: 1,
    //             label: 'Add Journal',
    //             link: '/addJournal',
    //             icon: 'mdi mdi-book-edit-outline',
    //             parentId: 4
    //         },
    //         {
    //             id: 2,
    //             label: "Journals",
    //             link: "/journals",
    //             icon: 'mdi mdi-book-outline',
    //             parentId: 4
    //         },
    //     ]
    // },
    // {
    //     id: 5,
    //     label: 'All Task',
    //     link: '/allTask',
    //     icon: 'mdi mdi-file-document-outline'
    // },
    // {
    //     id: 6,
    //     label: 'All Responses',
    //     link: '/allResponse',
    //     icon: 'mdi mdi-message-text-clock'
    // }
];

