<template>
    <Modal ref="user_form" :id="'user_form'" :size="'large'">
        <template #modal_title>
            <span>{{ title_text }}</span>
        </template>

        <form>
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
                            type="button"
                        >
                            Upload Image
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
                            'is-invalid':
                                formValidation.hasError('profile_image'),
                        }"
                    />
                    <span
                        :class="{
                            'is-invalid':
                                formValidation.hasError('profile_image'),
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
                        autocomplete="off"
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
                        autocomplete="off"
                        placeholder="Enter confirm password"
                        :errors="formValidation.errors"
                    ></Field>
                </div>
            </div>
        </form>

        <div class="row mt-4">
            <div class="col">
                <h5 class="text-center">Permissions</h5>
            </div>
        </div>

        <div class="row">
            <template
                v-for="(permissions, category) in grouped_permissions"
                :key="`permission_group_${category}`"
            >
                <div class="col-12">
                    <div
                        class="bg-light p-2 text-center mt-3"
                        style="border: 1px solid black; border-radius: 5px"
                    >
                        <strong>{{ category }}</strong>
                    </div>
                    <div class="row gy-2 mt-2">
                        <div
                            v-for="(permission, index) in permissions"
                            :key="`permission_${index}`"
                            class="col-12 col-lg-6 col-md-6"
                        >
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    :value="permission.name"
                                    v-model="permissions_array"
                                    :id="`${category}_permission_${index}`"
                                />
                                <label
                                    class="form-check-label"
                                    :for="`${category}_permission_${index}`"
                                >
                                    {{ permission.display_name }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <template #modal_footer>
            <button
                class="btn btn-success btn-sm"
                type="button"
                @click="handleSubmit"
            >
                {{ button_text }}
            </button>
        </template>
    </Modal>
</template>

<script setup>
import { ref, reactive } from "vue";
import Modal from "../../../components/Modal.vue";
import {
    FormValidation,
    withParamsAndMessage,
    withParams,
} from "../../../helpers/Validation";
import { resetObjectKeys } from "../../../helpers/utils";
import Field from "../../../helpers/Field.vue";
import axios from "axios";
import { userRoutes } from "../../../routes/UserRoutes";
import { toastAlert } from "../../../helpers/alert";

let user_form = ref(null);
let my_profile = ref("");
let title_text = ref("");
let button_text = ref("");

const emits = defineEmits(["reload"]);

const props = defineProps({
    grouped_permissions: {
        type: Object,
        required: true,
    },
});

let permissions_array = ref([]);

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
    user_form.value.open();

    title_text.value = user ? `Update user : ${user.username}` : "Create User";
    button_text.value = user ? "Update" : "Submit";

    if (user) {
        Object.assign(fields, user);
        fields.profile_path = `/storage/${user.profile_image.file_path}`;

        user.permissions.forEach((permission) => {
            permissions_array.value.push(permission.name);
        });

        formValidation.addFields(fields, {
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
        });
    } else {
        formValidation.addFields(fields, {
            password: {
                required: "Password field is required.",
            },
            confirm_password: {
                required: "Confirm password field is required.",
                same: withParamsAndMessage(
                    withParams([fields, "password"]),
                    "Confirm password dose not match."
                ),
            },
            profile_image: {
                required: "Profile picture is required.",
            },
        });
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
    title_text.value = "";
    button_text.value = "";
    permissions_array.value = [];
    formValidation.reset();
    formValidation.removeFields("password");
    formValidation.removeFields("confirm_password");
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

    form_data.set("id", fields.id);
    form_data.set("username", fields.username);
    form_data.set("first_name", fields.first_name);
    form_data.set("last_name", fields.last_name);
    form_data.set("email", fields.email);
    form_data.set("password", fields.password);
    form_data.set("confirm_password", fields.confirm_password);
    form_data.set("permissions", permissions_array.value);

    let settings = { headers: { "content-type": "multipart/form-data" } };

    if (formValidation.isValid()) {
        axios
            .post(userRoutes.createOrUpdate(fields.id), form_data, settings)
            .then((response) => {
                user_form.value.close();
                emits("reload", response.data.user_details);
                toastAlert({ title: response.data.message });
                clearFormData();
            })
            .catch(function (error) {
                if (error.response.status === 422) {
                    formValidation.setServerSideErrors(
                        error.response.data.errors
                    );
                }
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
    })
);
</script>
