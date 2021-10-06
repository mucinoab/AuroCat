<template>
  <div @click="$emit('mostrarConversacion')" 
      class="my-2 mr-4 p-2 flex cursor-pointer rounded-lg hover:bg-gray-200 dark:hover:bg-cat"
      :class="chatColor,select">
    <div class="w-full">
      <div class="flex flex-row justify-between items-center">
        <h2 class="text-base font-medium text-black dark:text-cat-light-secundary-3 pb-3">{{chat.name}}</h2>
        <div class="text-sm">
          <span class="text-gray-500 dark:text-gray-300">{{date}}</span>
        </div>
      </div>
      
      <div class="flex justify-between">
        <p class="text-base text-medium text-gray-500 dark:text-gray-300" v-html="lengthMessage"></p>
        <span 
          v-if="chat.unread" 
          class="text-sm bg-blue-500 rounded-full h-5 text-center text-white font-bold" 
          :class="unread">{{chat.unread}}</span>
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
            'bg-yellow-500 dark:bg-cat-hard-colors-3': this.chat.turn,
            'bg-green-500 dark:bg-cat-hard-colors-2': !this.chat.turn,
            'bg-opacity-75': true,
          }
        }
      },
      select(){
        return {'bg-gray-200 dark:bg-cat': this.chat_id == this.chat.id};
      },
      unread(){
        if(this.chat.unread>=100){ return 'w-auto';}
        
        return 'w-5';
      },
      lengthMessage(){
          return this.chat.lastMessage.length>40 ? this.chat.lastMessage.substr(0,40) + '...' : this.chat.lastMessage;
      }
    }
}
</script>
