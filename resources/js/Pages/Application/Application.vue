<template>
    <AuthenticatedLayout>
        <PageHeader title="Applications" />
        
        <div>
            <button class="border px-2 py-1 my-2 bg-purple-500 text-white text-semibold">Add New </button>
        </div>
        <div class="flex flex-wrap -mx-3 mb-5  ">
            <div class="w-full max-w-full px-3 mb-6  mx-auto">
                <div
                    class="relative max-h-[96vh] flex-[1_auto] flex flex-col break-words min-w-0 bg-clip-border rounded-[.95rem] bg-white m-5">
                    <div
                        class="relative flex flex-col min-w-0 break-words border border-dashed bg-clip-border rounded-2xl border-stone-200 bg-light/30">
                        <!-- card header -->
                        <div
                            class="px-9 pt-5 flex justify-between items-stretch flex-wrap min-h-[70px] pb-0 bg-transparent">
                            <h3
                                class="flex flex-col items-start justify-center m-2 ml-0 font-medium text-xl/tight text-dark">
                                <span class="mr-3 font-semibold text-dark">Projects Deliveries</span>
                                <span class="mt-1 font-medium text-secondary-dark text-lg/normal">
                                    {{ `${format(start_date, 'do')} ${format(start_date, 'LLLL')},
                                    ${format(start_date, 'yyyy')}` }} -
                                    {{ `${format(end_date, 'do')} ${format(end_date, 'LLLL')},
                                    ${format(end_date, 'yyyy')}` }}
                                </span>
                            </h3>


                            <div class="flex space-x-5">
                                <div class="w-[220px]">
                                    <VueDatePicker placeholder="Start Date" :enableTimePicker="false"
                                        v-model="start_date"></VueDatePicker>
                                </div>

                                <div class="w-[220px]">
                                    <VueDatePicker placeholder="End Date" :enableTimePicker="false" v-model="end_date">
                                    </VueDatePicker>
                                </div>
                            </div>

                            <div class="relative flex flex-wrap items-center my-2">
                                <a href="javascript:void(0)"
                                    class="inline-block text-[.925rem] font-medium leading-normal text-center align-middle cursor-pointer rounded-2xl transition-colors duration-150 ease-in-out text-light-inverse bg-light-dark border-light shadow-none border-0 py-2 px-5 hover:bg-secondary active:bg-light focus:bg-light">
                                    See other projects </a>
                            </div>
                        </div>
                        <!-- end card header -->
                        <!-- card body  -->
                        <div class="flex-auto block py-8 pt-6 px-9">
                            <div class="overflow-x-auto max-h-[65vh]">
                                <table class="w-full my-0  align-middle text-dark border-neutral-200">
                                    <thead class="align-bottom ">
                                        <tr class="font-semibold text-[0.95rem] text-secondary-dark ">
                                            <th class="px-3 py-2 border text-end min-w-[2px] "></th>
                                            <th class="px-3 py-2 border text-start ">Date</th>
                                            <th class="px-3 py-2 border  text-start ">Application Type</th>
                                            <th class="px-3 py-2 border  text-start ">Start Time</th>
                                            <th class="px-3 py-2 border  text-start ">End Time</th>
                                            <th class="px-3 py-2 border  text-start ">Description</th>
                                            <th class="px-3 py-2 border  text-start ">Is Approved</th>
                                            <th class="px-3 py-2 border  text-end ">Approved By </th>
                                            <th class="px-3 py-2 border  text-end ">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in timesheet_data"
                                            :class="item.checkin_time && !item.checkout_time ? '!bg-red-100' : ''">
                                            <td class="p-3 border text-start">
                                                <span class="font-semibold text-light-inverse text-md/normal"></span>
                                            </td>

                                            <td class="py-2 px-3 border text-start">
                                                <span class="font-semibold text-light-inverse text-md/normal">
                                                    {{ item.working_date }}
                                                </span>
                                            </td>

                                            <td class="py-2 px-3 border text-star" :class="getHolidayClass(item)">
                                                <span class="font-semibold text-light-inverse text-md/normal ">
                                                    {{ format(item.working_date, 'EEEE') }}
                                                </span>
                                            </td>

                                            <td class="py-2 px-3 border text-start">
                                                <span class="font-semibold text-light-inverse text-md/normal">
                                                    {{ item.day_type === 'HOLIDAY' ? 'Public Holiday' :
                                        isSaturday(item.working_date) || isSunday(item.working_date) ?
                                            "Holiday" : 'Weekday' }}
                                                </span>
                                            </td>

                                            <td class="py-2 px-3 border text-start">
                                                <span class="font-semibold text-light-inverse text-md/normal">
                                                    {{ item.checkin_time }}
                                                </span>
                                            </td>

                                            <td class="py-2 px-3 border text-start">
                                                <span class="font-semibold text-light-inverse text-md/normal">
                                                    {{ item.checkout_time }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-3 border text-start">
                                                <span class="font-semibold text-light-inverse text-md/normal">

                                                </span>
                                            </td>
                                            <td class="py-2 px-3 border text-start">
                                                <span class="font-semibold text-light-inverse text-md/normal">
                                                    {{ getTotalWorkingHour(item) }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-3 border text-start">
                                                <span class="font-semibold text-light-inverse text-md/normal">
                                                    {{ item.description ? item.description : item.remarks }}
                                                </span>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { differenceInMinutes, startOfMonth, endOfMonth, format, isSaturday, isSunday } from 'date-fns';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import PageHeader from '@/Components/PageHeader.vue';
import { onMounted, watch , ref} from 'vue';
import ApiHandler from "@/utils/apihandlers";




const start_date = ref(startOfMonth(new Date()));
const end_date = ref(endOfMonth(new Date()));
const http = new ApiHandler()
const timesheet_data = ref([]);

</script>