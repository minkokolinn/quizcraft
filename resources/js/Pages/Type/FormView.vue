<template>
    <Head :title="` | ${type ? 'Edit' : 'Create'} Type`"></Head>

    <AlertToast ref="alertToastRef" />

    <h5 class="card-title">
        <Link href="/type" class="px-2"
            ><i class="bi bi-caret-left-fill" style="font-size: 16px"></i
        ></Link>
        Question Type {{ type ? "Edit" : "Entry" }} Form
    </h5>

    <form class="row" @submit.prevent="submit">
        <div class="col-md-6">
            <label for="nameInput" class="form-label"
                ><b>Name</b>
                <small class="form-text">
                    ( Enter the name of question type )
                </small></label
            >
            <input
                v-model="form.name"
                type="text"
                class="form-control"
                id="nameInput"
                autocomplete="off"
                required
            />
            <InputError :message="form.errors.name" />
        </div>
        <div class="col-md-6">
            <label for="markInput" class="form-label"
                ><b>Mark</b>
                <small class="form-text">
                    ( Enter the mark value for each question )
                </small></label
            >
            <input
                v-model="form.mark"
                type="number"
                class="form-control"
                id="markInput"
                autocomplete="off"
                required
            />
            <InputError :message="form.errors.mark" />
        </div>
        <div class="col-12 my-3">
            <label for="headerInput" class="form-label"
                ><b>Header</b>
                <small class="form-text">
                    ( Enter the title that will appear in the question paper )
                </small></label
            >
            <textarea
                v-model="form.header"
                class="editor form-control"
                id="headerInput"
            >
            </textarea>
            <InputError :message="form.errors.header" />
        </div>
        <div class="d-flex justify-content-end">
            <button
                type="submit"
                class="btn btn-primary"
                :disabled="form.processing"
            >
                {{ type ? "Update" : "Save" }}
            </button>
        </div>
    </form>
</template>
<script setup>
import BlankLayout from "../../Layouts/BlankLayout.vue";
import InputError from "../Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import AlertToast from "../Components/AlertToast.vue";
import { onMounted, ref } from "vue";

defineOptions({
    layout: BlankLayout,
});
const props = defineProps({
    type: Object,
});

const alertToastRef = ref(null);
const form = useForm({
    name: props.type?.name ?? null,
    mark: props.type?.mark ?? null,
    header: props.type?.header ?? null,
});

const submit = () => {
    if (props.type) {
        form.put(`/type/${props.type.id}/edit`, {
            onSuccess: () => {
                alertToastRef.value.addToast(
                    "Type successfully updated..",
                    "success"
                );
            },
            onError: (errors) => {
                if(errors.error){
                    alertToastRef.value.addToast(errors.error, "danger");
                }else{
                    alertToastRef.value.addToast("Something went wrong!", "danger");
                }
            },
        });
    } else {
        form.post("/type/create", {
            onSuccess: () => {
                form.reset();
                alertToastRef.value.addToast(
                    "Type successfully created..",
                    "success"
                );
            },
            onError: () => {
                alertToastRef.value.addToast("Something went wrong!", "danger");
            },
        });
    }
};
</script>
