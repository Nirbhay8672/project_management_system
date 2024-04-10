<template>
    <tr style="background-color: rgb(248 248 248)">
        <td>
            <img
                :src="`${$page.props.url}/storage/${
                    user.profile_image ? user.profile_image.file_path : ''
                }`"
                class="avatar img-fluid rounded me-1"
                alt="profile image"
            />
        </td>
        <td style="min-width: 50px">{{ index + 1 }}</td>
        <td style="min-width: 50px">{{ user.username }}</td>
        <td style="min-width: 50px">
            {{ `${user.first_name} ${user.last_name}` }}
        </td>
        <td style="min-width: 50px">
            {{ user.email }}
        </td>
        <td style="min-width: 50px" class="text-center">
            <button
                class="btn btn-outline-primary btn-sm"
                @click="emits('openEditForm')"
            >
                <i class="fa fa-pencil"></i>
            </button>
            <button class="btn btn-outline-danger btn-sm ms-3">
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
});

const emits = defineEmits(["openEditForm"]);
</script>
