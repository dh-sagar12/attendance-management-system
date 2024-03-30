<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, router } from '@inertiajs/vue3';
import Sidebar from '@/Layouts/Sidebar.vue';
import { watch, ref } from 'vue';
import { onMounted } from 'vue';
import ApiHandler from '@/utils/apihandlers';
import { useStore } from '@/utils/store';

const store = useStore()
const is_authenticated = store.getters.isAuthenticated()

onMounted(() => {
    const http = new ApiHandler()
    http.get('/api/current-user').then(response => {
        store.mutations.login(response.data)
    }).catch(error => {
        router.get('/login')
        console.log(error);
    })
})

watch(store.state.isLoggedIn, (new_value, old_value) => {
    if (new_value === false) {
        router.replace('/login')
    }
});

</script>

<template>
    <nav class="bg-white border-b z-20 fixed border-gray-100 w-full">
        <!-- Primary Navigation Menu -->
        <div class=" mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('dashboard')">
                        <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                        </Link>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <!-- Settings Dropdown -->
                    <div class="ms-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <!-- {{ $page.props.auth.user.name }} -->
                                        Sagar Dhakal

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>
        </div>

    </nav>
    <div>
        <div class="absolute max-h-screen overflow-hidden w-full flex pt-[64px]">
            <Sidebar />
            <!-- Page Content -->
            <main class="my-4 px-6 py-4 mx-3  bg-white w-full">
                <slot />
            </main>
        </div>
    </div>
</template>
