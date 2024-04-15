<template>
    <tr style="background-color: rgb(248 248 248)">
        <td style="min-width: 50px">{{ index + 1 }}</td>
        <td style="min-width: 100px">
            <img
                :src="`${$page.props.url}/storage/${
                    user.profile_image ? user.profile_image.file_path : ''
                }`"
                class="avatar img-fluid rounded me-1"
                alt="profile image"
            />
        </td>
        <td style="min-width: 200px">{{ user.username }}</td>
        <td style="min-width: 200px">
            {{ `${user.first_name} ${user.last_name}` }}
        </td>
        <td style="min-width: 300px">
            {{ user.email }}
        </td>
        <td style="min-width: 100px" class="text-center">
            <button
                class="btn btn-outline-primary btn-sm"
                @click="emits('openEditForm')"
                v-if="hasPermission('update_user')"
            >
                <i class="fa fa-pencil"></i>
            </button>
            <button
                class="btn btn-outline-danger btn-sm ms-3"
                @click="emits('deleteUser')"
                v-if="hasPermission('delete_user')"
            >
                <i class="fa fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script setup>
const props = defineProps({
    user: {
        type: Object,
        default: {},
        required: true,
    },
    index: {
        type: Number,
        default: 0,
        required: true,
    },
    auth: {
        type: Object,
        required: true,
    },
});

function hasPermission(permission_name) {
    let permission_obj = props.auth.user.permissions.find(
        (permission) => permission.name == permission_name
    );

    return permission_obj ? true : false;
}

const emits = defineEmits(["openEditForm", "deleteUser"]);
</script>
