<template>
    <Head title=" | Configure Paper"></Head>

    <AlertToast ref="alertToastRef" />

    <div class="pt-1 mt-2">
        <div class="stepper-wrapper">
            <div class="stepper-item completed">
                <a href="/paper">
                    <div class="step-counter">
                        <i class="bi bi-house-door"></i>
                    </div>
                </a>
                <div class="step-name">Back</div>
            </div>
            <div class="stepper-item active" @click="step = 1">
                <div class="step-counter" id="btn1">1</div>
                <div class="step-name">Configure Paper</div>
            </div>
            <div class="stepper-item" @click="step = 2">
                <div class="step-counter" id="btn2">2</div>
                <div class="step-name">Insert Questions</div>
            </div>
            <div class="stepper-item">
                <a href="">
                    <div class="step-counter" id="btn3">3</div>
                </a>
                <div class="step-name">Preview</div>
            </div>
        </div>
    </div>
    <hr />

    <!-- Step 1 - Configure Paper -->
    <div v-if="step === 1" class="text-center mb-4">
        <h4 class="fw-bold">Configure Paper</h4>
    </div>
    <div
        v-if="step === 1"
        class="text-start text-md-end col-md-10 col-lg-7 mx-auto"
    >
        <div class="row mb-3">
            <label for="subjectTb" class="col-sm-2 col-form-label"
                ><b>Subject</b></label
            >
            <div class="col-sm-10 text-start">
                <input
                    v-model="subject"
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
                    v-model="grade"
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
                    v-model="timeAllowed"
                    type="text"
                    class="form-control border-dark-subtle"
                    id="timeAllowedTb"
                    value=""
                />
            </div>
        </div>
        <div class="row mb-3">
            <label for="total_mark" class="col-sm-2 col-form-label"
                ><b>Total Mark</b></label
            >
            <div class="col-sm-10 text-start">
                <input
                    v-model="totalMark"
                    type="number"
                    class="form-control border-dark-subtle"
                    id="total_mark"
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
                    v-for="(header, index) in headers"
                    :key="index"
                    class="input-group mb-2"
                >
                    <input
                        type="text"
                        class="form-control"
                        v-model="headers[index]"
                        :placeholder="`Header ${index + 1}`"
                    />
                    <button
                        class="btn"
                        :class="
                            index === headers.length - 1
                                ? 'btn-primary'
                                : 'btn-danger'
                        "
                        type="button"
                        @click="handleButtonClick(index)"
                    >
                        <i
                            :class="
                                index === headers.length - 1
                                    ? 'bi bi-plus-lg'
                                    : 'bi bi-dash-lg'
                            "
                        ></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-primary" @click="step = 2">
                Next
            </button>
        </div>
    </div>

    <!-- Step 2 - Insert Questions -->
    <div v-if="step === 2" class="text-center mb-4">
        <h4 class="fw-bold">Insert Questions</h4>
    </div>
    <div v-if="step === 2" class="m-3">
        <div class="text-center pt-3">
            <h5 class="fw-bold" v-for="(header,index) in headers" :key="index">{{ header }}</h5>
        </div>
        <hr />
        <div class="row">
            <h6 class="col text-start">Grade - {{ grade }}</h6>
            <h6 class="col text-center">{{ subject }}</h6>
            <h6 class="col text-end">Time Allowed : {{ timeAllowed }}</h6>
        </div>
        <div id="paperContent">
            
        </div>
    </div>
</template>
<script setup>
import { ref, watch } from "vue";
import BlankLayout from "../../Layouts/BlankLayout.vue";
import AlertToast from "../Components/AlertToast.vue";

defineOptions({
    layout: BlankLayout,
});
const props = defineProps({
    user: Object,
});

const grades = props.user.grade.split(",");
const chapters = props.user.chapter.split(",");
let step = ref(1);

// Header
const headers = ref([""]);
function handleButtonClick(index) {
    if (index === headers.value.length - 1) {
        headers.value.push("");
    } else {
        headers.value.splice(index, 1);
    }
}

const subject = ref(null);
const grade = ref("");
const timeAllowed = ref(null);
const totalMark = ref(0);

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
</style>
