<template>
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a
                class="sidebar-brand"
                :href="`${$page.props.url}`"
                style="text-decoration: none"
            >
                <img
                    :src="`${$page.props.url}/images/favicon.png`"
                    alt="logo"
                    style="height: 30px; width: auto"
                />
                <span class="align-middle ms-2">P M S</span>
            </a>

            <ul class="sidebar-nav">
                <template v-for="(menu, index) in menuItems">
                    <li
                        class="sidebar-item"
                        v-if="menu.has_permission"
                        :class="
                            current_url == `${$page.props.url}/${menu.url}`
                                ? 'active'
                                : ''
                        "
                        :key="`menu_item_${index}`"
                    >
                        <a
                            :href="`${$page.props.url}/${menu.url}`"
                            class="sidebar-link"
                        >
                            <i class="align-middle" :class="menu.icon"></i>
                            <span class="align-middle">{{ menu.name }}</span>
                        </a>
                    </li>
                </template>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { onMounted, ref, reactive } from "vue";

let current_url = ref(null);

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});

onMounted(() => {
    current_url.value = window.location.href;
});

let menuItems = reactive([
    {
        name: "Dashboard",
        icon: "fa fa-home",
        url: "",
        has_permission: true,
    },
    {
        name: "Projects",
        icon: "fa fa-list",
        url: "projects/index",
        has_permission: hasPermission("view_project"),
    },
    {
        name: "Users",
        icon: "fa fa-users",
        url: "users/index",
        has_permission: hasPermission("view_user"),
    },
]);

function hasPermission(permission_name) {
    let permission_obj = props.auth.user.permissions.find(
        (permission) => permission.name == permission_name
    );

    return permission_obj ? true : false;
}
</script>
