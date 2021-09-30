<template>
    <div class="p-3 rounded-xl mt-2 relative max-w-prose text-base" :class="styles">
        <p class="mb-2 break-words" v-html="message.message"></p>
        <span class="text-xs absolute right-2 bottom-2">{{date}}</span>
    </div>
</template>

<script>
export default {
    props:{
        message:{}
    },
    computed:{
       date(){
            return timeFromUnix(this.message.date * 1000);
        },
        styles(){
            return {
                'self-end':this.message.transmitter,
                'bg-blue-500':this.message.transmitter,
                'bg-gray-50':!this.message.transmitter,
                'text-white':this.message.transmitter,
                'text-black':!this.message.transmitter
            };
        }
    }
}
</script>
