<template>
    <form @submit.prevent="storeCollectionItem(collectionItem)">
        <!-- Thumbnail -->
      <div class="mt-1">
        <div class="flex items-center justify-center md:justify-start">
        <label for="thumbnail" class="block font-bold text-sm text-gray-700">
          Thumbnail
        </label>
          </div>
        <div class="mb-2 flex items-center justify-center md:justify-start">
          <img :src="`${thumbnailUrl}`" alt="Placeholder Image" class="mt-2 mb-3 h-auto max-w-xs rounded-lg"/>
        </div>
        <input @change="onFileChange($event.target.files[0])" type="file" id="thumbnail"/>
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
        >
          <template #afterInput>
            <button @click="toggleModal" title="Scan Barcode" type="button"
                    class="inline-flex content-center items-center px-3 py-2 bg-transparent text-white rounded disabled:opacity-75 disabled:cursor-not-allowed">
                  <span class="w-8">
                  <span v-html="SVG_BARCODE"></span>
                  </span>
            </button>
          </template>
        </collection-item-text-input>
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
                    :field-name="'category_id'"
                    :validation-errors="validationErrors"
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
            />
        </div>
        <!-- Boxed -->
        <div class="mt-4">
            <collection-item-radio
                    v-model:value="collectionItem.boxed"
                    :id="'collectionItem-boxed'"
                    :value="collectionItem.boxed.toInteger()"
                    :categories="BOXED_OPTIONS"
                    :label="'Boxed'"
                    :field-name="'boxed'"
                    :validation-errors="validationErrors"
            />
        </div>
        <!-- Buttons -->
        <div class="mt-4">
            <Collection-item-cancel-button @click="$router.push({ name: 'collection-items.index' })">Cancel</Collection-item-cancel-button>
            <Collection-item-save-button :is-loading="isLoading">Save</Collection-item-save-button>
            <Collection-item-primary-button :is-loading="cexIsLoading" @click="handleCexClick">Get CEX Details</Collection-item-primary-button>
        </div>
    </form>
     <modal @close="toggleModal" :modalActive="modalActive">
      <div class="modal-content">
            <h2 class="font-bold text-xl text-gray-800 leading-tight text-center">Scan Barcode</h2>
            <barcode-scanner @barcodeFound="barcodeFoundHandler" :startBarcodeScanner="modalActive" ></barcode-scanner>
        </div>
    </modal>
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
import CollectionItemPrimaryButton from "../ui/CollectionItemPrimaryButton.vue";
import { BOXED_OPTIONS } from "../../constants/collectionConstants";
import BarcodeScanner from "../ui/BarcodeScanner.vue";
import { SVG_BARCODE } from "../../constants/svgConstants";

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
const { cexItem, getCexItem, isLoading: cexIsLoading } = useCex();


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

function barcodeFoundHandler(code) {
  // Use the scanned barcode data to update the collection item
  collectionItem.value.barcode = code.code;
  // Close the modal
  toggleModal();

  // Show sweet alert to let user know the barcode was scanned
  Swal.fire({
    title: 'Barcode Scanned',
    text: `Barcode: ${code.code}`,
    icon: 'success',
    showConfirmButton: false,
    timer: 2000,
  });
}

</script>

<style scoped>
  .modal-content{
    padding:10px
  }
</style>
