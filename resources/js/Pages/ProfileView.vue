<template>
    <Head title=" | Profile "></Head>

    <div class="d-flex justify-content-between main-header">
        <div class="pagetitle">
            <h1>Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">QuizCraft | Profile</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <h3 class="mx-auto my-3 fw-bold">Profile</h3>
                <hr />
                <div class="row">
                    <div class="col-6">
                        <p class="text-end">Name <span class="ms-3">-</span></p>
                    </div>
                    <div class="col-6">
                        <p class="text-start fw-bold">{{ user.name }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="text-end">
                            Subject <span class="ms-3">-</span>
                        </p>
                    </div>
                    <div class="col-6">
                        <p class="text-start fw-bold">{{ user.subject }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="text-end">
                            Grade <span class="ms-3">-</span>
                        </p>
                    </div>
                    <div class="col-6">
                        <div>
                            <span
                                class="badge bg-primary me-2"
                                v-for="(grade, index) in user.grade.split(',')"
                                :key="index"
                            >
                                {{ grade }}
                                <i
                                    class="bi bi-x-lg ms-2 me-1"
                                    @click="removeGrade(grade)"
                                ></i>
                            </span>
                        </div>
                        <div class="col-3 my-3">
                            <div class="input-group input-group-sm">
                                <input
                                    type="number"
                                    v-model="newGrade"
                                    class="form-control"
                                    aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-sm"
                                />
                                <span
                                    class="input-group-text"
                                    style="cursor: pointer"
                                    id="inputGroup-sizing-sm"
                                    @click="addGrade"
                                    >Save</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="text-end">
                            Chapter <span class="ms-3">-</span>
                        </p>
                    </div>
                    <div class="col-6">
                        <div>
                            <span
                                class="badge bg-primary me-2"
                                v-for="(chapter, index) in user.chapter.split(
                                    ','
                                )"
                                :key="index"
                            >
                                {{ chapter }}
                                <i
                                    class="bi bi-x-lg ms-2 me-1"
                                    @click="removeChapter(chapter)"
                                ></i>
                            </span>
                        </div>
                        <div class="col-3 my-3">
                            <div class="input-group input-group-sm">
                                <input
                                    type="number"
                                    v-model="newChapter"
                                    class="form-control"
                                    aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-sm"
                                />
                                <span
                                    class="input-group-text"
                                    style="cursor: pointer"
                                    id="inputGroup-sizing-sm"
                                    @click="addChapter"
                                    >Save</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-6"></div>
                    <div class="col-6 my-3">
                        <form
                            ref="backupForm"
                            action="/run-backup"
                            method="POST"
                            target="_blank"
                            style="display: none"
                        >
                            <input
                                type="hidden"
                                name="_token"
                                :value="csrfToken"
                            />
                            <input
                                type="hidden"
                                name="key"
                                value="0WJHQ6DlhBquuA6DQke34pEe5TBFrT"
                            />
                        </form>

                        <button class="btn btn-info" @click="backupdata">
                            Back Up & Download
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { router } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import MainLayout from "../Layouts/MainLayout.vue";

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    user: Object,
});

const user = ref(props.user);
const newGrade = ref(null);
const newChapter = ref(null);

const backupForm = ref(null);
const csrfToken = ref("");

onMounted(() => {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (tokenMeta) {
        csrfToken.value = tokenMeta.getAttribute('content');
    } else {
        console.error("CSRF token meta tag not found.");
    }
});

function insertInOrder(dataString, newNumber) {
    const numbers = dataString
        .split(",")
        .map(Number)
        .filter((n) => !isNaN(n));

    if (!numbers.includes(newNumber)) {
        numbers.push(newNumber);
        numbers.sort((a, b) => a - b);
    }

    return numbers.join(",");
}

function removeFromDataString(dataString, numberToRemove) {
    const numbers = dataString
        .split(",")
        .filter((n) => !isNaN(n) && n !== numberToRemove); // remove the number
    return numbers.join(",");
}

function addGrade() {
    if (newGrade.value) {
        const grade_parsed = parseInt(newGrade.value);
        const gradeToUpdate = insertInOrder(user.value.grade, grade_parsed);

        router.post(
            "/profile/update-grade",
            { grade: gradeToUpdate },
            {
                onSuccess: () => {
                    user.value.grade = gradeToUpdate;
                },
                onError: (err) => {
                    console.error("Failed to update grade", err);
                },
            }
        );
    }
}

function removeGrade(numberToRemove) {
    const gradeToUpdate = removeFromDataString(
        user.value.grade,
        numberToRemove
    );
    router.post(
        "/profile/update-grade",
        { grade: gradeToUpdate },
        {
            onSuccess: () => {
                user.value.grade = gradeToUpdate;
            },
            onError: (err) => {
                console.error("Failed to update grade", err);
            },
        }
    );
}

function addChapter() {
    if (newChapter.value) {
        const chapter_parsed = parseInt(newChapter.value);
        const chapterToUpdate = insertInOrder(
            user.value.chapter,
            chapter_parsed
        );

        router.post(
            "/profile/update-chapter",
            { chapter: chapterToUpdate },
            {
                onSuccess: () => {
                    user.value.chapter = chapterToUpdate;
                },
                onError: (err) => {
                    console.error("Failed to update chapter", err);
                },
            }
        );
    }
}

function removeChapter(numberToRemove) {
    const chapterToUpdate = removeFromDataString(
        user.value.chapter,
        numberToRemove
    );
    router.post(
        "/profile/update-chapter",
        { chapter: chapterToUpdate },
        {
            onSuccess: () => {
                user.value.chapter = chapterToUpdate;
            },
            onError: (err) => {
                console.error("Failed to update chapter", err);
            },
        }
    );
}

function backupdata() {
    backupForm.value.submit();
}
</script>
