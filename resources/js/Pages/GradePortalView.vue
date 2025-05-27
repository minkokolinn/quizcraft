<template>
    <Head title=" | Grade Portal "></Head>

    <div class="p-5">
        <h1 class="text-center fw-bold mb-5">CHOOSE GRADE</h1>
        <div
            class="d-flex justify-content-center align-items-center flex-wrap gap-5"
        >
            <button
                v-for="(grade, index) in grades"
                :key="index"
                class="btn btn-outline-primary btn-lg grade-circle-btn"
                @click="selectGrade(grade)"
            >
                {{ grade }}
            </button>
        </div>
    </div>
</template>
<script setup>
import { router } from "@inertiajs/vue3";
import PortalLayout from "../Layouts/PortalLayout.vue";
defineOptions({
    layout: PortalLayout,
});

const props = defineProps({
    user: Object,
});

const grades = props.user.grade.split(",");

function selectGrade(grade) {
    router.post('/grade-portal-process', { grade })
}
</script>
<style scoped>
.grade-circle-btn {
    width: 140px; /* bigger size for large */
    height: 140px;
    border-radius: 50%;
    font-size: 4rem; /* larger font */
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.grade-circle-btn:hover {
    background-color: #0d6efd; /* Bootstrap primary */
    color: white;
    border-color: #0d6efd;
}
</style>
