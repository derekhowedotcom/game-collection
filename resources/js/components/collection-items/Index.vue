<template>
    <div class="overflow-hidden overflow-x-auto p-1 bg-white border-gray-200">
        <div class="min-w-full align-middle">
            <div class="mb-4 grid lg:grid-cols-4 gap-4">
<!--                <input v-model="search_global" type="text" placeholder="Search everything..."-->
<!--                       class="inline-block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">-->
                <input v-model="search_title" type="text"
                       @keyup="updatePage(1)"
                       class="inline-block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                       placeholder="Search Title...">

            </div>
            <div class="mb-4 grid lg:grid-cols-4 gap-4">
                <select v-model="search_category"
                        @change="updatePage(1)"
                        class="inline-block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" selected>-- All Categories --</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>
          <div v-if="isCollectionItemsLoading" class="flex items-center justify-center mt-10 mb-10">
            <div class="inline-block content-center animate-spin w-20 h-20 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></div>
          </div>
            <table class="min-w-full divide-y divide-gray-200 border mb-4" v-if="collectionItems.data && collectionItems.data.length > 0 && !isCollectionItemsLoading">
                <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left">
                        &nbsp;
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left">
                        <!--                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Title</span>-->
                        <div class="flex flex-row items-center justify-between cursor-pointer"
                             @click="updateOrdering('title')">
                            <div class="leading-4 font-medium text-gray-500 uppercase tracking-wider"
                                 :class="{ 'font-bold text-blue-600': orderColumn === 'title' }">
                                Title
                            </div>
                            <div class="select-none">
                                <span :class="{
                                  'text-blue-600': orderDirection === 'desc' && orderColumn === 'title',
                                  'hidden': orderDirection !== '' && orderDirection !== 'desc' && orderColumn === 'title',
                                }">&uarr;</span>
                                <span :class="{
                                  'text-blue-600': orderDirection === 'asc' && orderColumn === 'title',
                                  'hidden': orderDirection !== '' && orderDirection !== 'asc' && orderColumn === 'title',
                                }">&darr;</span>
                            </div>
                        </div>
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left">
                        <span
                            class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Category</span>
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                <tr v-for="collectionItem in collectionItems.data" :key="collectionItem.id">
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                        <router-link v-if="collectionItem.thumbnail"
                                     :to="{ name: 'collection-items.details', params: { id: collectionItem.id } }"><img
                            :src="`/storage/images/collection-items/small/${collectionItem.thumbnail}`" class="table-image"
                            alt=""/></router-link>
                        <router-link v-else
                                     :to="{ name: 'collection-items.details', params: { id: collectionItem.id } }">
                          <img :src="`/storage/images/collection-items/image-placeholder.jpg`" class="table-image" alt=""/>
                        </router-link>

                    </td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                        <titleLinkComponent :collectionItem="collectionItem"/>
                    </td>
                    <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                        {{ collectionItem.category }}
                    </td>
                </tr>
                </tbody>
            </table>
            <div v-else-if="!isCollectionItemsLoading" class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="flex justify-center items-center">
                    <div class="text-gray-500">No collection items found.</div>
                </div>
            </div>
            <div class="flex justify-center items-center">
                <Pagination :data="collectionItems"
                            @pagination-change-page="page => updatePage(page)"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted, watch } from 'vue';
import useCollectionItems from '../../composables/collectionItems';
import useCategories from '../../composables/categories';
import { current } from 'tailwindcss/colors';
import { useAbility } from '@casl/vue';
import titleLinkComponent from '../ui/TitleLink.vue';
import { formatDate } from '../../helpers/dateHelpers';
import { basename } from '../../helpers/fileHelpers';
import useCollectionItemCounts from '../../composables/collectionItemCounts';
import { useRouter } from 'vue-router';

const router = useRouter()
const search_category = ref(router.currentRoute.value.query.searchCategory || '');
const search_id = ref('');
const search_title = ref(router.currentRoute.value.query.searchTitle || '');
const search_description = ref('');
const search_global = ref('');
const orderColumn = ref('title');
const orderDirection = ref('asc');
const currentPage = ref(router.currentRoute.value.query.page || 1);

const { collectionItems, getCollectionItems, deleteCollectionItem, isLoading: isCollectionItemsLoading } = useCollectionItems();
const { categories, getCategories } = useCategories();
const { can } = useAbility();

// Define emits
const emit = defineEmits(['close-menu']);

const {
  collectionItemValueAndAmountSpent,
  getCollectionItemValueAndAmountSpent,
} = useCollectionItemCounts({});

onMounted(() => {
  getCollectionItems();
  getCategories();
  getCollectionItemValueAndAmountSpent();

  emit('close-menu', true);
});

// detect browser back/forward button
window.addEventListener('popstate', () => {
  currentPage.value = router.currentRoute.value.query.page || 1;
  search_category.value = router.currentRoute.value.query.searchCategory || '';
  search_id.value = router.currentRoute.value.query.searchId || '';
  search_title.value = router.currentRoute.value.query.searchTitle || '';
  search_description.value = router.currentRoute.value.query.searchDescription || '';
  search_global.value = router.currentRoute.value.query.searchGlobal || '';

  getCollectionItems(
      currentPage.value,
      search_category.value,
      search_id.value,
      search_title.value,
      search_description.value,
      search_global.value,
      orderColumn.value,
      orderDirection.value
  );

});

onMounted(() => {
    getCollectionItems(currentPage.value, search_category.value);
});

const updatePage = page => {

    currentPage.value = page;
    router.push({ query: { page, searchCategory: search_category.value, searchTitle: search_title.value } });

    getCollectionItems(page, search_category.value, search_id.value, search_title.value, search_description.value, search_global.value);
};


const updateOrdering = (column) => {
  orderColumn.value = column;
  orderDirection.value = orderDirection.value === 'asc' ? 'desc' : 'asc';
  getCollectionItems(
      1,
      search_category.value,
      search_id.value,
      search_title.value,
      search_description.value,
      search_global.value,
      orderColumn.value,
      orderDirection.value
  );
};

watch(search_category, (current, previous) => {
  getCollectionItems(
      1,
      current,
      search_id.value,
      search_title.value,
      search_description.value,
      search_global.value
  );
});

watch(search_id, (current, previous) => {
    getCollectionItems(
        1,
        search_category.value,
        current,
        search_title.value,
        search_description.value,
        search_global.value,
    )
})

watch(search_title, (current, previous) => {
    getCollectionItems(
        1,
        search_category.value,
        search_id.value,
        current,
        search_description.value,
        search_global.value,
    )
})

watch(search_description, (current, previous) => {
    getCollectionItems(
        1,
        search_category.value,
        search_id.value,
        search_title.value,
        current,
        search_global.value,
    )
})

watch(search_global, (current, previous) => {
    getCollectionItems(
        1,
        search_category.value,
        search_id.value,
        search_title.value,
        search_description.value,
        current,
    )
})

</script>

<style scoped>
.table-image {
  width: 80px;
}

@media (max-width: 640px) {
  .table-image {
    width: 150px;
  }
}

</style>

