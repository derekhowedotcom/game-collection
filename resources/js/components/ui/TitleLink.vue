<template>
    <router-link class="router-link" :to="{ name: 'collection-items.details', params: { id: collectionItem.id } }">{{ collectionItem.title }}</router-link>
</template>

<script setup>
import { ref } from 'vue';
import { useAbility } from '@casl/vue';

const { can } = useAbility();

// Declare props directly within setup
const props = defineProps({
  collectionItem: Object,
});
</script>

<style scoped>
.router-link:hover {
    color: rgb(37 99 235)
}
</style>
