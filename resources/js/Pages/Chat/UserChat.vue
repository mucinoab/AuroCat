<template>
  <div @click="$emit('mostrarConversacion')" 
      class="my-2 mr-4 p-2 flex cursor-pointer rounded-lg hover:bg-gray-200 dark:hover:bg-blue-500 dark:border-b-2 dark:border-blue-600"
      :class="chatColor,select">
    <div class="w-full">
      <div class="flex flex-row justify-between items-center">
        <h2 class="text-lg font-bold text-black dark:text-white">{{chat.name}}</h2>
        <div class="text-sm">
          <span class="text-gray-500 dark:text-gray-300">{{date}}</span>
        </div>
      </div>
      
      <div class="flex justify-between">
        <p class="text-xs text-gray-500 dark:text-gray-300" v-html="chat.lastMessage"></p>
        <span v-if="chat.unread" class="text-sm bg-blue-500 rounded-full w-5 h-5 text-center text-white font-bold">{{chat.unread}}</span>
      </div>
    
    </div>
  </div>
</template>

<script>
export default {
    props:{
      chat:{},
      chat_id:''
    },
    computed:{
      date(){
        return timeFromUnix(this.chat.date * 1000);
      },
      chatColor(){
        if(this.chat.state != 2){
          return {
            'bg-yellow-500': this.chat.turn,
            'bg-green-500': !this.chat.turn,
            'bg-opacity-75': true,
          }
        }
      },
      select(){
        return {'border-l-4 border-blue-600': this.chat_id == this.chat.id};
      }
    }
}
</script>