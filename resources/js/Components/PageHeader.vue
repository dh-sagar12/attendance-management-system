<script setup>
const props =  defineProps({
    title: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <h1 class="text-2xl font-medium text-purple-800"> {{ props.title }}</h1>
</template>
