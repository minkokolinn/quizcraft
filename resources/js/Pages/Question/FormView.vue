<template>
    <Head title=" | Question Entry"></Head>

    <AlertToast ref="alertToastRef" />

    <h5 class="card-title">
        <Link :href="`/question?type=${type.id}`" class="px-2"
            ><i class="bi bi-caret-left-fill" style="font-size: 16px"></i
        ></Link>
        {{ type.name }} {{ question ? "Edit" : "Entry" }} Form
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
            <div v-if="noChecked && noExists" class="text-success mt-1">
                {{ form.no }} already exists
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
        <div class="col-12 my-2">
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
        <div v-if="type.id == 3" class="row">
            <div class="col-md-6 col-lg-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="AInput">A.</span>
                    <input
                        v-model="form.A"
                        type="text"
                        class="form-control"
                        placeholder="Enter Option A"
                        autocomplete="off"
                        required
                    />
                </div>
                <InputError :message="form.errors.A" />
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="BInput">B.</span>
                    <input
                        v-model="form.B"
                        type="text"
                        class="form-control"
                        placeholder="Enter Option B"
                        autocomplete="off"
                        required
                    />
                </div>
                <InputError :message="form.errors.B" />
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="CInput">C.</span>
                    <input
                        v-model="form.C"
                        type="text"
                        class="form-control"
                        placeholder="Enter Option C"
                        autocomplete="off"
                        required
                    />
                </div>
                <InputError :message="form.errors.C" />
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="DInput">D.</span>
                    <input
                        v-model="form.D"
                        type="text"
                        class="form-control"
                        placeholder="Enter Option D"
                        autocomplete="off"
                        required
                    />
                </div>
                <InputError :message="form.errors.D" />
            </div>
        </div>
        <div v-if="!hasOldImage" class="col-12 mb-2">
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
        <div v-if="hasOldImage" class="col-12 mb-2">
            <label class="form-label"><b>Existing Image:</b></label>
            <img :src="`/storage/${question.image}`" class="img-fluid w-50" />
        </div>

        <div class="d-flex justify-content-end">
            <button
                type="submit"
                class="btn btn-primary"
                :disabled="form.processing"
            >
                {{ question ? "Update" : "Save" }}
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
    question: Object,
});

const form = useForm({
    no: props.question ? props.question.no : null,
    body: props.question ? props.question.body : null,
    image: null,
    grade: props.question ? props.question.grade : "",
    chapter: props.question ? props.question.chapter : "",
    type_id: props.type.id,
    A:
        props.question && props.question.type_id == 3
            ? props.question.options[0].content
            : null,
    B:
        props.question && props.question.type_id == 3
            ? props.question.options[1].content
            : null,
    C:
        props.question && props.question.type_id == 3
            ? props.question.options[2].content
            : null,
    D:
        props.question && props.question.type_id == 3
            ? props.question.options[3].content
            : null,
});
const hasOldImage = ref(!!props.question?.image);

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
    if (!props.question) {
        // only if no question to edit
        try {
            const response = await axios.get("/question/create/getLastNumber", {
                params: {
                    type: props.type.id,
                },
            });
            lastNo.value = response.data.lastNo;
            form.no = lastNo.value + 1;
        } catch (error) {
            console.error("Failed to fetch last number", error);
        }
    }else{
        try {
            const response = await axios.get("/question/create/getLastNumber", {
                params: {
                    type: props.type.id,
                },
            });
            lastNo.value = response.data.lastNo;
        } catch (error) {
            console.error("Failed to fetch last number", error);
        }
    }
});

// Upload Image
const uploadImage = (e) => {
    form.image = e.target.files[0];
};
const submit = () => {
    if (props.question) {
        form.post(`/question/${props.question.id}/edit`,{
            onSuccess:()=>{
                alertToastRef.value.addToast("Question successfully updated...", "success");
            },
            onError:(errors)=>{
                if (errors.error) {
                    alertToastRef.value.addToast(errors.error, "danger");
                } else {
                    alertToastRef.value.addToast(
                        "Something went wrong! Failed to edit question!",
                        "danger"
                    );
                }
            }
        });
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
                    form.no = lastNo.value + 1;
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
