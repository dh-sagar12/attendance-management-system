<template>
    <aside class="w-[300px] bg-white  border shadow-md  ">
        <ul class=" py-5">
            <Link href="/">
                <li class="text-lg p-3 my-2 w-full  space-x-2 font-semibold hover:bg-gray-300 ">
                    <i class="fa-solid fa-gauge"></i>
                    <span> Home </span>
                </li>
            </Link>

            <Link :href="'/timesheet'">

                <li class="text-lg p-3 my-2 w-full space-x-2 font-semibold hover:bg-gray-300">
                    <i class="fa-solid fa-users"></i>
                    <span>My Calendar</span>
                </li>
            </Link>



            <Link :href="'/application'">

                <li class="text-lg p-3 my-2 w-full space-x-2 font-semibold hover:bg-gray-300">
                    <i class="fa-solid fa-sheet-plastic"></i>
                    <span>Applications</span>
                </li>
            </Link>

            <a href="{{ route('marksheet.all') }}">

                <li class="text-lg p-3 my-2 w-full space-x-2 font-semibold hover:bg-gray-300">
                    <i class="fa-solid fa-sheet-plastic"></i>
                    <span>Messages</span>
                </li>
            </a>

        </ul>

    </aside>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';


</script>