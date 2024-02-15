<template>
  <div v-if="isCollectionItemLoading" class="flex items-center justify-center mt-10 mb-10">
    <div class="inline-block content-center animate-spin w-20 h-20 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></div>
  </div>
  <div v-else-if="!isCollectionItemLoading">
    <!-- Image -->
    <div v-if="collectionItem.thumbnail" class="mb-4 flex items-center justify-center md:justify-start">
        <img width="300" :src="`/storage/images/collection-items/${ collectionItem.thumbnail }`" alt="" />
    </div>
    <!-- Retailers -->
    <div class="mb-4 flex items-center justify-center md:justify-start">
      <a :href="`https://www.ebay.co.uk/sch/i.html?_nkw=${ collectionItem.barcode || collectionItem.title }`"
         :title="`eBay - ${ collectionItem.title }`" target="_blank">
        <span class="block w-20 mr-4" v-html="SVG_EBAY_LOGO"></span>
      </a>
      <a v-if="collectionItem.barcode" :href="`https://uk.webuy.com/product-detail/?id=${ collectionItem.barcode }`"
         :title="`Cex - ${ collectionItem.title }`" target="_blank">
        <span class="block w-20" v-html="SVG_CEX_LOGO"></span>
      </a>
     <a v-else :href="`https://uk.webuy.com/search?stext=${ collectionItem.title }`" target="_blank">
        <span class="block w-20" v-html="SVG_CEX_LOGO"></span>
      </a>
    </div>
      <!-- Barcode -->
      <div v-if="collectionItem.barcode" class="mb-4">
          <h1><span class="block font-medium text-sm text-gray-700">Barcode:</span></h1>
          <p>{{ collectionItem.barcode }}</p>
      </div>
      <!-- Title -->
    <div class="mb-4">
      <h1><span class="block font-medium text-sm text-gray-700">Title:</span></h1>
      <p>
        <title-edit-component @quick-edit-save="quickEditSaveHandler"
                              @quick-edit-esc-key="resetTitle"
                              :collectionItem="collectionItem"
                              :canEdit="can('collection-items.update')"
                              :isLoading="isLoading"
        />
      </p>
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
      <!-- Rarity -->
      <div v-if="collectionItem.rarity" class="mb-4">
          <h1><span class="block font-medium text-sm text-gray-700">Rarity:</span></h1>
          <p>{{ collectionItem.rarity }}</p>
      </div>
      <!-- Value -->
      <div class="mb-4" v-if="collectionItem.value">
          <h1><span class="block font-medium text-sm text-gray-700">Value:</span></h1>
          <p>&pound;{{ formatCurrency(collectionItem.value) }}</p>
      </div>
      <!-- Price Paid -->
      <div v-if="collectionItem.price_paid" class="mb-4">
          <h1><span class="block font-medium text-sm text-gray-700">Price Paid:</span></h1>
          <p>&pound;{{ formatCurrency(collectionItem.price_paid) }}</p>
      </div>
      <!-- Boxed -->
      <div class="mb-4">
          <h1><span class="block font-medium text-sm text-gray-700">Boxed:</span></h1>
          <p>{{ collectionItem.boxed === 1 ? 'Yes' : 'No' }}</p>
      </div>
      <!-- Buttons -->
    <div class="mt-4">
      <router-link class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                   v-if="can('collection-items.update')"
                   :to="{ name: 'collection-items.edit', params: { id: router.currentRoute.value.params.id } }">
        <span v-html="SVG_EDIT"></span>
        Edit
      </router-link>

      <a href="#" v-if="can('collection-items.delete')" @click.prevent="deleteCollectionItem(collectionItem.id)"
         class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">
        <span v-html="SVG_DELETE"></span>
        Delete</a>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCategories from '../../composables/categories';
import useCollectionItems from '../../composables/collectionItems';
import { useAbility } from '@casl/vue';
import titleEditComponent from '../ui/TitleEdit.vue';
import { formatDate } from '../../helpers/dateHelpers';
import { useRouter } from 'vue-router'
import { formatCurrency } from '../../helpers/numberHelpers'
import {SVG_DELETE, SVG_EDIT, SVG_EBAY_LOGO, SVG_CEX_LOGO} from "../../constants/svgConstants";

// Extract composables
const { categories, getCategories } = useCategories();
const { collectionItem, getCollectionItem, deleteCollectionItem, updateCollectionItem, isLoading: isCollectionItemLoading } = useCollectionItems();

// Get route and abilities
const route = useRoute();
const router = useRouter();
const { can } = useAbility();

// Define emits
const emit = defineEmits(['close-menu']);

// On mount actions
onMounted(() => {
    getCollectionItem(route.params.id);
    getCategories();

    emit('close-menu', true);
});

const quickEditSaveHandler = () => {
    // update the collection item
    updateCollectionItem(collectionItem.value, true);
};

const resetTitle = (title) => {
    collectionItem.value.title = title;
};
</script>
