<template>
    <div class="wrapper">
        <sidebar></sidebar>

        <div class="main">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
import Sidebar from "./components/Sidebar.vue";
</script>
