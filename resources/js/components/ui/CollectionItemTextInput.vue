<script setup>
import {onMounted, ref, toRef, watch} from "vue";

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number],
        required: true,
        default: '',
    },
    fieldName: {
        type: String,
        required: true,
    },
    validationErrors: {
        type: Object,
        required: true,
    },
    otherErrorMessage: {
        type: String,
        required: false,
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
    <label :for="id" class="block font-medium text-sm text-gray-700">
        {{ label }}
    </label>
    <input v-model="inputValue" type="text" :id="id"
           class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
           @blur="updateValue(inputValue)"
           @keydown="updateValue(inputValue)"
    >
    <div class="text-red-600 mt-1">
        <div v-for="message in validationErrors[fieldName]" :key="message">
            {{ message }}
        </div>
    </div>
    <div class="text-red-600 mt-1" v-if="otherErrorMessage && otherErrorMessage.length > 0">
        {{ otherErrorMessage }}
    </div>
</template>
