import {createRouter, createWebHistory } from "vue-router";

import AuthenticatedLayout from '../layouts/Authenticated.vue';
import GuestLayout from '../layouts/Guest.vue';

import CollectionItemsIndex from '../components/collection-items/Index.vue';
import CollectionItemsCreate from '../components/collection-items/Create.vue';
import CollectionItemsEdit from '../components/collection-items/Edit.vue';
import Login from '../components/Login.vue';

function auth(to, from, next) {
    if (JSON.parse(localStorage.getItem('loggedIn'))) {
        next()
        return
    }

    next('/login')
}

const routes = [
    {
        path: '/',
        redirect: { name: 'login' },
        component: GuestLayout,
        children:[
            { 
                path: '/login', 
                name: 'login',
                component: Login,
               
            },
        ]
    },
    {
        component: AuthenticatedLayout,
        beforeEnter: auth,
        children:[
            { 
                path: '/collection-items', 
                name: 'collection-items.index',
                component: CollectionItemsIndex,
                meta: { title: 'Collection Items' }
            },
            { 
                path: '/collection-items/create', 
                name: 'collection-items.create', 
                component: CollectionItemsCreate,
                meta: { title: 'Create New Collection Item' }
            },
            { 
                path: '/collection-items/edit/:id', 
                name: 'collection-items.edit', 
                component: CollectionItemsEdit,
                meta: { title: 'Edit Collection Item' }
            },
        ]
    },
    
]

export default createRouter ({
    history: createWebHistory(),
    routes
})