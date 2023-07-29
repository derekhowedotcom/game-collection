<template>
    <!-- Image -->
    <div class="mb-4">

        <img width="300" :src="`/storage/images/collection-items/${ collectionItem.thumbnail }`" alt="" />
    </div>
    <!-- Title -->
    <div class="mb-4">
        <h1><span class="block font-medium text-sm text-gray-700">Title:</span></h1>
        <p><titleEditComponent :collectionItem="collectionItem" /></p>
    </div>
    <!-- Date added  -->
    <div class="mb-4">
        <h1><span class="block font-medium text-sm text-gray-700">Date Added:</span></h1>
        <p>{{ formatDate(collectionItem.created_at) }}</p>
    </div>
    <!-- Description -->
    <div class="mb-4">
        <h1><span class="block font-medium text-sm text-gray-700">Description:</span></h1>
        <p>{{ collectionItem.description }}</p>
    </div>
    <!-- Category -->
    <div class="mb-4">
        <h1><span class="block font-medium text-sm text-gray-700">Category:</span></h1>
        <p>{{ collectionItem.category }}</p>
    </div>
    <!-- Value -->
    <div class="mb-4" v-if="collectionItem.value">
        <h1><span class="block font-medium text-sm text-gray-700">Value:</span></h1>
        <p>&pound;{{ collectionItem.value }}</p>
    </div>
    <!-- Price Paid -->
    <div v-if="collectionItem.price_paid">
        <h1><span class="block font-medium text-sm text-gray-700">Price Paid:</span></h1>
        <p>&pound;{{ collectionItem.price_paid }}</p>
    </div>
    <!-- Buttons -->
    <div class="mt-4">
        <router-link class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" v-if="can('collection-items.update')" :to="{ name: 'collection-items.edit', params: { id: this.$route.params.id } }"><svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>Edit</router-link>

        <a href="#" v-if="can('collection-items.delete')" @click.prevent="deleteCollectionItem(collectionItem.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2"><svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>Delete</a>
    </div>
</template>

<script>
import {onMounted, reactive } from "vue";
import { useRoute } from "vue-router";
import useCategories from "../../composables/categories";
import useCollectionItems from "../../composables/collectionItems";
import { useAbility } from '@casl/vue'
import titleEditComponent from '../ui/TitleEdit.vue'
import { formatDate } from "../../helpers/dateHelpers";
import { basename } from "../../helpers/fileHelpers";

export default {
    setup() {
        const { categories, getCategories } = useCategories()
        const { collectionItem, getCollectionItem, deleteCollectionItem } = useCollectionItems()
        const route = useRoute()
        const { can } = useAbility()

        onMounted(() => {
            getCollectionItem(route.params.id)
            getCategories()
        })



        return {
            categories,
            collectionItem,
            can,
            deleteCollectionItem,
            formatDate,
            basename

        }
    },
    components: {
        titleEditComponent
    }
}
</script>
