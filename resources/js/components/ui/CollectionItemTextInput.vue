<script setup>
import {onMounted, ref, toRef, watch} from "vue";

const props = defineProps({

    value: {
        type: String,
    }, // Optional initial value
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
        <label for="collectionItem-title" class="block font-medium text-sm text-gray-700">
            {{ label }}
        </label>
        <input v-model="inputValue" id="collectionItem-title" type="text"
               class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
               @blur="updateValue(inputValue)" @keydown="updateValue(inputValue)"
        >
        <div class="text-red-600 mt-1">
            <div v-for="message in validationErrors?.title" :key="message">
                {{ message }}
            </div>
        </div>
    </div>
</template>
