<template>
    <form @submit.prevent="storeCollectionItem(collectionItem)">
        <!-- Thumbnail -->
        <div class="mt-1">
            <label for="thumbnail" class="block font-bold text-sm text-gray-700">
                Thumbnail
            </label>
            <img :src="`${thumbnailUrl}`" alt="Placeholder Image" class="mt-2 mb-3 h-auto max-w-xs rounded-lg"/>
            <input @change="onFileChange($event.target.files[0])" type="file" id="thumbnail" />
            <div class="text-red-600 mt-1">
                <div v-for="message in validationErrors?.thumbnail" :key="message">
                    {{ message }}
                </div>
            </div>
        </div>
        <!-- Barcode -->
        <div class="mt-4">
            <collection-item-text-input
                    v-model:value="collectionItem.barcode"
                    :label="'Barcode/Product ID'"
                    :id="'collectionItem-barcode'"
                    :field-name="'barcode'"
                    :validation-errors="validationErrors"
                    :other-error-message="cexErrorMessage"
                    @update:value="(newValue) => updateCollectionItem('barcode', newValue)"
            />
            <!-- <button @click="toggleModal" type="button" class="inline-flex content-center items-center mt-3 px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">Scan Barcode</button> -->
        </div>
        <!-- Title -->
        <div class="mt-4">
            <collection-item-text-input
                v-model:value="collectionItem.title"
                :label="'Title'"
                :id="'collectionItem-title'"
                :value="collectionItem?.title"
                :field-name="'title'"
                :validation-errors="validationErrors"
                @update:value="(newValue) => updateCollectionItem('title', newValue)"
            />
        </div>
        <!-- Description -->
        <div class="mt-4">
            <collection-item-text-area
                    v-model:value="collectionItem.description"
                    :label="'Description'"
                    :id="'collectionItem-description'"
                    :value="collectionItem.description"
                    :field-name="'description'"
                    :validation-errors="validationErrors"
                    @update:value="(newValue) => updateCollectionItem('description', newValue)"
            />
        </div>
        <!-- Category -->
        <div class="mt-4">
            <collection-item-select
                    v-model:value="collectionItem.category_id"
                    id="collectionItem-category"
                    value="collectionItem.category_id"
                    :categories="categories"
                    :label="'Category'"
                    :field-name="'category'"
                    :validation-errors="validationErrors"
                    @update:value="(newValue) => updateCollectionItem('category', newValue)"
            />
        </div>
        <!-- Rarity -->
        <div class="mt-4">
            <collection-item-select
                    v-model:value="collectionItem.rarity_id"
                    id="collectionItem-rarity"
                    value="collectionItem.rarity_id"
                    :categories="rarities"
                    :label="'Rarity'"
                    :field-name="'rarity'"
                    :validation-errors="validationErrors"
                    @update:value="(newValue) => updateCollectionItem('rarity', newValue)"
            />
        </div>
        <!-- Value -->
        <div class="mt-4">
            <collection-item-text-input
                    v-model:value="collectionItem.value"
                    :label="'Value'"
                    :id="'collectionItem-value'"
                    :field-name="'value'"
                    :validation-errors="validationErrors"
                    @update:value="(newValue) => updateCollectionItem('value', newValue)"
            />
        </div>
        <!-- Price Paid -->
        <div class="mt-4">
            <collection-item-text-input
                    v-model:value="collectionItem.price_paid"
                    :label="'Price Paid'"
                    :id="'collectionItem-pricePiad'"
                    :field-name="'price_paid'"
                    :validation-errors="validationErrors"
                    @update:value="(newValue) => updateCollectionItem('price_paid', newValue)"
            />
        </div>
        <!-- Boxed -->
        <div class="mt-4">
            <collection-item-radio
                    v-model:value="collectionItem.boxed"
                    :id="'collectionItem-boxed'"
                    :value="collectionItem.boxed.toInteger()"
                    :categories="boxedOptions"
                    :label="'Boxed'"
                    :field-name="'boxed'"
                    :validation-errors="validationErrors"
                    @update:value="(newValue) => updateCollectionItem('boxed', newValue)"
            />
        </div>
        <!-- Buttons -->
        <div class="mt-4">
            <Collection-item-cancel-button @click="$router.push({ name: 'collection-items.index' })">Cancel</Collection-item-cancel-button>
            <Collection-item-save-button :is-loading="isLoading"></Collection-item-save-button>
            <button @click="handleCexClick" type="button"
                    class="inline-flex content-center items-center mt-3 ml-3 px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">
                Get CEX Details
            </button>
        </div>
    </form>
    <!-- <modal @close="toggleModal" :modalActive="modalActive">
      <div class="modal-content">
            <h1>Barcode Scanner</h1>
            <StreamBarcodeReader @decode='onDecode' @loaded='onLoaded'></StreamBarcodeReader>
        </div>
    </modal> -->
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import useCategories from '../../composables/categories';
import useRarities from '../../composables/rarities';
import useCex from '../../composables/cex';
import useCollectionItems from '../../composables/collectionItems';
import Modal from '../Modal.vue';
import Swal from 'sweetalert2';
import { getCexCategory } from "../../helpers/cexHelpers";
import CollectionItemTextInput from "../ui/CollectionItemTextInput.vue";
import CollectionItemTextArea from "../ui/CollectionItemTextArea.vue";
import CollectionItemSelect from "../ui/CollectionItemSelect.vue";
import CollectionItemRadio from "../ui/CollectionItemRadio.vue";
import CollectionItemCancelButton from "../ui/CollectionItemCancelButton.vue";
import CollectionItemSaveButton from "../ui/CollectionItemSaveButton.vue";



// Declare reactive state and functions
const collectionItem = ref({
    barcode: '',
    title: '',
    description: '',
    category_id: '',
    rarity_id: '',
    thumbnail: '',
    cex_image: '',
    cexCategory: null,
    value: '',
    price_paid: '',
    boxed: 0,
});
const cexErrorMessage = ref('');
const thumbnailUrl = ref('/storage/images/collection-items/image-placeholder.jpg');
const modalActive = ref(false);
const toggleModal = () => {
    modalActive.value = !modalActive.value;
};

// Extract composables
const { categories, getCategories } = useCategories();
const { rarities, getRarities } = useRarities();
const { storeCollectionItem, validationErrors, isLoading } = useCollectionItems();
const { cexItem, getCexItem } = useCex();
const boxedOptions = [
    { id: 1, name: 'Yes' },
    { id: 0, name: 'No' },
];

// On mount actions
onMounted(() => {
    getCategories();
    getRarities();
    collectionItem.value = {
        barcode: '',
        title: '',
        description: '',
        category_id: '',
        rarity_id: '',
        thumbnail: '',
        cex_image: '',
        cexCategory: null,
        value: '',
        price_paid: '',
        boxed: 0,
    };
});

function onFileChange(target) {
    collectionItem.value.thumbnail = target;

    //create url to show image preview
    if(target){
        thumbnailUrl.value = URL.createObjectURL(target);
        collectionItem.value.cex_image = '';
    }
}

const updateCollectionItem = (fieldName, newValue) => {
    collectionItem.value.fieldName = newValue;
};

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
            await getCexItem(collectionItem.value.barcode);
        }

        // Only set values if there is data from Cex
        if(cexItem?.value?.response?.data !== null){
            collectionItem.value.title = cexItem?.value?.response?.data?.boxDetails[0]?.boxName;
            //trim white space and html tags
            collectionItem.value.description = cexItem?.value?.response?.data?.boxDetails[0]?.boxDescription
                                        .replace(/<\/?[^>]+(>|$)/g, "")
                                        .trim()
                                        .replace(/\s{2,10}/g, ' ');
            collectionItem.value.cex_image = cexItem?.value?.response?.data?.boxDetails[0]?.imageUrls?.large;
            collectionItem.value.value = cexItem?.value?.response?.data?.boxDetails[0]?.sellPrice;
            collectionItem.value.category_id = getCexCategory(cexItem?.value?.response?.data?.boxDetails[0]?.categoryName);
            // If we could not find a category try the search using categoryFriendlyName
            if(collectionItem.value.category_id === null){
                collectionItem.value.category_id = getCexCategory(cexItem?.value?.response?.data?.boxDetails[0]?.categoryFriendlyName);
            }
            cexErrorMessage.value = '';
            thumbnailUrl.value = collectionItem.value.cex_image;

        }else{
            //TODO: convert this to a flash message?
            cexErrorMessage.value = 'Item not found. Please check the CEX Barcode/Product ID.';
        }
    } catch (error) {
        cexErrorMessage.value = 'Please check the CEX Barcode/Product ID and try again.';
        console.error(error);
    }
}

</script>
