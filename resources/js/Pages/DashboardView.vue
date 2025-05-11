<template>
    <Head title=" | Dashboard "></Head>

    <!-- Add this after your existing cards -->
    <div
        class="modal fade"
        id="importModal"
        ref="importModel"
        tabindex="-1"
        aria-labelledby="importModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">
                        Import Project File
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="closeimportModel"
                    ></button>
                </div>
                <div class="modal-body">
                    <input
                        type="file"
                        accept=".txt"
                        @change="onFileChange"
                        class="form-control"
                    />
                    <p v-if="errorMessage" class="text-danger mt-2">
                        {{ errorMessage }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="handleImport"
                    >
                        Import
                    </button>
                </div>
            </div>
        </div>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-xxl-3 col-xl-3 col-md-6">
                <Link href="/paper/create/step/1">
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
                </Link>
            </div>
            <div class="col-xxl-3 col-xl-3 col-md-6">
                <a
                    href="#"
                    data-bs-toggle="modal"
                    data-bs-target="#importModal"
                >
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
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import MainLayout from "../Layouts/MainLayout.vue";
import DashboardCard from "./Components/DashboardCard.vue";
import CryptoJS from "crypto-js";

defineOptions({
    layout: MainLayout,
});
defineProps({
    types: Array,
    questionTotalCount: Number,
});

const file = ref(null);
const errorMessage = ref("");

const onFileChange = (e) => {
    file.value = e.target.files[0];
};

const handleImport = () => {
    errorMessage.value = "";

    if (!file.value) {
        errorMessage.value = "Please select a file first.";
        return;
    }

    const reader = new FileReader();
    reader.onload = (event) => {
        try {
            const secretKey = "babymonster"; // Match the export key
            const encrypted = event.target.result;

            // Decrypt
            const bytes = CryptoJS.AES.decrypt(encrypted, secretKey);
            const decryptedString = bytes.toString(CryptoJS.enc.Utf8);

            if (!decryptedString) {
                throw new Error("Decryption failed");
            }

            const parsed = JSON.parse(decryptedString);

            if (!parsed.info || !Array.isArray(parsed.sections)) {
                throw new Error("Invalid file structure.");
            }

            const modalEl = document.getElementById("importModal");
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance.hide();

            router.post("/paper/create/step/2", {
                info: parsed.info,
                sections: parsed.sections,
            });
        } catch (e) {
            errorMessage.value =
                "Invalid or corrupted file, or decryption failed.";
        }
    };

    reader.onerror = () => {
        errorMessage.value = "Failed to read the file.";
    };

    reader.readAsText(file.value);
};

const importModel = ref(null);
const closeimportModel = () => {
    if (document.activeElement) {
        document.activeElement.blur();
    }
    const modal = bootstrap.Modal.getInstance(importModel.value);
    modal.hide();
};
</script>
