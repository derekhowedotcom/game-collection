<template>
    <!-- Image -->
    <div class="mb-4">
        <img width="300" :src="`/storage/images/collection-items/${ collectionItem.thumbnail }`" alt="" />
    </div>
    <!-- Barcode -->
    <div v-if="collectionItem.barcode" class="mb-4">
        <h1><span class="block font-medium text-sm text-gray-700">Barcode:</span></h1>
        <p>{{ collectionItem.barcode }}</p>
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
        <p>{{ boxedText }}</p>
    </div>
    <!-- Buttons -->
  <div class="mt-4">
    <router-link class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                 v-if="can('collection-items.update')"
                 :to="{ name: 'collection-items.edit', params: { id: this.$route.params.id } }">
      <span v-html="SVG_EDIT"></span>
      Edit
    </router-link>

    <a href="#" v-if="can('collection-items.delete')" @click.prevent="deleteCollectionItem(collectionItem.id)"
       class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">
      <span v-html="SVG_DELETE"></span>
      Delete</a>
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
import { basename } from '../../helpers/fileHelpers';
import { formatCurrency } from '../../helpers/numberHelpers'
import {SVG_DELETE, SVG_EDIT} from "../../constants/svgConstants";

// Extract composables
const { categories, getCategories } = useCategories();
const { collectionItem, getCollectionItem, deleteCollectionItem } = useCollectionItems();

// Get route and abilities
const route = useRoute();
const { can } = useAbility();

// On mount actions
onMounted(() => {
    getCollectionItem(route.params.id);
    getCategories();
});

// Computed properties
const boxedText = computed(() => {
    return collectionItem.value.boxed === 1 ? 'Yes' : 'No';
});
</script>
