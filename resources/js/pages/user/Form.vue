<template>
    <Modal ref="profile_form" :id="'profile_form'">
        <template #modal_title>
            <span>Update Profile</span>
        </template>

        <div class="row">
            <div class="text-center col-lg-12">
                <div class="card-img-actions d-inline-block">
                    <div
                        style="
                            position: center;
                            overflow: hidden;
                            border-radius: 50%;
                        "
                    >
                        <img
                            id="profile_image_file"
                            :src="
                                fields.profile_path
                                    ? fields.profile_path
                                    : '/images/user.png'
                            "
                            class="rounded"
                            style="width: 120px; height: 120px"
                        />
                    </div>
                    <button
                        class="btn btn-primary btn-sm mt-2"
                        @click="trigger"
                    >
                        Change Image
                    </button>
                </div>
                <input
                    type="file"
                    id="profile_image"
                    ref="my_profile"
                    @change="previewFiles"
                    class="form-control d-none"
                    accept="image/png, image/jpeg, image/jpg"
                    :class="{
                        'is-invalid': formValidation.hasError('profile_image'),
                    }"
                />
                <span
                    :class="{
                        'is-invalid': formValidation.hasError('profile_image'),
                    }"
                ></span>
                <div
                    class="invalid-feedback"
                    v-if="formValidation.hasError('profile_image')"
                >
                    <span>{{
                        formValidation.getError("profile_image")[0]
                    }}</span>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-6 mb-2">
                <Field
                    v-model="fields.username"
                    label="Username"
                    label-class="required"
                    type="text"
                    id="username"
                    field="username"
                    placeholder="Enter username"
                    :errors="formValidation.errors"
                ></Field>
            </div>
            <div class="col-lg-6 mb-2">
                <Field
                    v-model="fields.email"
                    label="Email"
                    label-class="required"
                    type="text"
                    id="email"
                    field="email"
                    placeholder="Enter email"
                    :errors="formValidation.errors"
                ></Field>
            </div>
            <div class="col-lg-6 mb-2">
                <Field
                    v-model="fields.first_name"
                    label="First Name"
                    label-class="required"
                    type="text"
                    id="first_name"
                    field="first_name"
                    placeholder="Enter first name"
                    :errors="formValidation.errors"
                ></Field>
            </div>
            <div class="col-lg-6 mb-2">
                <Field
                    v-model="fields.last_name"
                    label="Last Name"
                    label-class="required"
                    type="text"
                    id="last_name"
                    field="last_name"
                    placeholder="Enter last name"
                    :errors="formValidation.errors"
                ></Field>
            </div>
            <div class="col-lg-6 mb-2">
                <Field
                    v-model="fields.password"
                    label="Password"
                    type="password"
                    id="password"
                    field="password"
                    placeholder="Enter password"
                    :errors="formValidation.errors"
                ></Field>
            </div>
            <div class="col-lg-6 mb-2">
                <Field
                    v-model="fields.confirm_password"
                    label="Confirm Password"
                    type="text"
                    id="confirm_password"
                    field="confirm_password"
                    placeholder="Enter confirm password"
                    :errors="formValidation.errors"
                ></Field>
            </div>
        </div>

        <template #modal_footer>
            <button class="btn btn-success btn-sm" @click="handleSubmit">
                Update
            </button>
        </template>
    </Modal>
</template>

<script setup>
import { ref, reactive } from "vue";
import Modal from "../../components/Modal.vue";
import {
    FormValidation,
    withParamsAndMessage,
    withParams,
} from "../../helpers/Validation";
import { resetObjectKeys } from "../../helpers/utils";
import Field from "../../helpers/Field.vue";
import axios from "axios";
import { userRoutes } from "../../routes/UserRoutes";
import { toastAlert } from "../../helpers/alert";

let profile_form = ref(null);
let my_profile = ref("");

const emits = defineEmits(["reload"]);

let fields = reactive({
    id: "",
    profile_path: "",
    profile_image: "",
    username: "",
    email: "",
    first_name: "",
    last_name: "",
    password: "",
    confirm_password: "",
});

function openModal(user) {
    clearFormData();
    profile_form.value.open();

    if (user) {
        fields.id = user.id;
        fields.profile_path = user.profile_path;
        fields.profile_image = user.profile_path;
        fields.username = user.username;
        fields.email = user.email;
        fields.first_name = user.first_name;
        fields.last_name = user.last_name;
        fields.password = "";
        fields.confirm_password = "";
    }
}

function trigger() {
    my_profile.value.click();
}

function previewFiles(event) {
    fields.profile_image = event.target.files[0].name;

    var image = document.getElementById("profile_image_file");
    image.src = URL.createObjectURL(event.target.files[0]);
}

function clearFormData() {
    formValidation.reset();
    resetObjectKeys(fields);
}

function handleSubmit() {
    formValidation.validate();

    let form_data = new FormData();
    let profile_image = document.getElementById("profile_image");

    if (profile_image && profile_image.files.length > 0) {
        let file = profile_image.files[0];
        form_data.set("profile_image", file, file.name);
    }

    form_data.set("user_id", fields.id);
    form_data.set("username", fields.username);
    form_data.set("first_name", fields.first_name);
    form_data.set("last_name", fields.last_name);
    form_data.set("email", fields.email);
    form_data.set("password", fields.password);
    form_data.set("confirm_password", fields.confirm_password);

    let settings = { headers: { "content-type": "multipart/form-data" } };

    if (formValidation.isValid()) {
        axios
            .post(userRoutes.updateProfile(fields.id), form_data, settings)
            .then((response) => {
                profile_form.value.close();
                emits("reload", response.data.user_details);
                toastAlert({ title: response.data.message });
                clearFormData();
            });
    }
}

defineExpose({
    openModal,
});

let formValidation = reactive(
    new FormValidation(fields, {
        profile_image: {
            required: "Profile picture is required.",
        },
        username: {
            required: "Username field is required.",
        },
        email: {
            required: "Email field is required.",
        },
        first_name: {
            required: "First name field is required.",
        },
        last_name: {
            required: "Last name field is required.",
        },
        password: {
            requiredIf: withParamsAndMessage(
                withParams([fields, "confirm_password"]),
                "Password field is required."
            ),
        },
        confirm_password: {
            requiredIf: withParamsAndMessage(
                withParams([fields, "password"]),
                "Confirm password field is required."
            ),
            same: withParamsAndMessage(
                withParams([fields, "password"]),
                "Confirm password dose not match."
            ),
        },
    })
);
</script>
