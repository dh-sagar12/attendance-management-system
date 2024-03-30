<template>
    <div class="bg-purple-700 text-white px-4 py-6 my-3 rounded-md">
        <p class="text-center text-xl py-2">{{ `${format(currentTime, 'do')} ${format(currentTime, 'LLLL')},
            ${format(currentTime, 'yyyy')}` }} </p>
        <h1 class="text-center text-4xl py-2">{{ format(currentTime, 'HH:MM:ss') }} </h1>
        <h2 class="text-center text-2xl">Welcome Sagar Dhakal !</h2>
        <div class="text-center pt-8 ">
            <button @click="handleCheckActionBtnClick()"  class=" h-[150px] w-[150px] rounded-full check-btn "
                :class="getButtonClass()" role="button" id="check_button">{{button_text}}</button>
        </div>
    </div>
</template>

<script setup>
import ApiHandler from '@/utils/apihandlers';
import { format } from 'date-fns';
import { onMounted } from 'vue';
import { ref } from 'vue';
const currentTime = ref(new Date());
const status = ref('');
const button_text  =  ref('');
const http = new ApiHandler();

setInterval(() => {
    currentTime.value = new Date();
}, 100);


const getButtonClass = () => {
    switch (status.value) {
        case 'UNCHECKED':
            button_text.value =  'Check In'
            return 'bg-[#06e406]';
        case 'CHECKED':
            button_text.value =  'Check Out'
            return 'bg-[#e40606]';
        case 'EOD':
            button_text.value =  'Day End'
            return 'bg-gray-400 cursor-disabled';
        default:
            return 'invisible'
    }
};


const handleCheckActionBtnClick = () => {
   
    if (status.value === 'UNCHECKED') {
        const payload = {
            working_date: format(new Date(), 'yyyy-MM-dd'),
            checkin_time: format(new Date(), 'hh:mm:ss')
        }
        http.post('/api/check-in', payload).then(response => {
            console.log(response)
            status.value = 'CHECKED';
        }).catch(error => {
            console.log(error);
        })
    }
    else if (status.value === 'CHECKED'){
        const payload = {
            working_date: format(new Date(), 'yyyy-MM-dd'),
            checkout_time: format(new Date(), 'hh:mm:ss')
        }
        http.post('/api/check-out', payload).then(response => {
            console.log(response)
            status.value = 'EOD';
        }).catch(error => {
            console.log(error);
        })
    }
};


onMounted(() => {
    http.get('/api/atttendance-status').then(response => {
        status.value = response.data.message;
    }).catch(error => {
        console.log(error);
    })
});

</script>


<style scoped>
/* CSS */
.check-btn {
    color: #fff;
    padding: 15px 25px;
    background-image: radial-gradient(93% 87% at 87% 89%, rgba(0, 0, 0, 0.23) 0%, transparent 86.18%), radial-gradient(66% 66% at 26% 20%, rgba(255, 255, 255, 0.55) 0%, rgba(255, 255, 255, 0) 69.79%, rgba(255, 255, 255, 0) 100%);
    box-shadow: inset -3px -3px 9px rgba(255, 255, 255, 0.25), inset 0px 3px 9px rgba(255, 255, 255, 0.3), inset 0px 1px 1px rgba(255, 255, 255, 0.6), inset 0px -8px 36px rgba(0, 0, 0, 0.3), inset 0px 1px 5px rgba(255, 255, 255, 0.6), 2px 19px 31px rgba(0, 0, 0, 0.2);
    font-weight: bold;
    font-size: 18px;
    border: 0;
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
    cursor: pointer;
    transition: all 0.2s ease;

}

/* Define animation keyframes */
@keyframes rotate-border {
    0% {
        border-color: white;
        transform: rotate(0deg);
    }

    100% {
        border-color: white;
        transform: rotate(360deg);
    }
}
</style>