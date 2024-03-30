<template>
    <AuthenticatedLayout>
        <PageHeader title="Timesheet" />

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
                                            <th class="px-3 py-2 border  text-start ">Work Day</th>
                                            <th class="px-3 py-2 border  text-start ">Work Day Type</th>
                                            <th class="px-3 py-2 border  text-start ">Checkin Time</th>
                                            <th class="px-3 py-2 border  text-start ">Checkout Time</th>
                                            <th class="px-3 py-2 border  text-start ">Overtime</th>
                                            <th class="px-3 py-2 border  text-end ">Total W.H. </th>
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

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { ref } from 'vue';
import { differenceInMinutes, endOfMonth, format, isSaturday, isSunday } from 'date-fns';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { startOfMonth } from 'date-fns';
import { onMounted } from 'vue';
import ApiHandler from '@/utils/apihandlers';
import { watch } from 'vue';



const start_date = ref(startOfMonth(new Date()));
const end_date = ref(endOfMonth(new Date()));
const http = new ApiHandler()
const timesheet_data = ref([])


onMounted(() => {
    fetchData();
});


const fetchData = () => {
    http.get(`api/timesheet/?start_date=${format(start_date.value, 'yyy-MM-dd')}&end_date=${format(end_date.value, 'yyyy-MM-dd')}`)
        .then(response => {
            const result = response.data
            timesheet_data.value = result.data
            console.log(timesheet_data.value);
        }).catch(error => {
            console.log(error);
        })
}

watch([start_date], ()=>{
    fetchData();
})
watch([end_date], ()=>{
    fetchData();
})

const getHolidayClass = (item) => {
    if (isInvalidCheckin(item)) {
        return ''
    }

    if (item.day_type === 'HOLIDAY' || isSaturday(item.working_date) || isSunday(item.working_date))
        return 'bg-red-400 text-gray-50'
    else {
        return 'bg-green-50'
    }
};

const isInvalidCheckin = (item) => {
    return item.checkin_time && !item.checkout_time
};


const getTotalWorkingHour = (item) => {
    if (!item.checkin_time || !item.checkout_time) {
        return
    }
    const start_time = new Date(`${item.working_date} ${item.checkin_time}`)
    const end_time = new Date(`${item.working_date} ${item.checkout_time}`)
    const difference = (differenceInMinutes(end_time, start_time) / 60).toFixed(2);
    return `${difference} Hrs.`
}
</script>
