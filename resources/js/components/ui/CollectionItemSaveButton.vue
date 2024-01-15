<script setup>
defineProps({
  isLoading: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['click']);
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
      <svg class="w-6 h-6 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"
           xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
      </svg>
      Save
    </span>
  </button>
</template>


