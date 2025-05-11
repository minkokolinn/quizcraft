<script setup>
import { onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  url: String,
})

onMounted(() => {
  router.post(props.url)
})
</script>

<template>
  <div class="flex items-center justify-center min-h-screen">
    <p class="text-lg font-medium">Redirecting to step 1...</p>
  </div>
</template>
