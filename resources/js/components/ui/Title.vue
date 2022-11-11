<template>
    <router-link class="router-link" v-if="can('collection-items.update')" :to="{ name: 'collection-items.edit', params: { id: collectionItem.id } }">{{ collectionItem.title }}</router-link>
    <span v-else>{{ collectionItem.title }}</span>
</template>

<script>
import { ref } from 'vue';
import { useAbility } from '@casl/vue'

export default {
    setup(){            
        const { can } = useAbility()
        return {  can }
    },
    props: { 
        collectionItem: Object 
    },
    
}
</script>

<style scoped>
.router-link:hover {
    color: rgb(37 99 235)
}
</style>
