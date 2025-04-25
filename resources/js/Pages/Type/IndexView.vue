<template>
    <Head title=" | Type"></Head>

    <AlertToast ref="alertToastRef"/>

    <!-- Modal -->
    <div
        class="modal fade"
        tabindex="-1"
        data-bs-backdrop="static"
        ref="detailModalRef"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionModalLabel">
                        Type Details
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
                        <strong>Name</strong>
                        <br />
                        {{ selectedDetail?.name }}
                    </p>
                    <p>
                        <strong>Header</strong>
                        <br />
                        {{ selectedDetail?.header }}
                    </p>
                    <p>
                        <strong>Mark</strong>
                        <br />
                        {{ selectedDetail?.mark }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div v-if="selectedIndexes.length>0" class="badge bg-secondary text-white selected-badge p-2">
        Selected Row :
        <span id="countTxt" class="text-white fw-bold">{{ selectedIndexes.length }}</span>
    </div>

    <div class="d-flex justify-content-between">
        <h5 class="card-title">
            <Link href="/" class="px-2"
                ><i class="bi bi-caret-left-fill" style="font-size: 16px"></i
            ></Link>
            List of Type
        </h5>

        <div class="d-flex align-items-center gap-1">
            <Link href="/type/create" class="btn btn-info">
                <i class="bi bi-plus-circle-dotted me-1"></i> 
                <span class="d-none d-sm-inline">New</span>
            </Link>
            <button
                type="button"
                class="btn btn-warning"
                id="btnEdit"
                :disabled="selectedIndexes.length!=1"
                @click="editClick"
            >
                <i class="bi bi-pencil-square me-1"></i>
                <span class="d-none d-sm-inline">Edit</span>
            </button>
            <button
                type="button"
                class="btn btn-danger"
                id="btnDelete"
                :disabled="selectedIndexes.length==0"
                @click="deleteClick"
            >
                <i class="bi bi-trash3 me-1"></i>
                <span class="d-none d-sm-inline">Delete</span>
            </button>
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
                    <th>Name</th>
                    <th>Mark</th>
                    <th>Count</th>
                    <th>Header</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(type,index) in $page.props.types"
                    :key="type.id"
                    :class="{ selected: selectedIndexes.includes(type.id) }"
                >
                    <td>
                        <input
                            type="checkbox"
                            :value="type.id"
                            v-model="selectedIndexes"
                        />
                    </td>
                    <td @dblclick="showModal(type)">{{ index+1 }}</td>
                    <td @dblclick="showModal(type)">{{ type.name }}</td>
                    <td @dblclick="showModal(type)">{{ type.mark }}</td>
                    <td @dblclick="showModal(type)">
                        {{ type.questions_count }}
                    </td>
                    <td
                        @dblclick="showModal(type)"
                        v-text="limitWithMore(type.header, 150)"
                    ></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script setup>
import { router } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";
import BlankLayout from "../../Layouts/BlankLayout.vue";
import AlertToast from "../Components/AlertToast.vue";

defineOptions({
    layout: BlankLayout,
});
const alertToastRef = ref(null);

const selectedIndexes = ref([]);
const selectedDetail = ref(null);
const detailModalRef = ref(null);

watch(selectedIndexes, () => {
    console.log(selectedIndexes.value);
});

const toggleSelect = (index) => {
    const i = selectedIndexes.value.indexOf(index);
    if (i !== -1) {
        selectedIndexes.value.splice(i, 1); // Deselect
    } else {
        selectedIndexes.value.push(index); // Select
    }
};

const showModal = (type) => {
    selectedDetail.value = type;
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

const deleteClick=()=>{
    if(selectedIndexes.value.length>0){
        if(confirm("Are you sure to delete?")){
            router.delete("/type/delete",{
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
            })
        }
    }else{
        alertToastRef.value.addToast("Something went wrong!","danger");
    }
}

const editClick = ()=>{
    if(selectedIndexes.value.length==1){
        router.get(`/type/${selectedIndexes.value[0]}/edit`);
    }else{
        alertToastRef.value.addToast("Error : choose only one row to edit!","danger");
    }
}

</script>
<style scoped>
tr.selected td {
    background-color: #cfe2ff !important;
    color: #000 !important;
}

.table-responsive {
    max-height: 700px;
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
