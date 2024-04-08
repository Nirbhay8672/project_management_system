<template>
    <inertia-head title="Dashboard" />
    <main-page>
        <h5><strong>Welcome</strong> {{ auth.user.username }}</h5>
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
