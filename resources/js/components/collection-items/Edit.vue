<template>
    <form @submit.prevent="updateCollectionItem(collectionItem)">
        <!-- Thumbnail -->
        <div class="mt-4">
            <label for="thumbnail" class="block font-medium text-sm text-gray-700">
                Thumbnail
            </label>
            <img :src="getThumbUrl()" alt="Placeholder Image" class="mt-2 mb-3 h-auto max-w-xs rounded-lg"/>
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
              :field-name="'category'"
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
              :field-name="'rarity_id'"
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
import { onMounted, reactive, ref, computed, watch } from "vue";
import {useRoute, useRouter} from "vue-router";
import useCex from '../../composables/cex';
import useCategories from "../../composables/categories";
import useRarities from '../../composables/rarities';
import useCollectionItems from "../../composables/collectionItems";
import Swal from 'sweetalert2';
import { getCexCategory } from "../../helpers/cexHelpers";
import CollectionItemTextInput from "../ui/CollectionItemTextInput.vue";
import CollectionItemTextArea from "../ui/CollectionItemTextArea.vue";
import CollectionItemSelect from "../ui/CollectionItemSelect.vue";
import CollectionItemRadio from "../ui/CollectionItemRadio.vue";
import { BOXED_OPTIONS } from "../../constants/collectionConstants";
import BarcodeScanner from "../ui/BarcodeScanner.vue";
import Modal from "../Modal.vue";
import {SVG_BARCODE, SVG_TICK} from "../../constants/svgConstants";
import CollectionItemCancelButton from "../ui/CollectionItemCancelButton.vue";
import CollectionItemSaveButton from "../ui/CollectionItemSaveButton.vue";
import CollectionItemPrimaryButton from "../ui/CollectionItemPrimaryButton.vue";
import {useAbility} from "@casl/vue";

const { categories, getCategories } = useCategories();
const { rarities, getRarities } = useRarities();
const {
    collectionItem = ref(''),
    getCollectionItem,
    updateCollectionItem,
    validationErrors,
    isLoading
} = useCollectionItems();
const { cexItem, getCexItem, isLoading: cexIsLoading } = useCex();
const cexErrorMessage = ref(null);
const thumbnailUrl = ref(false);
const modalActive = ref(false);
const toggleModal = () => {
  modalActive.value = !modalActive.value;
};
const route = useRoute();
const router = useRouter()
const { can } = useAbility();

onMounted(() => {
  // If the user does not have the ability to update collection items redirect them to the index page
  if(!can('collection-items.update')){
    router.push({ name: 'collection-items.index' });
  }
    getCollectionItem(route.params.id)
    getCategories()
    getRarities()
})

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
            cexErrorMessage.value = null;

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

function getThumbUrl() {

    if(collectionItem.value.thumbnail && thumbnailUrl.value === false){
        return '/storage/images/collection-items/' + collectionItem.value.thumbnail;
    }

    //if the upload has been changed use that image
    if(thumbnailUrl.value){
        return thumbnailUrl.value;
    }

    //if no other images just use the placeholder
    return '/storage/images/collection-items/image-placeholder.jpg';
}

function onFileChange(target) {
    collectionItem.value.thumbnail = target;

    //create url to show image preview
    if(target){
        thumbnailUrl.value = URL.createObjectURL(target);
        collectionItem.value.cex_image = '';
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
