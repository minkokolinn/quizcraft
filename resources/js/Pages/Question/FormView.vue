<template>
    <Head title=" | Question Entry"></Head>

    <AlertToast ref="alertToastRef" />

    <h5 class="card-title">
        <Link :href="`/question?type=${type.id}`" class="px-2"
            ><i class="bi bi-caret-left-fill" style="font-size: 16px"></i
        ></Link>
        {{ type.name }} Entry Form
    </h5>

    <form class="row" @submit.prevent="submit">
        <div class="col-md-4">
            <label for="noInput" class="form-label"
                ><b>Number</b>
                <small class="form-text"> ( Enter the number ) </small></label
            >
            <input
                v-model="form.no"
                type="number"
                class="form-control"
                id="noInput"
                autocomplete="off"
                min="1"
                required
            />
            <InputError :message="form.errors.no" />
            <div v-if="lastNo" class="text-success mt-1">
                Last Number for this type is {{ lastNo }}
            </div>
            <div v-if="noChecked && noExists" class="text-danger mt-1">
                {{ form.no }} is not available
            </div>
            <div v-if="noChecked && !noExists" class="text-success mt-1">
                {{ form.no }} is available
            </div>
        </div>
        <div class="col-md-4">
            <label for="gradeInput" class="form-label"><b>Grade</b></label>
            <select
                v-model="form.grade"
                class="form-select"
                id="gradeInput"
                aria-label="Default select example"
                required
            >
                <option disabled value="">Open this select menu</option>
                <option
                    v-for="(item, index) in user.grade.split(',')"
                    :key="index"
                    :value="item"
                >
                    Grade {{ item }}
                </option>
            </select>
            <InputError :message="form.errors.grade" />
        </div>
        <div class="col-md-4">
            <label for="chapterInput" class="form-label"><b>Chapter</b></label>
            <select
                v-model="form.chapter"
                class="form-select"
                id="chapterInput"
                aria-label="Default select example"
                required
            >
                <option disabled value="">Open this select menu</option>
                <option
                    v-for="(item, index) in user.chapter.split(',')"
                    :key="index"
                    :value="item"
                >
                    Chapter {{ item }}
                </option>
            </select>
            <InputError :message="form.errors.chapter" />
        </div>
        <div class="col-12 my-3">
            <label for="bodyInput" class="form-label"
                ><b>Body</b>
                <small class="form-text"> ( Enter the question ) </small></label
            >
            <textarea
                v-model="form.body"
                class="editor form-control"
                id="bodyInput"
            >
            </textarea>
            <InputError :message="form.errors.body" />
        </div>
        <div class="col-12 mb-2">
            <label for="imageInput" class="form-label"><b>Image</b></label>
            <input
                name="form.image"
                type="file"
                class="form-control border-dark-subtle"
                id="imageInput"
                @input="uploadImage"
            />
            <InputError :message="form.errors.image" />
        </div>
        <div class="d-flex justify-content-end">
            <button
                type="submit"
                class="btn btn-primary"
                :disabled="form.processing"
            >
                <!-- {{ type ? "Update" : "Save" }} -->
                Save
            </button>
        </div>
    </form>
</template>
<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";
import BlankLayout from "../../Layouts/BlankLayout.vue";
import AlertToast from "../Components/AlertToast.vue";
import InputError from "../Components/InputError.vue";
import { debounce } from "lodash";
import axios from "axios";

defineOptions({
    layout: BlankLayout,
});
const props = defineProps({
    type: Object,
    user: Object,
});

const form = useForm({
    no: null,
    body: null,
    image: null,
    grade: "",
    chapter: "",
    type_id: props.type.id,
});

const alertToastRef = ref(null);

const noExists = ref(false);
const lastNo = ref(null);
const noChecked = ref(false);
watch(
    () => form.no,
    debounce(async (no) => {
        if (!no || no < 0) {
            noExists.value = false;
            noChecked.value = false;
            return;
        }
        try {
            const response = await axios.get(
                "/question/create/checkNumberExist",
                {
                    params: {
                        type: props.type.id,
                        noToCheck: no,
                    },
                }
            );
            noExists.value = response.data.noExists;
            noChecked.value = true;
        } catch (error) {
            console.error("Validation error", error);
        }
    }, 700)
);

onMounted(async () => {
    try {
        const response = await axios.get("/question/create/getLastNumber", {
            params: {
                type: props.type.id,
            },
        });
        lastNo.value = response.data.lastNo;
        form.no = lastNo.value+1;
    } catch (error) {
        console.error("Failed to fetch last number", error);
    }
});

// Upload Image
const uploadImage = (e) => {
    form.image = e.target.files[0];
};
const submit = () => {
    if (noExists.value) {
        alertToastRef.value.addToast(
            "Number is already occupied! Change the number!",
            "danger"
        );
    } else {
        form.post("/question/create", {
            onSuccess: async () => {
                form.reset();
                alertToastRef.value.addToast(
                    "Question successfully created..",
                    "success"
                );
                try {
                    const response = await axios.get(
                        "/question/create/getLastNumber",
                        {
                            params: {
                                type: props.type.id,
                            },
                        }
                    );
                    lastNo.value = response.data.lastNo;
                    form.no = lastNo.value+1;
                } catch (error) {
                    console.error("Failed to fetch last number", error);
                }
            },
            onError: (errors) => {
                if (errors.error) {
                    alertToastRef.value.addToast(errors.error, "danger");
                } else {
                    alertToastRef.value.addToast(
                        "Something went wrong! Failed to create question!",
                        "danger"
                    );
                }
            },
        });
    }
};
</script>
