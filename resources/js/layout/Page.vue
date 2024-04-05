<template>
    <navbar></navbar>
    <main class="content">
        <div class="container-fluid p-0">
            <slot></slot>
        </div>
    </main>
</template>

<script setup>
import Navbar from "./components/Navbar.vue";
</script>
