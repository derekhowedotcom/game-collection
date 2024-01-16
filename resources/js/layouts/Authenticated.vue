<template>
    <div class="min-h-screen bg-gray-200">
        <nav class="flex items-center justify-between flex-wrap border-b border-gray-100 p-2 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center flex-shrink-0 text-white mr-6   ">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
viewBox="0 0 58 58" style="enable-background:new 0 0 316 316;" xml:space="preserve" class="w-14 h-14 fill-current text-gray-500">
<path style="fill:none;stroke:#38454F;stroke-width:2;stroke-linecap:round;stroke-miterlimit:10;" d="M19,31V13.5
c0-2.475,2.025-4.5,4.5-4.5h0c2.475,0,4.5,2.025,4.5,4.5V20c0,2.2,1.8,4,4,4h0c2.2,0,4-1.8,4-4V1"/>
<path style="fill:#546A79;" d="M54.016,58H3.984C2.336,58,1,56.664,1,55.016V31.984C1,30.336,2.336,29,3.984,29h50.032
C55.664,29,57,30.336,57,31.984v23.032C57,56.664,55.664,58,54.016,58z"/>
<polygon style="fill:#38454F;stroke:#CBD4D8;stroke-width:2;stroke-miterlimit:10;" points="17,41 14,41 14,38 9,38 9,41 6,41 6,46
9,46 9,49 14,49 14,46 17,46 "/>
<rect x="41" y="46" style="fill:#DD352E;stroke:#CBD4D8;stroke-width:2;stroke-miterlimit:10;" width="4" height="4"/>
<rect x="49" y="46" style="fill:#DD352E;stroke:#CBD4D8;stroke-width:2;stroke-miterlimit:10;" width="4" height="4"/>
<rect x="21" y="45" style="fill:#FFFFFF;" width="16" height="5"/>
<line style="fill:none;stroke:#CBD4D8;stroke-width:2;stroke-miterlimit:10;" x1="21" y1="54" x2="37" y2="54"/>
<line style="fill:none;stroke:#CBD4D8;stroke-width:2;stroke-miterlimit:10;" x1="21" y1="41" x2="37" y2="41"/>
<line style="fill:none;stroke:#CBD4D8;stroke-width:2;stroke-miterlimit:10;" x1="21" y1="37" x2="37" y2="37"/>
<line style="fill:none;stroke:#CBD4D8;stroke-width:2;stroke-miterlimit:10;" x1="21" y1="33" x2="37" y2="33"/>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>      </a>

                </div>
            </div>
            <!--Mobile menu button-->
            <div class="block lg:hidden">
                <button @click="toggleNav" class="flex items-center px-3 py-2 border rounded text-grey-900 border-grey-900 hover:text-grey hover:border-grey">
                <svg class="fill-current h-6 w-6" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                </button>
            </div>
            <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto sm:flex" :class="showMenu ? '' : 'hidden'">
                <div class="text-sm lg:flex-grow mt-9">
                    <router-link :to="{ name: 'collection-items.index' }" active-class="font-bold" class="block mt-4 lg:inline-block lg:mt-0 lg:mr-5 px-1 pt-1 text-lg font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out text-center">
                        Collection
                    </router-link>
                    <router-link v-if="can('collection-items.create')" :to="{ name: 'collection-items.create' }" active-class="font-bold" class="block mt-4 lg:inline-block lg:mt-0 px-1 pt-1  text-lg font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out text-center">
                        New Collection Item
                    </router-link>
                    <a href="#" @click="logout" active-class="font-bold" class="lg:hidden block mt-4 lg:inline-block lg:mt-0 px-1 pt-1  text-lg font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out text-center">Logout</a>

                </div>
                <div class="flex items-center mt-5 hidden sm:flex">
                    <div>
                        <div>Hi, {{ user.name }}</div>
                        <div class="text-sm text-gray-500">{{ user.email }}</div>
                    </div>
                    <button @click="logout" type="button" class="inline-flex items-center h-10 px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 ml-4" :class="{ 'opacity-25': processing }" :disabled="processing">
                        Log out
                    </button>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white shadow text-center">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ currentPageTitle }}
                    <h3 class="font-semibold text-xs text-gray-800 leading-tight">
                        Hardware: {{collectionItemCounts?.hardware}}</h3>
                    <h3 class="font-semibold text-xs text-gray-800 leading-tight">
                        Software: {{collectionItemCounts?.software}}</h3>
                    <h3 class="font-semibold text-xs text-gray-800 leading-tight">
                        Other: {{collectionItemCounts?.other}}</h3>
                    <h3 class="font-bold text-xs text-gray-800 leading-tight">
                        Total collection items: {{collectionItemCounts?.total}}</h3>
                  <h3 class="font-bold text-xs text-gray-800 leading-tight">
                    Total Value: &pound;{{ formatCurrency(collectionItemValueAndAmountSpent?.totalValue) }}</h3>
                  <h3 class="font-bold text-xs text-gray-800 leading-tight">
                    Total Spent: &pound;{{ formatCurrency(collectionItemValueAndAmountSpent?.totalAmountSpent) }}</h3>
                </h2>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <div class="py-2">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 bg-white border-b border-gray-200">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';
import useAuth from '../composables/auth';
import { useAbility } from '@casl/vue'
import { useRoute } from "vue-router";
import useCollectionItemCounts from '../composables/collectionItemCounts'
import { formatCurrency } from '../helpers/numberHelpers'

const { user, processing, logout } = useAuth()
const { can } = useAbility()
const {
  collectionItemCounts,
  getCollectionItemCounts,
  collectionItemValueAndAmountSpent,
  getCollectionItemValueAndAmountSpent
} = useCollectionItemCounts({
  categoryNames: 'hardware,software,other',
})

// Get the current route
const route = useRoute()

onMounted(() => {
    getCollectionItemCounts()
    getCollectionItemValueAndAmountSpent()
})

//show/hide mobile menu
const showMenu = ref(false);
// Toggle mobile menu
const toggleNav = () => (showMenu.value = !showMenu.value);
// Get current page title
const currentPageTitle = computed(() => route.meta.title);


</script>
