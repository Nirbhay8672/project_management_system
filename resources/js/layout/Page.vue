<template>
    <custom-navbar />
    <main class="content">
        <div class="container-fluid p-0">
            <slot></slot>
        </div>
    </main>
    <custom-footer />
</template>

<script setup>
import CustomNavbar from "./components/CustomNavbar.vue";
import CustomFooter from "./components/CustomFooter.vue";
</script>
