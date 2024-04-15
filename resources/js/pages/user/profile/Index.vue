<template>
    <inertia-head title="Profile" />
    <main-page>
        <div class="container-fluid p-0">
            <div class="mb-3">
                <h5 class="d-inline align-middle">Profile</h5>
            </div>
            <div class="row">
                <div class="col-md-4 col-xl-3">
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <div class="d-flex justify-content-between">
                                <div class="mr-auto">
                                    <h5 class="card-title mb-0">
                                        Profile Details
                                    </h5>
                                </div>
                                <div>
                                    <i
                                        class="fa fa-pencil fs-5 cursor-pointer"
                                        aria-hidden="true"
                                        @click="openForm()"
                                        v-if="hasPermission('update_profile')"
                                    ></i>
                                </div>
                            </div>
                            <img
                                :src="
                                    user_data.profile_path
                                        ? user_data.profile_path
                                        : '/images/user.png'
                                "
                                alt="profile image"
                                class="img-fluid rounded-circle mb-2 mt-3"
                                width="128"
                                height="128"
                            />
                            <h5 class="card-title mt-2">
                                {{ user_data.username }}
                            </h5>
                            <div class="text-muted mb-2">
                                <span>{{
                                    `${user_data.first_name} ${user_data.last_name}`
                                }}</span>
                                <p class="text-primary">
                                    {{ user_data.email }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <teleport to="body">
            <profile-form ref="profile_form" @reload="loadProfileData" />
        </teleport>
    </main-page>
</template>

<script setup>
import { ref } from "vue";
import profileForm from "./Form.vue";

let profile_form = ref(null);

const props = defineProps({
    user_details: {
        type: Object,
        required: true,
    },
    auth: {
        type: Object,
        required: true,
    },
});

let user_data = ref(props.user_details);

function loadProfileData(data) {
    user_data.value = data;
}

function openForm() {
    profile_form.value.openModal(user_data.value);
}

function hasPermission(permission_name) {
    let permission_obj = props.auth.user.permissions.find(
        (permission) => permission.name == permission_name
    );

    return permission_obj ? true : false;
}
</script>
