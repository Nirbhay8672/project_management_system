<template>
    <Modal ref="website_form" :id="'website_form'">
        <template #modal_title>
            <span>Add Website</span>
        </template>

        <div class="row mb-3">
            <div class="col-">
                <Field
                    v-model="fields.url"
                    label="Website"
                    label-class="required"
                    type="text"
                    id="url"
                    field="url"
                    placeholder="Enter website"
                    :errors="formValidation.errors"
                ></Field>
            </div>
        </div>

        <template #modal_footer>
            <button class="btn btn-success btn-sm" @click="handleSubmit">
                Find
            </button>
        </template>
    </Modal>
</template>

<script setup>
import { ref, reactive } from "vue";
import Modal from "../../../components/Modal.vue";
import { FormValidation } from "../../../helpers/Validation";
import Field from "../../../helpers/Field.vue";

let website_form = ref(null);

const emits = defineEmits(["reload"]);

let fields = reactive({
    url: "",
});

function openModal(user) {
    clearFormData();
    website_form.value.open();
}

function clearFormData() {
    formValidation.reset();
    fields.url = "";
}

function handleSubmit() {
    formValidation.validate();
    if (formValidation.isValid()) {
    }
}

defineExpose({
    openModal,
});

let formValidation = reactive(
    new FormValidation(fields, {
        url: {
            required: "The website field is required.",
        },
    })
);
</script>
