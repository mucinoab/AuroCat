<template>
    <div class="p-3 rounded-xl mt-2 relative w-1/2" :class="{'self-end':message.transmitter,'bg-blue-500':message.transmitter, 'bg-gray-50':!message.transmitter,'text-white':message.transmitter,'text-black':!message.transmitter}">
        <p v-html="message.message"></p>
        <span class="text-xs absolute right-2 bottom-2">{{message.date}}</span>
    </div>
</template>

<script>
export default {
    props:{
        message:{}
    }
}
</script>