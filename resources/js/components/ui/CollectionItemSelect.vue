<script setup>
import {onMounted, ref, toRef, watch} from "vue";

const props = defineProps({
  id: {
    type: String,
    required: true,
  },
  value: {
    type: String,
    required: true,
    default: '',
  },
  categories: {
    type: Object,
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
    <div class="mt-4">

        <label :for="id" class="block font-medium text-sm text-gray-700">
            {{ label }}
        </label>
      <select v-model="inputValue" :id="id"
              class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              @blur="updateValue(inputValue)"
              @keydown="updateValue(inputValue)"
      >
        <option value="" selected>&#45;&#45; Choose {{ label }} &#45;&#45;</option>
        <option v-for="category in categories" :value="category.id" :key="category.id" >
          {{ category.name }}
        </option>
      </select>
    </div>
</template>
