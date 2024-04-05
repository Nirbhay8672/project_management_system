<template>
    <main-page>
        <h1 class="h3 mb-3">
            <strong>Welcome</strong> {{ auth.user.username }}
        </h1>
    </main-page>
</template>

<script setup>
const props = defineProps({
    auth: {
        type: Object,
        required: true,
    },
});
</script>
