<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/PageHeader.vue'
import CheckInOut from '@/Components/Partials/CheckInOut.vue'
</script>

<template>
    <Head title="Attendance Management System-Dashboard" />

    <AuthenticatedLayout>
        <PageHeader title="Dashboard" />
        <CheckInOut />
    </AuthenticatedLayout>
</template>
