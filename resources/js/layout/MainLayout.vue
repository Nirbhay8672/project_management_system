<template>
    <div class="wrapper">
        <custom-sidebar />

        <div class="main">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import CustomSidebar from "./components/CustomSidebar.vue";
</script>
