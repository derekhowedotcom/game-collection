<template>
    <form @submit.prevent="storeCollectionItem(collectionItem)">
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
            <button @click="$router.push({ name: 'collection-items.index' })" type="button" class="inline-flex content-center items-center mt-3 px-3 py-2 bg-red-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">Cancel</button>
            <button :disabled="isLoading" class="inline-flex items-center ml-3 px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">
                <div v-show="isLoading" class="inline-block animate-spin w-4 h-4 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></div>
                <span v-if="isLoading">Processing...</span>
                <span v-else>Save</span>
            </button>
            
            <button @click="handleCexClick" type="button" class="inline-flex content-center items-center mt-3 ml-3 px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">Get CEX Details</button>
        </div>
    </form>
    <!-- <modal @close="toggleModal" :modalActive="modalActive">
      <div class="modal-content">
            <h1>Barcode Scanner</h1>
            <StreamBarcodeReader @decode='onDecode' @loaded='onLoaded'></StreamBarcodeReader>
        </div>
    </modal> -->
</template>

<script>

import { onMounted, reactive, ref } from 'vue'
import useCategories from '../../composables/categories'
import useCex from '../../composables/cex'
import useCollectionItems from '../../composables/collectionItems'
// import { StreamBarcodeReader } from "vue-barcode-reader";
import Modal from "../Modal.vue";
import Swal from 'sweetalert2';

export default {
    setup() {
        const collectionItem = reactive({
            barcode: '',
            title: '',
            description: '',
            category_id: '',
            thumbnail: ''
        })
        const cexErrorMessage = ref(null);
        const modalActive = ref(false);
        const toggleModal = () => {
            modalActive.value = !modalActive.value;
        };
        const { categories, getCategories } = useCategories()
        const { storeCollectionItem, validationErrors, isLoading } = useCollectionItems()
        const { cexItem, getCexItem } = useCex()
        

        onMounted( () => {
            getCategories()
            
        })


        // const decodeText = ref('')

        // function onDecode (result) {
        //             //display the result from barcode scan
        //             // collectionItem.barcode.value = result
        //             //close the modal
        //             toggleModal()
        //         }

        async function handleCexClick() {
            try {
                
                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will replace the form values with values from Cex.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    confirmButtonColor: '#22c55e',
                    timer: 20000,
                    timerProgressBar: true,
                    reverseButtons: true
                });

                // If user clicks yes    
                if (result.isConfirmed) {
                    //wait for the values to come back from the api call before setting them.
                    await getCexItem(collectionItem.barcode);
                }

                // Only set values if there is data from Cex
                if(cexItem?.value?.response?.data !== null){
                    collectionItem.title = cexItem?.value?.response?.data?.boxDetails[0]?.boxName; 
                    //trim white space and html tags
                    collectionItem.description = cexItem?.value?.response?.data?.boxDetails[0]?.boxDescription
                                                .replace(/<\/?[^>]+(>|$)/g, "")
                                                .trim()
                                                .replace(/\s{2,10}/g, ' '); 
                    cexErrorMessage.value = null;

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
            storeCollectionItem, 
            validationErrors, 
            isLoading, 
            //onDecode, 
            modalActive, 
            toggleModal, 
            cexItem, 
            getCexItem, 
            handleCexClick,
            cexErrorMessage 
        }
    },
    components: {
        Modal
    }
    
}        
</script>