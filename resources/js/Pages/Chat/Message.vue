<template>
    <div class="p-3 rounded-xl mt-2 relative max-w-prose text-base" :class="styles">
        <p class="mb-2 break-words pr-12 text-base font-normal" v-html="message.message"></p>
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
                'self-start':!this.message.transmitter,
                'bg-cat-hard-blue-2':this.message.transmitter,
                'bg-cat-light-secundary-3':!this.message.transmitter,
                'text-cat-light-secundary-3':this.message.transmitter,
                'text-cat-hard-5':!this.message.transmitter
            };
        }
    }
}
</script>
