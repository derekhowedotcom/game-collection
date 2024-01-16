<script setup>
  import { ref, toRef, watch } from 'vue';

  const props = defineProps({
  id: {
  type: String,
  required: true,
},
  value: {
  type: [String, Number],
  required: true,
},
  categories: {
  type: Array,
  required: true,
},
  fieldName: {
  type: String,
  required: true,
},
  validationErrors: {
  type: Object,
  required: true,
},
  label: {
  type: String,
  required: true,
},
});

const emit = defineEmits(['update:value', 'blur', 'keydown']);

// Reactive reference for input value
const inputValue = ref(props.value);

function updateValue(newValue) {
  // Update internal state
  inputValue.value = newValue;

  // Emit event to parent component
  emit('update:value', newValue);
}

//watch for if value is changed and update the v-model value
watch(toRef(props,'value'),  (newValue) => {
  inputValue.value = newValue;
});
</script>

<template>
<span class="block font-medium text-sm text-gray-700">
  {{ label }}
</span>
<div class="mt-2">
  <div v-for="category in categories" :key="category.id" class="inline-flex items-center">
    <input
        type="radio"
        :id="id + '-' + category.id"
        :value="category.id"
        v-model="inputValue"
        class="form-radio"
        @change="updateValue(inputValue)"
    >
    <label :for="id + '-' + category.id" class="inline-flex items-center ml-3 mr-2">
      {{ category.name }}
    </label>
  </div>
  <div v-if="validationErrors[fieldName]" class="text-red-600 mt-1">
    <div v-for="message in validationErrors[fieldName]" :key="message">
      {{ message }}
    </div>
  </div>
</div>
</template>
