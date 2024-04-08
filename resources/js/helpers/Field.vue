<template>
    <slot name="label" v-if="!noLabel">
        <label :for="id" class="form-label" :class="labelClass">
            {{ label }}
        </label>
    </slot>

    <slot :hasError="hasError" :errorMessage="errorMessage">
        <slot name="input" :hasError="hasError">
            <input
                v-if="!noInput"
                v-bind="$attrs"
                v-bind:type="$attrs['type'] || 'text'"
                class="form-control form-control-solid"
                :value="modelValue"
                v-bind:id="$attrs['id'] || id"
                @input="$emit('update:modelValue', $event.target.value)"
                :class="{
                    'is-invalid': hasError,
                }"
            />
        </slot>

        <slot name="error" :errorMessage="errorMessage" :hasError="hasError">
            <div class="invalid-feedback" v-if="hasError">
                <span>{{ errors[field][0] }}</span>
            </div>
        </slot>
    </slot>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean, Object, Array],
    },
    label: {
        type: String,
    },
    labelClass: {
        type: String,
        default: "",
    },
    id: {
        type: String,
    },
    field: {
        type: String,
    },
    errors: {
        type: Object,
        default: () => {},
    },
    noLabel: {
        type: Boolean,
        default: false,
    },
    noInput: {
        type: Boolean,
        default: false,
    },
});
defineEmits(["update:modelValue"]);

const hasError = computed(() => {
    if (!props.field) {
        return false;
    }

    return props.errors.hasOwnProperty(props.field);
});

const errorMessage = computed(() => {
    if (hasError.value) {
        return props.errors[props.field][0];
    }

    return "";
});
</script>

<style scoped></style>
