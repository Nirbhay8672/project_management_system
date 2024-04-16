<template>
    <Modal ref="user_view" :id="'user_view'" :size="'large'">
        <template #modal_title>
            <span>User Info : {{ title_text }}</span>
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
                                user
                                    ? `/storage/${user.profile_image.file_path}`
                                    : '/images/user.png'
                            "
                            class="rounded"
                            style="width: 120px; height: 120px"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p>{{ user ? user.username : "" }}</p>
                        <p>
                            {{ user ? user.first_name : "" }}
                            {{ user ? user.last_name : "" }}
                        </p>
                        <p class="text-primary">{{ user ? user.email : "" }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="user">
            <h5 class="text-center">Permissions</h5>
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
                    <div class="row gy-2 mt-1 p-2">
                        <div
                            v-for="(permission, index) in permissions"
                            :key="`permission_${index}`"
                            class="col-12 col-lg-6 col-md-6"
                        >
                            <i
                                :class="{
                                    'text-success': permission.has_permission,
                                    'text-danger': !permission.has_permission,
                                    'fa fa-check': permission.has_permission,
                                    'fa fa-times': !permission.has_permission,
                                }"
                            ></i>
                            <span class="ms-2">{{
                                permission.display_name
                            }}</span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </Modal>
</template>

<script setup>
import { ref, reactive } from "vue";
import Modal from "../../../components/Modal.vue";

let user_view = ref(null);
let title_text = ref("");

const props = defineProps({
    grouped_permissions: {
        type: Object,
        required: true,
    },
});

let user = ref(null);

function openModal(user_obj) {
    user_view.value.open();
    title_text.value = user_obj.username;
    user.value = user_obj;

    Object.entries(props.grouped_permissions).forEach((permission_category) => {
        permission_category[1].forEach((permission, index) => {
            permission["has_permission"] = hasPermission(permission.id);
        });
    });
}

function hasPermission(permission_id) {
    let obj = user.value.permissions.find((perm) => perm.id === permission_id);
    return obj ? true : false;
}

defineExpose({
    openModal,
});
</script>
