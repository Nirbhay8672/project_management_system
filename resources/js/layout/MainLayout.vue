<template>
    <div class="wrapper">
        <custom-sidebar :auth="auth"></custom-sidebar>

        <div class="main">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import CustomSidebar from "./components/CustomSidebar.vue";

const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
    url: {
        type: String,
        required: true,
    },
});
</script>
