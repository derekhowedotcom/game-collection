<template>
    <div class="min-h-screen bg-gray-200">
        <nav class="flex items-center justify-between flex-wrap border-b border-gray-100 p-2 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center flex-shrink-0 text-white mr-6   ">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                  <router-link :to="{ name: 'collection-items.index' }" class="flex items-center flex-shrink-0 text-white mr-6">
                    <span v-html="SVG_LOGO"></span>
                  </router-link>
                </div>
            </div>
            <!--Mobile menu button-->
            <div class="block lg:hidden">
                <button @click="toggleNav" class="flex items-center px-3 py-2 border rounded text-grey-900 border-grey-900 hover:text-grey hover:border-grey">
                  <span v-html="SVG_MOBILE_MENU"></span>
                </button>
            </div>
            <div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto lg:flex" :class="showMenu ? '' : 'hidden'">
                <div class="text-sm lg:flex-grow mt-9">
                    <router-link :to="{ name: 'collection-items.index' }" active-class="font-bold" class="block mt-4 lg:inline-block lg:mt-0 lg:mr-5 px-1 pt-1 text-lg font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out text-center">
                        Collection
                    </router-link>
                    <router-link v-if="can('collection-items.create')" :to="{ name: 'collection-items.create' }" active-class="font-bold" class="block mt-4 lg:inline-block lg:mt-0 px-1 pt-1  text-lg font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out text-center">
                        New Collection Item
                    </router-link>
                    <a href="#" @click="logout" active-class="font-bold" class="lg:hidden block mt-4 lg:inline-block lg:mt-0 px-1 pt-1  text-lg font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out text-center">Logout</a>
                </div>
                <div class="flex items-center mt-5 hidden lg:flex">
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
import {SVG_LOGO, SVG_MOBILE_MENU} from "../constants/svgConstants";

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
