<template>
  
        <!-- Title -->
        <div class="mb-4">
            <h1><span class="block font-medium text-sm text-gray-700">Title:</span></h1> 
            <p>{{ collectionItem.title }}</p>
        </div>
        <!-- Description -->
        <div class="mb-4">
            <h1><span class="block font-medium text-sm text-gray-700">Description:</span></h1> 
            <p>{{ collectionItem.description }}</p>
        </div>
        <!-- Category -->
        <div>
            <h1><span class="block font-medium text-sm text-gray-700">Category:</span></h1> 
            <p>{{ collectionItem.category }}</p>
        </div>
        <!-- Buttons -->
        <div class="mt-4">
            <router-link class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" v-if="can('collection-items.update')" :to="{ name: 'collection-items.edit', params: { id: this.$route.params.id } }">Edit</router-link>
            

            <a href="#" v-if="can('collection-items.delete')" @click.prevent="deleteCollectionItem(collectionItem.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">Delete</a>
        </div>
  
</template>

<script>
import {onMounted, reactive } from "vue";
import { useRoute } from "vue-router";
import useCategories from "../../composables/categories";
import useCollectionItems from "../../composables/collectionItems";
import { useAbility } from '@casl/vue'
export default {
    setup() {
        const { 
            categories, 
            getCategories 
        } = useCategories()
        
        const { 
            collectionItem, 
            getCollectionItem, 
            deleteCollectionItem 
        } = useCollectionItems()
        
        const route = useRoute()

        const { can } = useAbility()
        
        onMounted(() => {
            getCollectionItem(route.params.id)
            getCategories()
        })
        console.log(collectionItem);
        return { 
            categories, 
            collectionItem, 
            can, 
            deleteCollectionItem 
        }
    }
}      
</script>