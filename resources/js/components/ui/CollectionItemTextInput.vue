<script setup>
defineProps({
    collectionItem: {
        type: Object,
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

</script>

<template>
     <label for="collectionItem-title" class="block font-medium text-sm text-gray-700">
            {{ label }}:
     </label>
     <input v-model="collectionItem.title" id="collectionItem-title" type="text"
               class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
     <div class="text-red-600 mt-1">
        <div v-for="message in validationErrors?.title" :key="message">
            {{ message }}
        </div>
     </div>
</template>

