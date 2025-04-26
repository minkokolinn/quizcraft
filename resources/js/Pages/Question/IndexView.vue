<template>
    <Head title=" | Question"></Head>

    <AlertToast ref="alertToastRef" />

    <!-- Modal -->
    <div
        class="modal fade"
        tabindex="-1"
        data-bs-backdrop="static"
        ref="detailModalRef"
    >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionModalLabel">
                        Question Details
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="closeModal"
                    ></button>
                </div>
                <div class="modal-body">
                    <p>
                        <strong>No - {{ selectedDetail?.no }}</strong>
                    </p>
                    <p>
                        <strong>Grade - {{ selectedDetail?.grade }}</strong>
                    </p>
                    <p>
                        <strong>Chapter - {{ selectedDetail?.chapter }}</strong>
                    </p>
                    <p>
                        <strong>Type</strong>
                        <br />
                        {{ selectedDetail?.type.name }}
                    </p>
                    <p>
                        <strong>Body</strong>
                        <br />
                        {{ selectedDetail?.body }}
                    </p>
                    <div
                        v-if="
                            selectedDetail?.type_id == 3 &&
                            selectedDetail?.options.length > 0
                        "
                        class="mb-2"
                    >
                        <span
                            v-for="option in selectedDetail.options"
                            :key="option.id"
                            class="me-3"
                            >({{ option.label }}) {{ option.content }} </span
                        >
                    </div>
                    <div>
                        <strong>Image</strong>
                        <br />
                        <div
                            v-if="selectedDetail?.image"
                            class="col-12 col-md-10 col-lg-6"
                        >
                            <img
                                :src="`/storage/${selectedDetail.image}`"
                                class="img-fluid"
                                alt=""
                            />
                        </div>
                        <span v-else>No Image</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        v-if="selectedIndexes.length > 0"
        class="badge bg-secondary text-white selected-badge p-2"
    >
        Selected Row :
        <span id="countTxt" class="text-white fw-bold">{{
            selectedIndexes.length
        }}</span>
    </div>

    <div class="d-flex justify-content-between">
        <h5 class="card-title">
            <Link href="/" class="px-2"
                ><i class="bi bi-caret-left-fill" style="font-size: 16px"></i
            ></Link>
            List of {{ type ? type.name : "All Questions" }}
        </h5>

        <div class="d-flex flex-column flex-sm-row align-items-center gap-1">
            <div class="d-flex gap-1">
                <Link
                    v-if="type"
                    :href="`/question/create?type=${type ? type.id : ''}`"
                    class="btn btn-info"
                >
                    <i class="bi bi-plus-circle-dotted me-1"></i>
                    <span class="d-none d-sm-inline">New</span>
                </Link>
                <button
                    v-if="type"
                    type="button"
                    class="btn btn-warning"
                    id="btnEdit"
                    :disabled="selectedIndexes.length != 1"
                    @click="editClick"
                >
                    <i class="bi bi-pencil-square me-1"></i>
                    <span class="d-none d-sm-inline">Edit</span>
                </button>
                <button
                    v-if="type"
                    type="button"
                    class="btn btn-danger"
                    id="btnDelete"
                    :disabled="selectedIndexes.length == 0"
                    @click="deleteClick"
                >
                    <i class="bi bi-trash3 me-1"></i>
                    <span class="d-none d-sm-inline">Delete</span>
                </button>
            </div>
            <div class="d-flex gap-1 flex-column flex-sm-row">
                <div class="dropdown d-inline">
                    <button
                        class="btn btn-outline-secondary dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        {{ grade ? "Grade - " + grade : "Filter By Grade" }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <Link
                                class="dropdown-item"
                                :href="`/question?${
                                    type ? '&type=' + type.id : ''
                                }${chapter ? '&chapter=' + chapter : ''}`"
                                >All</Link
                            >
                        </li>

                        <li
                            v-for="(grade, index) in user.grade.split(',')"
                            :key="index"
                        >
                            <Link
                                class="dropdown-item"
                                :href="`/question?grade=${grade}${
                                    type ? '&type=' + type.id : ''
                                }${chapter ? '&chapter=' + chapter : ''}`"
                            >
                                Grade - {{ grade }}</Link
                            >
                        </li>
                    </ul>
                </div>
                <div class="dropdown d-inline">
                    <button
                        class="btn btn-outline-secondary dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        {{
                            chapter
                                ? "Chapter - " + chapter
                                : "Filter By Chapter"
                        }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <Link
                                class="dropdown-item"
                                :href="`/question?${
                                    type ? '&type=' + type.id : ''
                                }${grade ? '&grade=' + grade : ''}`"
                                >All</Link
                            >
                        </li>
                        <li
                            v-for="(chapter, index) in user.chapter.split(',')"
                            :key="index"
                        >
                            <Link
                                class="dropdown-item"
                                :href="`/question?chapter=${chapter}${
                                    type ? '&type=' + type.id : ''
                                }${grade ? '&grade=' + grade : ''}`"
                            >
                                Chapter - {{ chapter }}</Link
                            >
                        </li>
                    </ul>
                </div>
            </div>
            <div class="search-bar">
                <input
                    type="text"
                    name="query"
                    placeholder="Search Question..."
                    class="form-control"
                    v-model="searchText"
                />
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table
            class="table table-hover table-bordered align-middle"
            id="questionTable"
        >
            <thead class="table-dark">
                <tr>
                    <th></th>
                    <th>No</th>
                    <th v-if="!type">Type</th>
                    <th>Grade</th>
                    <th>Chapter</th>
                    <th>Body</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="question in questions.data"
                    :key="question.id"
                    :class="{ selected: selectedIndexes.includes(question.id) }"
                >
                    <td>
                        <input
                            type="checkbox"
                            :value="question.id"
                            v-model="selectedIndexes"
                        />
                    </td>
                    <td @dblclick="showModal(question)">{{ question.no }}</td>
                    <td v-if="!type" @dblclick="showModal(question)">
                        {{ question.type.name }}
                    </td>
                    <td @dblclick="showModal(question)">
                        {{ question.grade }}
                    </td>
                    <td @dblclick="showModal(question)">
                        {{ question.chapter }}
                    </td>
                    <td @dblclick="showModal(question)">
                        {{ limitWithMore(question.body, 150) }}
                        <div
                            v-if="
                                question.type_id == 3 &&
                                question.options.length > 0
                            "
                        >
                            <span
                                v-for="option in question.options"
                                :key="option.id"
                                class="badge bg-dark me-2"
                                >({{ option.label }}) {{ option.content }}</span
                            >
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination Link -->

        <div class="d-flex justify-content-between align-items-center mt-3">
            <nav aria-label="Pagination">
                <ul class="pagination mb-0">
                    <li
                        v-for="(link, index) in questions.links"
                        :key="`${link.label}-${index}`"
                        :class="[
                            'page-item',
                            {
                                active: link.active,
                                disabled: !link.url,
                            },
                        ]"
                    >
                        <Link
                            :href="link.url || ''"
                            class="page-link"
                            :preserve-scroll="true"
                            :only="['questions']"
                            :disabled="!link.url"
                        >
                            <span v-html="link.label" />
                        </Link>
                    </li>
                </ul>
            </nav>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">
                    Showing {{ questions.from }} to {{ questions.to }} of
                    {{ questions.total }} results
                </span>
                <select
                    v-model="perPage"
                    class="form-select form-select-sm"
                    style="width: auto"
                >
                    <option :value="10">10 rows</option>
                    <option :value="20">20 rows</option>
                    <option :value="50">50 rows</option>
                    <option :value="100">100 rows</option>
                </select>
            </div>
        </div>
    </div>
</template>
<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";
import BlankLayout from "../../Layouts/BlankLayout.vue";
import AlertToast from "../Components/AlertToast.vue";
import { debounce } from "lodash";
defineOptions({
    layout: BlankLayout,
});
const props = defineProps({
    questions: Object,
    type: Object,
    user: Object,
    grade: String,
    chapter: String,
    search: String,
});

const alertToastRef = ref(null);
const detailModalRef = ref(null);

const selectedIndexes = ref([]);
const selectedDetail = ref(null);

const showModal = (question) => {
    selectedDetail.value = question;
    const modal = new bootstrap.Modal(detailModalRef.value);
    modal.show();
};
const closeModal = () => {
    if (document.activeElement) {
        document.activeElement.blur();
    }
    const modal = bootstrap.Modal.getInstance(detailModalRef.value);
    modal.hide();
};

const limitWithMore = (text, limit = 150) => {
    if (!text) return "";
    if (text.length <= limit) return text;
    const shortened = text.substring(0, limit);
    return `${shortened} ...`;
};

const page = usePage();
const perPage = ref(page.props.perPage || 10);
const currentUrl = window.location.pathname;
const currentQuery = { ...page.props };
watch(perPage, (value) => {
    const params = {
        perPage: value,
        page: 1,
    };
    if (props.type) params.type = props.type.id;
    if (props.grade) params.grade = props.grade;
    if (props.chapter) params.chapter = props.chapter;
    if (searchText.value) params.search = searchText.value;
    router.get(currentUrl, params, {
        preserveScroll: true,
        preserveState: true,
    });
});

// Search
const searchText = ref(props.search ?? "");
watch(
    searchText,
    debounce((q) => {
        const params = {
            search: q,
        };
        if (props.type) params.type = props.type.id;
        if (props.grade) params.grade = props.grade;
        if (props.chapter) params.chapter = props.chapter;
        router.get("/question", params, { preserveState: true });
    }, 500)
);

// selection for update and delete
watch(selectedIndexes, ()=>{
    console.log(selectedIndexes.value);
});

// go to edit click
const editClick = ()=>{
    if(selectedIndexes.value.length==1){
        router.get(`/question/${selectedIndexes.value[0]}/edit`);
    }else{
        alertToastRef.value.addToast("Error : choose only one row to edit!","danger");
    }
}

// delete question
const deleteClick = ()=>{
    if(selectedIndexes.value.length>0){
        if(confirm("Are you sure to delete?")){
            router.delete("/question/delete",{
                data:{
                    ids:selectedIndexes.value
                },
                onSuccess:()=>{
                    alertToastRef.value.addToast("Deleted Successfully...","success");
                    selectedIndexes.value = [];
                },
                onError:(errors)=>{
                    alertToastRef.value.addToast("Failed to delete!","danger");
                    if(errors.error){
                        alertToastRef.value.addToast(errors.error,"danger");
                    }
                }
            });
        }
    }else{
        alertToastRef.value.addToast("Something went wrong!","danger");
    }
}

</script>
<style scoped>
tr.selected td {
    background-color: #cfe2ff !important;
    color: #000 !important;
}

.table-responsive {
    /* max-height: 700px; */
    overflow-x: auto;
}

td {
    white-space: nowrap;
}

tr {
    cursor: pointer;
}

thead th {
    position: sticky;
    top: 0;
    background-color: #343a40;
    color: white;
    z-index: 1;
    border: none;
}

div.selected-badge {
    position: fixed;
    left: 45%;
    top: 100px;
    z-index: 100;
}
</style>
