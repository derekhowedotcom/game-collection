<template>
    <form @submit.prevent="updateCollectionItem(collectionItem)">
       
         <!-- Barcode TODO: add validation and save to db -->
         <div>
            <label for="collectionItem-barcode" class="block font-medium text-sm text-gray-700">
                Barcode/Product ID
            </label>
            <input v-model="collectionItem.barcode" id="collectionItem-barcode" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"> 
            <div class="text-red-600 mt-1" v-if="cexErrorMessage">
                    {{ cexErrorMessage }}
            </div>
            <!-- <button @click="toggleModal" type="button" class="inline-flex content-center items-center mt-3 px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">Scan Barcode</button> -->
        </div>    
        <!-- Title -->
        <div class="mt-4">
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
       
        <!-- Description -->
        <div class="mt-4">
            <label for="collectionItem-description" class="block font-medium text-sm text-gray-700">
                Description
            </label>
            <textarea v-model="collectionItem.description" id="collectionItem-description" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.description" :key="message">
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

            <!-- <div class="mt-4 mb-4 w-96">
                    <p @click="clickme">here</p>
                    <StreamBarcodeReader
                @decode="onDecode"
                @loaded="onLoaded"
                
            ></StreamBarcodeReader>
             Barcode - {{ decodeText }}

            </div> -->
            <button :disabled="isLoading" class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-3 rounded ml-2 disabled:opacity-75 disabled:cursor-not-allowed">
                <div v-show="isLoading" class="inline-block animate-spin w-4 h-4 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></div>
                <span v-if="isLoading">Processing...</span>
                <span v-else><svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Save</span>
            </button>
            <button @click="handleCexClick" type="button" class="inline-flex content-center items-center mt-3 ml-3 px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">Get CEX Details</button>
        </div>
    </form>
</template>

<script>

import {onMounted, reactive, ref, computed } from "vue";
import { useRoute } from "vue-router";
// import { StreamBarcodeReader } from "vue-barcode-reader";
import useCex from '../../composables/cex'
import useCategories from "../../composables/categories";
import useCollectionItems from "../../composables/collectionItems";
export default {
    setup() {
        const { categories, getCategories } = useCategories()
        const { collectionItem, getCollectionItem, updateCollectionItem, validationErrors, isLoading } = useCollectionItems()
        const { cexItem, getCexItem } = useCex()
        const cexErrorMessage = ref(null);
        const decodeText = ref('')

        // function clickme (credentials) {
        //     this.text = 'billy'
        // alert('yoyoyoy')
        // }

        // function onLoaded (loaded){
        //     console.log(loaded)

        // }
        // function onDecode (result) { 
        //     decodeText.value = result
            
        // }

        // const text2 = computed(() => {
        //     return text
        //     })

        const route = useRoute()
        onMounted(() => {
            getCollectionItem(route.params.id)
            getCategories()
        })

        async function handleCexClick() {
            console.log(collectionItem.title);
            try {
                //wait for the values to come back from the api call before setting them.
                await getCexItem(collectionItem.value.barcode);

                if(cexItem?.value?.response?.data !== null){
                    collectionItem.value.title = cexItem?.value?.response?.data?.boxDetails[0]?.boxName; 
                    //trim white space and html tags
                    collectionItem.value.description = cexItem?.value?.response?.data?.boxDetails[0]?.boxDescription
                                                .replace(/<\/?[^>]+(>|$)/g, "")
                                                .trim()
                                                .replace(/\s{2,10}/g, ' '); 
                }else{
                    //TODO: convert this to a flash message?
                    cexErrorMessage.value = 'Item not found. Please check the CEX Barcode/Product ID.';
                }
            } catch (error) {
                cexErrorMessage.value = 'Please check the CEX Barcode/Product ID and try again.';
                console.error(error);
            }
        
        }
        
        
        return { 
            categories, 
            collectionItem, 
            validationErrors, 
            isLoading, 
            // decodeText, 
            // text2, 
            updateCollectionItem, 
            // StreamBarcodeReader, 
            // clickme, 
            // onDecode, 
            cexItem, 
            handleCexClick,
            cexErrorMessage
        }

    },
}      
</script>