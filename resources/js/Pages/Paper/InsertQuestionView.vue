<template>
    <Head title=" | Insert Question to Paper"></Head>

    <AlertToast ref="alertToastRef" />

    <!-- Add Section Modal -->
    <div
        class="modal fade"
        tabindex="-1"
        data-bs-backdrop="static"
        id="addSectionModalRef"
        ref="addSectionModalRef"
    >
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionModalLabel">
                        Add New Section
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="closeAddSectionModal"
                    ></button>
                </div>
                <div class="modal-body">
                    <label for="" class="mb-2">
                        <b>Question Type</b>
                    </label>
                    <select
                        name=""
                        id=""
                        class="form-select"
                        v-model="sectionTypeModal"
                    >
                        <option value="" disabled selected>None</option>
                        <option
                            v-for="type in $page.props.types"
                            :key="type.id"
                            :value="type"
                        >
                            {{ type.name }}
                        </option>
                    </select>
                    <div class="mt-4 text-end">
                        <button
                            class="btn btn-primary"
                            @click="btnAddSection"
                            :disabled="!sectionTypeModal"
                        >
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Insert Question Modal -->
    <div
        class="modal fade"
        tabindex="-1"
        data-bs-backdrop="static"
        id="insertQuestionModalRef"
        ref="insertQuestionModalRef"
    >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionModalLabel">
                        Insert Question
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="closeInsertQuestionModal"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="row border-bottom pb-3">
                        <div class="col-6 col-lg-3">
                            <select v-model="filterType" class="form-select">
                                <option value="">Select Type</option>
                                <option
                                    v-for="type in $page.props.types"
                                    :key="type.id"
                                    :value="type.id"
                                >
                                    {{ type.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-6 col-lg-3">
                            <select v-model="filterGrade" class="form-select">
                                <option value="">Select Grade</option>
                                <option
                                    v-for="(grade, index) in grades"
                                    :key="index"
                                    :value="grade"
                                >
                                    Grade - {{ grade }}
                                </option>
                            </select>
                        </div>
                        <div class="col-6 col-lg-3">
                            <select v-model="filterChapter" class="form-select">
                                <option value="">Select Chapter</option>
                                <option
                                    v-for="(chapter, index) in chapters"
                                    :key="index"
                                    :value="chapter"
                                >
                                    Chapter - {{ chapter }}
                                </option>
                            </select>
                        </div>
                        <div class="col-6 col-lg-3">
                            <input
                                v-model="filterSearch"
                                type="text"
                                class="form-control"
                                placeholder="Search"
                            />
                        </div>
                    </div>

                    <div class="list-group my-3 fetch-question-list">
                        <div
                            v-for="question in fetchQuestionsData"
                            :key="question.id"
                        >
                            <div
                                v-if="
                                    sections &&
                                    sections.find(
                                        (section) =>
                                            section.section_type.id ===
                                            question.type_id
                                    )
                                "
                                class="list-group-item list-group-item-action"
                                aria-current="true"
                                @dblclick="
                                    !isQuestionSelected(question.id) &&
                                        dblClickInsertQuestion(question)
                                "
                                :class="{
                                    'disabled-question': isQuestionSelected(
                                        question.id
                                    ),
                                }"
                            >
                                <div
                                    style="color: black"
                                    :class="{
                                        'question-selected': isQuestionSelected(
                                            question.id
                                        ),
                                    }"
                                >
                                    {{ question.no }} . <i v-if="question.image" class="bi bi-file-image text-primary"></i> {{ question.body }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-1 mt-2">
        <div class="stepper-wrapper">
            <div class="stepper-item completed">
                <Link href="/">
                    <div class="step-counter">
                        <i class="bi bi-house-door"></i>
                    </div>
                </Link>
                <div class="step-name">Back</div>
            </div>
            <div class="stepper-item completed">
                <Link
                    href="/paper/create/step/1"
                    method="post"
                    :data="{ info, sections }"
                    style="background: transparent; border: none"
                >
                    <div class="step-counter" id="btn1">1</div>
                </Link>
                <div class="step-name">Configure Paper</div>
            </div>
            <div class="stepper-item active">
                <Link
                    href="/paper/create/step/2"
                    method="post"
                    :data="{ info, sections }"
                    style="background: transparent; border: none"
                >
                    <div class="step-counter" id="btn2">2</div>
                </Link>
                <div class="step-name">Insert Questions</div>
            </div>
            <div class="stepper-item">
                <Link
                    href="/paper/create/step/2"
                    method="post"
                    :data="{ info, sections }"
                    style="background: transparent; border: none"
                >
                    <div class="step-counter" id="btn2">3</div>
                </Link>
                <div class="step-name">Preview</div>
            </div>
        </div>
    </div>
    <hr />
    <!-- Step 2 - Insert Questions -->
    <div class="mb-4 row">
        <div class="col-1 col-md-4"></div>
        <h4 class="col-6 col-md-4 fw-bold text-center">Insert Questions</h4>
        <div class="col-5 col-md-4 text-end">
            <button
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addSectionModalRef"
            >
                <i class="bi bi-text-paragraph"></i>
                <span class="d-none d-sm-inline">Add Section</span>
            </button>
            <button
                v-if="sections && sections.length > 0"
                class="btn btn-primary ms-2"
                data-bs-toggle="modal"
                data-bs-target="#insertQuestionModalRef"
            >
                <i class="bi bi-database-add"></i>
                <span class="d-none d-sm-inline"> Add Question</span>
            </button>
        </div>
    </div>
    <div class="m-1">
        <div class="text-center pt-3" v-if="info">
            <h5
                class="fw-bold"
                v-for="(header, index) in info.headers"
                :key="index"
            >
                {{ header }}
            </h5>
        </div>
        <hr />
        <div class="row mb-3" v-if="info">
            <h6 class="col text-start">Grade - {{ info.grade }}</h6>
            <h6 class="col text-center">{{ info.subject }}</h6>
            <h6 class="col text-end">Time Allowed : {{ info.timeAllowed }}</h6>
        </div>
        <div id="paperContent" v-if="sections">
            <div
                v-for="(section, sectionIndex) in sections"
                :key="sectionIndex"
                class="mb-5"
            >
                <h5>
                    {{ sectionIndex + 1 }} . {{ section.section_type.header }}
                    <span
                        class="text-danger fw-bold me-3 float-start"
                        style="cursor: pointer"
                        @click="removeSection(sectionIndex)"
                        title="Remove this section"
                    >
                        <i class="bi bi-trash-fill"></i>
                    </span>
                </h5>

                <ul class="list-group">
                    <li
                        v-for="(
                            question, questionIndex
                        ) in section.section_questions"
                        :key="question.id"
                        class="list-group-item"
                        draggable="true"
                        @dragstart="
                            handleDragStart(sectionIndex, questionIndex)
                        "
                        @dragover.prevent
                        @drop="handleDrop(sectionIndex, questionIndex)"
                    >
                        {{ questionIndex + 1 }} . {{ question.body }}
                        <small class="text-muted float-end"
                            >
                            <span v-if="question.image"><i class="bi bi-file-image text-primary"></i> | </span>
                            Chaper: {{ question.chapter }} | Grade:
                            {{ question.grade }}</small
                        >
                        <span
                            class="text-danger fw-bold me-3 float-start"
                            style="cursor: pointer"
                            @click="removeQuestion(sectionIndex, questionIndex)"
                            title="Remove this question"
                        >
                            <i class="bi bi-x-circle"></i>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="text-end" v-if="sections && info">
            <Link
                href="/paper/create/step/3"
                method="post"
                :data="{ info, sections }"
                class="btn btn-primary"
                :class="{
                    disabled: sections.length == 0,
                }"
                @click.prevent="sections.length == 0"
            >
                Show Preview
            </Link>
        </div>
    </div>
</template>
<script setup>
import axios from "axios";
import { onMounted, ref, watch } from "vue";
import BlankLayout from "../../Layouts/BlankLayout.vue";
import AlertToast from "../Components/AlertToast.vue";
import { debounce } from "lodash";

defineOptions({
    layout: BlankLayout,
});

const props = defineProps({
    user: Object,
    info: Object,
    sections: Array,
});
const info = props.info;
const grades = props.user.grade.split(",");
const chapters = props.user.chapter.split(",");

// For Step 2
const sections = ref(props.sections ?? []);

console.log("step 2");
console.log(info);
console.log(sections.value);

// Drag state
let draggedItem = null;

function handleDragStart(sectionIndex, questionIndex) {
    draggedItem = { sectionIndex, questionIndex };
}

function handleDrop(sectionIndex, questionIndex) {
    if (!draggedItem) return;

    // Get dragged question
    const draggedQuestion =
        sections.value[draggedItem.sectionIndex].section_questions[
            draggedItem.questionIndex
        ];

    // Remove from original place
    sections.value[draggedItem.sectionIndex].section_questions.splice(
        draggedItem.questionIndex,
        1
    );

    // Insert into new place
    sections.value[sectionIndex].section_questions.splice(
        questionIndex,
        0,
        draggedQuestion
    );

    // Clear drag state
    draggedItem = null;
}
// watch(sections.value, () => {
//     console.log(sections.value);
// });

// Add Section Modal and Add Section Stuffs
const addSectionModalRef = ref(null);
const closeAddSectionModal = () => {
    if (document.activeElement) {
        document.activeElement.blur();
    }
    const modal = bootstrap.Modal.getInstance(addSectionModalRef.value);
    modal.hide();
};
const sectionTypeModal = ref("");
const btnAddSection = () => {
    sections.value.push({
        section_type: sectionTypeModal.value,
        section_questions: [],
    });
};

// Add Question Modal and Add Question Stuffs
const insertQuestionModalRef = ref(null);
const filterType = ref("");
const filterGrade = ref("");
const filterChapter = ref("");
const filterSearch = ref("");
let fetchQuestionsData = ref(null);
const closeInsertQuestionModal = () => {
    if (document.activeElement) {
        document.activeElement.blur();
    }
    const modal = bootstrap.Modal.getInstance(insertQuestionModalRef.value);
    modal.hide();
};
onMounted(async () => {
    const response = await axios.get("/question/fetchQuestions", {
        params: {
            type: filterType.value,
            grade: filterGrade.value,
            chapter: filterChapter.value,
            search: filterSearch.value,
        },
    });
    fetchQuestionsData.value = response.data.questions;
});
const fetchQuestions = async () => {
    const response = await axios.get("/question/fetchQuestions", {
        params: {
            type: filterType.value,
            grade: filterGrade.value,
            chapter: filterChapter.value,
            search: filterSearch.value,
        },
    });
    fetchQuestionsData.value = response.data.questions;
};
watch([filterType, filterGrade, filterChapter], () => {
    fetchQuestions();
});
watch(
    filterSearch,
    debounce((q) => {
        fetchQuestions();
    }, 500)
);
function isQuestionSelected(questionId) {
    return sections.value.some((section) =>
        section.section_questions.find((q) => q.id === questionId)
    );
}

const dblClickInsertQuestion = (question) => {
    const targetSection = sections.value.find(
        (section) => section.section_type.id === question.type_id
    );
    if (targetSection) {
        targetSection.section_questions.push(question);
    }
};

function removeQuestion(sectionIndex, questionIndex) {
    sections.value[sectionIndex].section_questions.splice(questionIndex, 1);
}

const removeSection = (sectionIndex) => {
    sections.value.splice(sectionIndex, 1);
};
</script>
<style scoped>
.stepper-wrapper {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    padding-bottom: 4px;
}
.stepper-item {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    font-size: 12px;
}

.stepper-item::before {
    position: absolute;
    content: "";
    border-bottom: 2px solid #ccc;
    width: 100%;
    top: 20px;
    left: -50%;
    z-index: 2;
}

.stepper-item::after {
    position: absolute;
    content: "";
    border-bottom: 2px solid #ccc;
    width: 100%;
    top: 20px;
    left: 50%;
    z-index: 2;
}

.stepper-item .step-counter {
    position: relative;
    z-index: 5;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #ccc;
    margin-bottom: 2px;
    font-size: 20px;
    cursor: pointer;
}

.stepper-item .step-counter:hover ~ .step-name {
    font-weight: bold;
}

.stepper-item.active {
    font-weight: bold;
}

.stepper-item.active .step-counter {
    background-color: #237afb;
    color: white;
}

.stepper-item.completed .step-counter {
    background-color: #237afb;
    color: white;
    font-weight: bold;
}

.stepper-item.completed::after {
    position: absolute;
    content: "";
    border-bottom: 2px solid #237afb;
    width: 100%;
    top: 20px;
    left: 50%;
    z-index: 3;
}

.stepper-item:first-child::before {
    content: none;
}
.stepper-item:last-child::after {
    content: none;
}
.list-group-item {
    cursor: grab;
}
.fetch-question-list {
    max-height: 600px;
    overflow-y: auto;
}
.question-selected {
    text-decoration: line-through;
}
.disabled-question {
    pointer-events: none;
    opacity: 0.6;
}
</style>
