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
                <template
                    v-for="(menu, index) in menuItems"
                    :key="`menu_item_${index}`"
                >
                    <li
                        class="sidebar-item"
                        :class="
                            current_url == `${$page.props.url}/${menu.url}`
                                ? 'active'
                                : ''
                        "
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
import { onMounted, ref, onUpdated, reactive } from "vue";

let current_url = ref(null);

onMounted(() => {
    current_url.value = window.location.href;
});

let menuItems = reactive([
    {
        name: "Dashboard",
        icon: "fa fa-home",
        url: "",
    },
    {
        name: "Projects",
        icon: "fa fa-list",
        url: "projects/index",
    },
    {
        name: "Users",
        icon: "fa fa-users",
        url: "users/index",
    },
]);
</script>
