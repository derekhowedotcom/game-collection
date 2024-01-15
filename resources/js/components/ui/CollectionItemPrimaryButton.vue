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
      class="inline-flex content-center items-center mt-3 ml-3 px-3 py-2 bg-blue-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed"
      type="button"
  >
    <div v-show="isLoading"
         class="inline-block animate-spin w-4 h-4 mr-2 border-t-2 border-t-white border-r-2 border-r-white border-b-2 border-b-white border-l-2 border-l-blue-600 rounded-full"></div>
    <span v-if="isLoading">Processing...</span>
    <span v-else>
      <slot></slot>
    </span>
  </button>
</template>


