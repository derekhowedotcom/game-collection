<template>
    <div class="overflow-hidden overflow-x-auto p-1 bg-white border-gray-200">
        <div class="min-w-full align-middle">
            <div class="mb-4 grid lg:grid-cols-4 gap-4">
                <input v-model="search_global" type="text" placeholder="Search..." class="inline-block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <table class="min-w-full divide-y divide-gray-200 border mb-4">
                <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left">
                        <input v-model="search_id" type="text" class="inline-block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Filter by ID">
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left">
                        <input v-model="search_title" type="text" class="inline-block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Filter by Title">
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left">
                        <select v-model="search_category" class="inline-block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="" selected>-- all categories --</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left">
                        <input v-model="search_description" type="text" class="inline-block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Filter by Description">
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left"></th>
                    <th class="px-6 py-3 bg-gray-50 text-left"></th>
                </tr>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left">
<!--                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</span>-->
                        <div class="flex flex-row items-center justify-between cursor-pointer" @click="updateOrdering('id')">
                            <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider" :class="{ 'font-bold text-blue-600': orderColumn === 'id' }">
                                ID
                            </div>
                            <div class="select-none">
                                <span :class="{
                                  'text-blue-600': orderDirection === 'asc' && orderColumn === 'id',
                                  'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'id',
                                }">&uarr;</span>
                                <span :class="{
                                  'text-blue-600': orderDirection === 'desc' && orderColumn === 'id',
                                  'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'id',
                                }">&darr;</span>
                            </div>
                        </div>
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left">
<!--                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Title</span>-->
                        <div class="flex flex-row items-center justify-between cursor-pointer" @click="updateOrdering('title')">
                            <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider" :class="{ 'font-bold text-blue-600': orderColumn === 'title' }">
                                Title
                            </div>
                            <div class="select-none">
                                <span :class="{
                                  'text-blue-600': orderDirection === 'asc' && orderColumn === 'title',
                                  'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'title',
                                }">&uarr;</span>
                                <span :class="{
                                  'text-blue-600': orderDirection === 'desc' && orderColumn === 'title',
                                  'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'title',
                                }">&darr;</span>
                            </div>
                        </div>
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left">
                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Category</span>
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left">
                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">description</span>
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left">
<!--                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Created at</span>-->
                        <div class="flex flex-row items-center justify-between cursor-pointer" @click="updateOrdering('created_at')">
                            <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider" :class="{ 'font-bold text-blue-600': orderColumn === 'created_at' }">
                                Created at
                            </div>
                            <div class="select-none">
                                <span :class="{
                                  'text-blue-600': orderDirection === 'asc' && orderColumn === 'created_at',
                                  'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'created_at',
                                }">&uarr;</span>
                                <span :class="{
                                  'text-blue-600': orderDirection === 'desc' && orderColumn === 'created_at',
                                  'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'created_at',
                                }">&darr;</span>
                            </div>
                        </div>
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left">
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                    <tr v-for="collectionItem in collectionItems.data" :key="collectionItem.id">
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                        {{ collectionItem.id }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                        
                        <titleComponent :collectionItem="collectionItem" />
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                        {{ collectionItem.category }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                        {{ collectionItem.description }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                        {{ collectionItem.created_at }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                        <router-link v-if="can('collection-items.update')" :to="{ name: 'collection-items.edit', params: { id: collectionItem.id } }">Edit</router-link>
                        <a href="#"  v-if="can('collection-items.delete')" @click.prevent="deleteCollectionItem(collectionItem.id)" class="ml-2">Delete</a>
                    </td>
                </tr>
                </tbody>
            </table>
            <Pagination :data="collectionItems" @pagination-change-page="page => getCollectionItems(page, selectedCategory)" />
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted, watch } from 'vue'
import useCollectionItems from '../../composables/collectionItems'
import useCategories from '../../composables/categories'
import { current } from 'tailwindcss/colors';
import { useAbility } from '@casl/vue'
import titleComponent from '../ui/Title.vue'

export default {
    setup() {

        
        const search_category = ref('')
        const search_id = ref('')
        const search_title = ref('')
        const search_description = ref('')
        const search_global = ref('')
        const orderColumn = ref('title')
        const orderDirection = ref('desc')
        const { collectionItems, getCollectionItems, deleteCollectionItem } = useCollectionItems()
        const { categories, getCategories } = useCategories()
        const { can } = useAbility()
        onMounted( () => {
            getCollectionItems(),
            getCategories()
            
        })
        const updateOrdering = (column) => {
            orderColumn.value = column;
            orderDirection.value = (orderDirection.value === 'asc') ? 'desc' : 'asc';
            getCollectionItems(
                1, 
                search_category.value,
                search_id.value,
                search_title.value,
                search_description.value, 
                search_global.value,
                orderColumn.value, 
                orderDirection.value
            ); 
            
        }

        watch(search_category, (current, previous) => {
            getCollectionItems(
                1, 
                current, 
                search_id.value,
                search_title.value,
                search_description.value, 
                search_global.value,
            )
        })

        watch(search_id, (current, previous) => {
            getCollectionItems(
                1, 
                search_category.value,
                current, 
                search_title.value,
                search_description.value, 
                search_global.value,
            )
        })

        watch(search_title, (current, previous) => {
            getCollectionItems(
                1, 
                search_category.value,
                search_id.value,
                current,
                search_description.value, 
                search_global.value,
            )
        })

        watch(search_description, (current, previous) => {
            getCollectionItems(
                1, 
                search_category.value,
                search_id.value,
                search_title.value,
                current, 
                search_global.value,
            )
        })

        watch(search_global, (current, previous) => {
            getCollectionItems(
                1, 
                search_category.value,
                search_id.value,
                search_title.value,
                search_description.value, 
                current, 
            )
        })

        return { 
            collectionItems,
            getCollectionItems,
            deleteCollectionItem,
            categories,
            search_category,
            search_id,
            search_title,
            search_description, 
            search_global,
            orderColumn,
            orderDirection,
            updateOrdering,
            can 
        }
    },
    components: {
        titleComponent
    }
    
}
</script>