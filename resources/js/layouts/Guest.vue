<template>
    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-200">
        <div>
            <a href="/">
              <span v-html="SVG_LOGO"></span>
            </a>
        </div>
        <div>
            <h1 class="mt-5 text-2xl">Game Collection</h1>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg">
            <router-view></router-view>
        </div>
    </div>
</template>
<script setup>
import { SVG_LOGO } from "../constants/svgConstants";
</script>
