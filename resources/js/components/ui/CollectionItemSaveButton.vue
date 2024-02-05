<script setup>
defineProps({
  isLoading: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['click']);

import { SVG_TICK } from "../../constants/svgConstants";
</script>
<template>
  <button
      :disabled="isLoading"
      @click="$emit('click')"
      class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-3 rounded ml-2 disabled:opacity-75 disabled:cursor-not-allowed"
  >
    <div v-show="isLoading"
         class="inline-block animate-spin w-4 h-4 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></div>
    <span v-if="isLoading">Processing...</span>
    <span v-else>
      <span v-html="SVG_TICK"></span>
      <slot></slot>
    </span>
  </button>
</template>


