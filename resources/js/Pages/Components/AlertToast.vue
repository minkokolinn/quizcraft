<template>
    <div class="alert-toast-wrapper position-fixed p-3" style="z-index: 100">
        <div
            v-for="(toast,index) in toasts"
            :key="index"
            class="alert d-flex align-items-center fade show mb-2"
            :class="`alert-${toast.type}`"
            role="alert"
        >
            <div>{{ toast.message }}</div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const toasts = ref([])

const addToast = (message, type = 'success') => {
    const id = Date.now()
    toasts.value.push({ id, message, type })

    // Auto-remove after 3 seconds
    setTimeout(() => {
        toasts.value = toasts.value.filter((t) => t.id !== id)
    }, 3000)
}

// Expose the method to parent
defineExpose({ addToast })
</script>

<style scoped>
.alert-toast-wrapper {
    top: 50px;
    right: 5px;
    max-width: 100%;
    width: auto;
}

@media (min-width: 576px) {
    .alert-toast-wrapper {
        width: 500px;
    }
}

</style>