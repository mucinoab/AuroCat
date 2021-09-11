<template>
  <div @click="$emit('mostrarConversacion')" class="my-2 mr-4 p-2 flex cursor-pointer rounded-lg hover:bg-gray-50 hover:bg-opacity-50 focus:outline-none focus:ring">
    <div class="w-full">
      <div class="flex flex-row justify-between items-center">
        <h2 class="text-xs font-bold">{{chat.name}}</h2>
        <div class="text-xs">
          <span class="text-gray-400">{{chat.date}}</span>
        </div>
      </div>
      
      <div class="flex justify-between">
        <p class="text-xs text-gray-500" v-html="chat.lastMessage">
        </p>
        <span v-if="chat.unread" class="text-sm bg-blue-500 rounded-full w-5 h-5 text-center text-white font-bold">{{chat.unread}}</span>
      </div>
    
    </div>
  </div>
  </template>

<script>
export default {
    props:{
        chat:{}
    }
    
}
</script>