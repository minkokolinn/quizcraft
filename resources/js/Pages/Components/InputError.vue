<template>
    <div v-if="message" class="text-danger mt-1">
        {{ message }}
    </div>
</template>
<script setup>
defineProps({
    message:String
});
</script>
