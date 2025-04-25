<template>
    <Head title=" | Dashboard "></Head>

    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-3 col-xl-3 col-md-6">
                <a href="/paper">
                    <!-- <x-dashboard-card
                                type="Paper"
                                icon="bi bi-envelope-paper-fill"
                                caption="Total Paper"
                                count="{{ $paperCount }}"
                                class="bg-primary"
                            ></x-dashboard-card> -->
                    <div class="card info-card sales-card cardhover bg-primary">
                        <div class="card-body">
                            <h5 class="card-title text-white">New Paper</h5>

                            <div class="d-flex align-items-center">
                                <div
                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                                >
                                    <i class="bi bi-file-earmark-plus-fill"></i>
                                </div>
                                <span class="text-white pt-2 ps-3"
                                    >Create a new question paper</span
                                >
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xxl-3 col-xl-3 col-md-6">
                <a href="/paper">
                    <!-- <x-dashboard-card
                                type="Paper"
                                icon="bi bi-envelope-paper-fill"
                                caption="Total Paper"
                                count="{{ $paperCount }}"
                                class="bg-primary"
                            ></x-dashboard-card> -->
                    <div class="card info-card sales-card cardhover bg-primary">
                        <div class="card-body">
                            <h5 class="card-title text-white">Open Paper</h5>

                            <div class="d-flex align-items-center">
                                <div
                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                                >
                                    <i class="bi bi-cloud-upload-fill"></i>
                                </div>
                                <span class="text-white pt-2 ps-3"
                                    >Open an existing paper</span
                                >
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xxl-3 col-xl-3 col-md-6">
                <Link href="/type">
                    <!-- <x-dashboard-card
                                type="Quiz Type"
                                icon="bi bi-list-columns"
                                caption="Total Quiz Type"
                                count="{{ $types->count() }}"
                                class="bg-success"
                            ></x-dashboard-card> -->
                    <DashboardCard
                        type="Type"
                        icon="bi bi-list-columns"
                        caption="Total Question Type"
                        :count="types.length"
                        bgClass="bg-success"
                    />
                </Link>
            </div>
            <div class="col-xxl-3 col-xl-3 col-md-6">
                <Link href="/question">
                    <!-- <x-dashboard-card
                                type="Quizs"
                                icon="bi bi-question-octagon-fill"
                                caption="Total Quizs"
                                count="{{ $quizTotalCount }}"
                                class="bg-danger"
                            ></x-dashboard-card> -->
                    <DashboardCard
                        type="Question"
                        icon="bi bi-question-octagon-fill"
                        caption="Total Question"
                        :count="questionTotalCount"
                        bgClass="bg-danger"
                    />
                </Link>
            </div>
            <hr class="mb-4" />

            <div
                v-for="type in types"
                :key="type.id"
                class="col-xxl-4 col-xl-4 col-md-6"
            >
                <Link href="/question" :data="{ type: type.id }">
                    <!-- <x-dashboard-card
                                type="{{ $type->name }}"
                                icon="bi bi-database-fill"
                                caption="worth {{ $type->mark == 1 ? $type->mark . ' mark' : $type->mark . ' marks' }}"
                                count="{{ $type->quizzes->count() }}"
                                class="bg-dark"
                            ></x-dashboard-card> -->
                    <DashboardCard
                        :type="type.name"
                        icon="bi bi-database-fill"
                        :caption="`worth ${type.mark} ${
                            type.mark == 1 ? 'mark' : 'marks'
                        }`"
                        :count="type.questions_count"
                        bgClass="bg-dark"
                    />
                </Link>
            </div>

            <!-- End Left side columns -->
        </div>
    </section>
</template>
<script setup>
import MainLayout from "../Layouts/MainLayout.vue";
import DashboardCard from "./Components/DashboardCard.vue";

defineOptions({
    layout: MainLayout,
});
defineProps({
    types: Array,
    questionTotalCount: Number,
});

</script>
