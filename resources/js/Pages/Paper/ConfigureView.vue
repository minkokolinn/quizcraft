<template>
    <Head title=" | Configure Paper"></Head>

    <AlertToast ref="alertToastRef" />

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
            <div class="stepper-item active">
                <Link href="/paper/create/step/1" method="post" :data="{ info,sections }" style="background: transparent; border: none;">
                    <div class="step-counter" id="btn1">1</div>
                </Link>
                <div class="step-name">Configure Paper</div>
            </div>
            <div class="stepper-item">
                <Link href="/paper/create/step/2" method="post" :data="{ info,sections }" style="background: transparent; border: none;">
                    <div class="step-counter" id="btn2">2</div>
                </Link>
                <div class="step-name">Insert Questions</div>
            </div>
            <div class="stepper-item">
                <Link href="/paper/create/step/2" method="post" :data="{ info,sections }" style="background: transparent; border: none;">
                    <div class="step-counter" id="btn2">3</div>
                </Link>
                <div class="step-name">Preview</div>
            </div>
        </div>
    </div>
    <hr />

    <!-- Step 1 - Configure Paper -->
    <div class="text-center mb-4">
        <h4 class="fw-bold">Configure Paper</h4>
    </div>
    <div class="text-start text-md-end col-md-10 col-lg-7 mx-auto">
        <div class="row mb-3">
            <label for="subjectTb" class="col-sm-2 col-form-label"
                ><b>Subject</b></label
            >
            <div class="col-sm-10 text-start">
                <input
                    v-model="info.subject"
                    type="text"
                    class="form-control border-dark-subtle"
                    id="subjectTb"
                    value=""
                />
            </div>
        </div>
        <div class="row mb-3">
            <label for="grade" class="col-sm-2 col-form-label"
                ><b>Grade</b></label
            >
            <div class="col-sm-10 text-start">
                <select
                    v-model="info.grade"
                    class="form-select border-dark-subtle"
                    id="grade"
                    aria-label="Default select example"
                >
                    <option value="">Open this select menu</option>
                    <option
                        v-for="(item, index) in grades"
                        :key="index"
                        :value="item"
                    >
                        Grade - {{ item }}
                    </option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <label for="timeAllowedTb" class="col-sm-2 col-form-label"
                ><b>Time Allowed</b></label
            >
            <div class="col-sm-10 text-start">
                <input
                    v-model="info.timeAllowed"
                    type="text"
                    class="form-control border-dark-subtle"
                    id="timeAllowedTb"
                    value=""
                />
            </div>
        </div>
        <div class="row mb-3">
            <label for="header" class="col-sm-2 col-form-label"
                ><b>Header</b></label
            >
            <div class="col-sm-10 text-start" id="inputContainer">
                <div
                    v-for="(header, index) in info.headers"
                    :key="index"
                    class="input-group mb-2"
                >
                    <input
                        type="text"
                        class="form-control"
                        v-model="info.headers[index]"
                        :placeholder="`Header ${index + 1}`"
                    />
                    <button
                        class="btn"
                        :class="
                            index === info.headers.length - 1
                                ? 'btn-primary'
                                : 'btn-danger'
                        "
                        type="button"
                        @click="handleButtonClick(index)"
                    >
                        <i
                            :class="
                                index === info.headers.length - 1
                                    ? 'bi bi-plus-lg'
                                    : 'bi bi-dash-lg'
                            "
                        ></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="text-end">
            <Link
                href="/paper/create/step/2"
                method="post"
                :data="{ info, sections }"
                class="btn btn-primary"
                :class="{
                    disabled:
                        !info.subject ||
                        !info.grade ||
                        !info.timeAllowed ||
                        info.headers[0] == '',
                }"
                @click.prevent="
                    !info.subject ||
                    !info.grade ||
                    !info.timeAllowed ||
                    info.headers[0] == ''
                        ? null
                        : undefined
                "
                >Next</Link
            >
        </div>
    </div>
</template>
<script setup>
import { router } from "@inertiajs/vue3";
import { onMounted, reactive, ref } from "vue";
import BlankLayout from "../../Layouts/BlankLayout.vue";
import AlertToast from "../Components/AlertToast.vue";

defineOptions({
    layout: BlankLayout,
});
const props = defineProps({
    user: Object,
    info: Object,
    sections: Array
});

// For Step 1
const grades = props.user.grade.split(",");
const chapters = props.user.chapter.split(",");
const alertToastRef = ref(null);

const info = ref(props.info ?? {
    subject: null,
    grade: "",
    timeAllowed: null,
    headers: [""],
});
const sections = ref(props.sections ?? []);

console.log('step 1');
console.log(info.value);
console.log(sections.value);

function handleButtonClick(index) {
    if (index === info.value.headers.length - 1) {
        info.value.headers.push("");
    } else {
        info.value.headers.splice(index, 1);
    }
}

onMounted(()=>{
    if(props.info){
        info.value = {...props.info}
    }
})
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
</style>
