<template>
<div class="shadow-lg rounded-lg bg-white mx-auto m-8 p-4 notification-box flex dark:bg-cat">
    <div class="pr-2">
        <svg class="fill-current" :class="iconColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
        <path class="heroicon-ui" 
        :d="card.d"/>
        </svg>
    </div>
    <div class="">
        <div class="text-sm pb-2 dark:text-white">
        {{card.firstMessage}}
        </div>
        <div class="text-sm text-gray-600  tracking-tight dark:text-white">
        {{card.secondMessage}}
        </div>
        <div class="flex justify-center pt-2">
        <button
        class="bg-transparent hover:text-white rounded shadow hover:shadow-lg py-2 px-4 border hover:border-transparent dark:text-white"
        :class="color">{{card.value}}</button>
        </div>
    </div>
    </div>
</template>

<script>
export default {
    props:{
        card:''
    },
    computed:{
        color(){
            return `hover:bg-${this.card.color}-600 text-${this.card.color}-600 border-${this.card.color}-300 dark:border-${this.card.color}-500`;
        },
        iconColor(){
            return `text-${this.card.color}-600`;
        }
    }
}
</script>