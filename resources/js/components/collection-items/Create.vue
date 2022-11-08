<template>
    <form @submit.prevent="storeCollectionItem(collectionItem)">
        <!-- Title -->
        <div>
            <label for="collectionItem-title" class="block font-medium text-sm text-gray-700">
                Title
            </label>
            <input v-model="collectionItem.title" id="collectionItem-title" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.title" :key="message">
                    {{ message }}
                </div>
            </div>
        </div>
       

        <!-- Content -->
        <div class="mt-4">
            <label for="collectionItem-content" class="block font-medium text-sm text-gray-700">
                Content
            </label>
            <textarea v-model="collectionItem.content" id="collectionItem-content" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.content" :key="message">
                    {{ message }}
                </div>
            </div>
        </div>

        <!-- Category -->
        <div class="mt-4">
            <label for="collectionItem-category" class="block font-medium text-sm text-gray-700">
                Category
            </label>
            <select v-model="collectionItem.category_id" id="collectionItem-category" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="" selected>-- Choose category --</option>
                <option v-for="category in categories" :value="category.id" :key="category.id" >
                    {{ category.name }}
                </option>
            </select>
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.category_id" :key="message">
                    {{ message }}
                </div>
            </div>
        </div>

         <!-- Thumbnail -->
         <div class="mt-4">
            <label for="thumbnail" class="block font-medium text-sm text-gray-700">
                Thumbnail
            </label>
            <input @change="collectionItem.thumbnail = $event.target.files[0]" type="file" id="thumbnail" />
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.thumbnail" :key="message">
                    {{ message }}
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-4">
            <button :disabled="isLoading" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">
                <div v-show="isLoading" class="inline-block animate-spin w-4 h-4 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></div>
                <span v-if="isLoading">Processing...</span>
                <span v-else>Save</span>
            </button>
        </div>
    </form>
</template>

<script>

import { onMounted, reactive } from 'vue'
import useCategories from '../../composables/categories'
import useCollectionItems from '../../composables/collectionItems'

export default {
    setup() {
        const collectionItem = reactive({
            title: '',
            content: '',
            category_id: '',
            thumbnail: ''
        })
        
        const { categories, getCategories } = useCategories()
        const { storeCollectionItem, validationErrors, isLoading } = useCollectionItems()
        onMounted( () => {
            getCategories()
        })

        return { categories, collectionItem, storeCollectionItem, validationErrors, isLoading }
    }
    
}        
</script>