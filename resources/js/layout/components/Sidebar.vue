<template>
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="/" style="text-decoration: none">
                <img
                    src="../../../../public/images/favicon.png"
                    alt="logo"
                    style="height: 30px; width: auto"
                />
                <span class="align-middle ms-2">P M S</span>
            </a>

            <ul class="sidebar-nav">
                <li
                    class="sidebar-item"
                    :class="
                        current_url == `${$page.props.url}/` ? 'active' : ''
                    "
                >
                    <a href="/" class="sidebar-link" id="dashboard">
                        <i class="align-middle" data-feather="home"></i>
                        <span class="align-middle">Dashboard</span>
                    </a>
                </li>

                <li
                    class="sidebar-item"
                    :class="
                        current_url == `${$page.props.url}/projects/index`
                            ? 'active'
                            : ''
                    "
                >
                    <a
                        href="/projects/index"
                        class="sidebar-link"
                        id="projects"
                    >
                        <i class="align-middle" data-feather="list"></i>
                        <span class="align-middle">Projects</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script setup>
import { onMounted, ref } from "vue";

onMounted(() => {
    current_url.value = window.location.href;
});

let current_url = ref(null);
</script>
