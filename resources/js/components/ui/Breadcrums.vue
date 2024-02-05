<template>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li v-for="(route, index) in routes" :key="index" class="breadcrumb-item">
        <router-link v-if="route.path !== $route.path" :to="route.path">{{ route.meta.breadcrumb }}</router-link>
        <span v-else>{{ route.meta.breadcrumb }}</span>
      </li>
    </ol>
  </nav>
</template>

<script setup>
import { useRoute } from 'vue-router';
import {computed} from "vue";

const route = useRoute();

const routes = computed(() => {
  const matched = route.matched.filter((r) => r.meta && r.meta.breadcrumb);
  return matched.slice().reverse();
});
</script>
