<script setup>
defineProps({
  disabled: {
    type: Boolean,
    default: false,
  },
});

defineEmits(['click']);
</script>
<template>
  <button
      @click="$emit('click')"
      type="button"
      class="inline-flex content-center items-center mt-3 px-3 py-2 bg-red-600 text-white rounded disabled:opacity-75 disabled:cursor-not-allowed"
      :disabled="disabled"
  >
    Cancel
  </button>
</template>


