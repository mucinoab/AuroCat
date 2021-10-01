<template>
<app-layout title="Messenger">
  <Head title="Messenger" />

  <div class="grid grid-cols-5 dark:bg-cat-light">
    
    <!-- Left Section-->
    <div class="flex flex-col h-screen p-3 bg-gray-50 dark:bg-cat-light">
      <!-- Chat title -->
      <div class="mb-4 p-4 text-center rounded-md dark:bg-cat">
        <h1 class="text-xl font-bold text-black dark:text-white">Chats</h1>
      </div>
      <!-- Error message card -->
      <CardInfo v-if="errors.chatsError" :card="cards[0]"></CardInfo>
      <template v-if="loads.loadChats">
        <!-- loading chats icon -->
        <LoadMessage :message="LoadingMessages[0]"></LoadMessage>
      </template>
      <template v-else>
        <!-- chats -->
        <div class="scroll-thin overflow-auto">
          <UserChat
            v-for="(chat, idx) in chats"
            :chat="chat"
            @click="loadConversation(chat.id, chat.name,chat.gameId)"
            :chat_id="chat_id">
          </UserChat>
        </div>
        <!-- more chats button -->
        <div v-if="loads.moreChats">
          <button class="w-full p-2 m-2 rounded-lg bg-blue-600"
            @click="loadMoreChats">
            <p class="font-bold text-xs text-white text-base">Cargar más chats</p>
          </button>
        </div>
      </template>
    </div>

    <!-- Middle Section -->
    <template v-if="name != ''">
      <div class="col-span-3 flex flex-col h-screen p-3 bg-white dark:bg-cat-light">
        <!-- Conversation header -->
        <div class="flex p-3 justify-between border-b-2 border-gray-100">
          <!-- Chat name -->
          <h1 class="text-xl font-bold text-black dark:text-white" :name="name">{{ name }}</h1>
          <!-- Close chat icon -->
          <div>
            <button
              type="button"
              class="p-2 text-gray-400 rounded-full hover:text-gray-600 hover:bg-gray-100"
              @click="closeChat">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Error message card -->
        <CardInfo v-if="errors.messagesError" :card="cards[2]"></CardInfo>
       
        <!-- Messages -->
        <div class="scroll-thin overflow-y-scroll flex flex-col-reverse h-screen pr-3">
          <Message v-for="message in messages" :message="message"> </Message>
        </div>

        <!-- Input for writing a messages -->
        <template v-if="inputAvailable">
          <div class="flex mt-5" >
            <div class="flex-1">
              <input type="text" class="w-full rounded-full py-1 text-gray-500"
                placeholder="Escribe un mensaje..."
                v-model="message"
                @keyup.enter="sendMessage"/>
            </div>
            <div>
              <button
                type="button"
                class="ml-2 p-2 text-gray-400 rounded-full hover:text-gray-600 hover:bg-gray-100"
                @click="sendMessage">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-6 w-6 transform rotate-90">
                  <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z">
                  </path>
                </svg>
              </button>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="text-center px-3 py-2 mx-1 my-2 rounded-md bg-orange-200 dark:bg-yellow-500">
            <span class="text-xl text-base text-yellow-600 dark:text-white">Este chat se encuentra atendido por un bot o ha finalizado</span>
          </div>
        </template>
      </div>
    </template>

    <!-- Right Section -->
    <!-- No game message card -->
    <div class="flex flex-col justify-center text-center bg-white dark:bg-cat" v-if="errors.gamesError">
      <CardInfo
      :card="cards[1]"
      @click="loadGame"
      ></CardInfo>
    </div>

    <!-- No game exists message card -->
    <div class="flex flex-col justify-center text-center bg-white dark:bg-cat" v-if="errors.noGameError">
      <CardInfo
      :card="cards[3]"
      ></CardInfo>
    </div>

    <template v-if="game != ''">
      <div class="flex flex-col justify-center text-center bg-white dark:bg-cat">

        <div class="m-3">
          <!-- Status game card -->
          <GameHeader
            :game="game">
          </GameHeader>
        </div>

        <div class="flex justify-center mb-10" :class="{'cursor-not-allowed': boardAvailable, 'cursor-pointer':!boardAvailable}">
          <!-- Board game -->
          <Board 
            :state="game" 
            :newMove="newMove"
            v-on:move="move">
          </Board>
        </div>

        <!-- Card of game information  -->
        <GameInformation
        :game="game"
        :option="info[0]"
        ></GameInformation>

        <GameInformation
        :game="game"
        :option="info[1]"
        ></GameInformation>

        <GameInformation
        :game="game"
        :option="info[2]"
        :name="name"
        ></GameInformation>

        <div class="p-2">
          <button class="w-full p-2 rounded-lg bg-red-500">
            <p class="font-bold text-xs text-white text-base">Finalizar Partida</p>
          </button>
        </div>
      </div>
    </template>

  </div>
</app-layout>
</template>

<style>
/* scroll in chrome */
::-webkit-scrollbar {
  width: 10px;
}
::-webkit-scrollbar-track {
  background: #f1f1f1; 
  border-radius: 10px;
  box-shadow: inset 0 0 1px #888888;
}
 
::-webkit-scrollbar-thumb {
  background: #888888; 
  border-radius: 10px;
}

/* scroll in firefox */
.scroll-thin{
  scrollbar-color: #888888 #f1f1f1;
  scrollbar-width: thin;
  border-radius: 10px;
}
</style>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import Category from "./Category.vue";
import UserChat from "./UserChat.vue";
import Message from "./Message.vue";
import Board from "./Board.vue";
import GameHeader from "./GameHeader.vue";
import GameInformation from "./GameInformation.vue";
import CardInfo from "./CardInfo.vue";
import LoadMessage from "./LoadMessage.vue";
import uuidUnique from "/js/instanceId.js";

export default {
  components: {
    AppLayout,
    Category,
    UserChat,
    Message,
    Board,
    GameHeader,
    GameInformation,
    CardInfo,
    LoadMessage
  },
  data() {
    return {
      WIN_VALUES:[7, 56, 448, 73, 146, 292, 273, 84],
      info:[
        {'option':0,'title':'Partida','color':'bg-green-100 text-green-500','icon':'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'},
        {'option':1,'title':'Oponente','color':'bg-red-100 text-red-500','icon':'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'},
        {'option':2,'title':'Ganador','color':'bg-orange-100 text-orange-500','icon':'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'}
      ],
      cards:[
        {'firstMessage':'Lo sentimos','secondMessage':'Al parecer tuvimos un error al obtener los datos','color':'red','value':'Recargar','d':'M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-3.54-4.54a5 5 0 0 1 7.08 0 1 1 0 0 1-1.42 1.42 3 3 0 0 0-4.24 0 1 1 0 0 1-1.42-1.42zM9 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm6 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2z'},
        {'firstMessage':'Cargar juego','secondMessage':'Da click para traer el último juego','color':'green','value':'Cargar','d':'M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-3.54-4.46a1 1 0 0 1 1.42-1.42 3 3 0 0 0 4.24 0 1 1 0 0 1 1.42 1.42 5 5 0 0 1-7.08 0zM9 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm6 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2z'},
        {'firstMessage':'Lo sentimos','secondMessage':'Al parecer tuvimos un error al obtener los mensajes','color':'red','value':'Recargar','d':'M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-3.54-4.54a5 5 0 0 1 7.08 0 1 1 0 0 1-1.42 1.42 3 3 0 0 0-4.24 0 1 1 0 0 1-1.42-1.42zM9 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm6 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2z'},
        {'firstMessage':'Sin juegos','secondMessage':'Este usuario no tiene ningún juego registrado','color':'red','value':'Recargar','d':'M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-3.54-4.54a5 5 0 0 1 7.08 0 1 1 0 0 1-1.42 1.42 3 3 0 0 0-4.24 0 1 1 0 0 1-1.42-1.42zM9 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm6 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2z'},

      ],
      LoadingMessages:[
        {'message':'Cargando chats...'}
      ],
      errors:{
        chatsError:false,
        messagesError:false,
        gamesError:false,
        noGameError:false
      },
      loads:{
        loadChats:true,
        loadMessage:true,

        // True until there are no more chats to load.
        moreChats: true,
      },
      // save all the chats
      chats: [],
      // save the ids of all chats
      chatsIds:[],
      //save the all conversations
      allMessages: [],
      //save the messages of the current conversation
      messages: [],
      // the value of the input message
      message: "",
      // chat selected info,
      chat_id: "",
      name: "",
      // agent instanceId
      instanceId: "",
      //save the new move to update the board
      newMove: "",
      //save the game information
      game: ''
    }
  },
  computed:{
    inputAvailable(){
      if(this.chat_id != '' && this.game_id != '' && this.game.state !=2 && this.game.opponent == 1 ) return true;

      return false;
    },
    boardAvailable(){
      if(this.game.state == 2 || this.game.state_relation.turn == 1) return true;

      return false;
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
  },
  methods: {
    // the method that listens to the propagation
    handlePackage(pckg) {

        let position = this.chats.findIndex((chat) => chat.id == pckg.id);

        if (position === -1) {
          pckg.unread = 1;
          pckg.lastMessage = pckg.hasOwnProperty("lastMessage") ? pckg.lastMessage : 'Juego en curso...' ;
          pckg.name = pckg.name;
          pckg.state = pckg.hasOwnProperty("callback") ? '' : 2;
          pckg.gameId = '';
          this.chats.unshift(pckg);
          position = 0;
          
          var obj = {
            'chat_id':pckg.id,
            'messages':[]
          };
          
          this.allMessages.push(obj);
        }

      if (pckg.hasOwnProperty("callback")) {
        var data = pckg.callback.data.split(",");
        var black = data[2];
        var white = data[3];
        var game_id = data[5];
        var turn = data[6] == 'agent' ? 1 : 0;

        // Propagates changes to the selected game and chats states
        if(this.chat_id == pckg.id && this.game != ''){
          this.newMove = pckg.callback; 
          this.game.state_relation.turn = turn;

          if(black == 0 && white == 0){
            this.chats[position].state = turn;
            this.game.state = 1;
            this.game.winner = undefined;
            this.game.opponent = !pckg.callback.practice_game;
          }else{
            if(pckg.callback.win != 3){
              this.game.winner = pckg.callback.win;
              this.game.state = 2;
              this.chats[position].state = 2;
            }
          }
        }else{
          this.chats[position].state = turn;
          if(black == 0 && white == 0){
            this.chats[position].turn = turn;
          }else{
            if(pckg.callback.win != 3){
              this.chats[position].state = 2;
            }
          }
        }

        this.chats[position].turn = turn;
        this.chats[position].gameId = game_id;
        // Shift elements
        this.chats.unshift(this.chats.splice(position, 1)[0]);
      } else {

        if(this.instanceId != pckg.instanceId && this.chat_id == pckg.id){
          this.messages.unshift(pckg);
        }

        //save messages from all conversation
        var selectedGame = this.allMessages.filter(message => message.chat_id == pckg.id);
        selectedGame[0].messages.unshift(pckg);
        
        this.chats[position].date = unixTime();
        this.chats[position].lastMessage = pckg.message;

        if (this.chat_id != pckg.id) {
          this.chats[position].unread += 1;
        } else {
          this.chats[position].unread = 0;
        }

        // Shift elements
        this.chats.unshift(this.chats.splice(position, 1)[0]);
      }
    },
    sendMessage() {
      if (this.message == "") {
        return;
      }
      const message = {
        message: this.message,
        transmitter: 1,
        date: unixTime(),
      };

      this.messages.unshift(message);
      
      postData("/send-telegram", {
        chat: this.chat_id,
        msg: this.message,
        senderId: this.instanceId,
      });
      this.message = "";
    },
    move(data) {
      var msgId = data.split(",")[7];
      postData("/telegram-update", {
        callback_query: {
          data: data,
          message: {
            chat: { id: this.chat_id, first_name:this.name},
            message_id: msgId,
            date: unixTime(),
          },
          // Signal the server that is an agent move.
          agent: 1,
        },
      });
    },
    closeChat(){
      this.message = "";
      this.chat_id = "";
      this.name = "";
      this.game = "";
    },
    //Get user and game for selected user
    loadConversation(chat_id, name,game_id) {
      this.errors.noGameError = false;
      if(game_id == ''){
        this.game = "";
        this.errors.gamesError = true;
      }else{
        this.errors.gamesError = false;

        this.getGame(game_id);
      }
      this.chat_id = chat_id;
      this.name = name;
      this.messages = [];
      this.getConversation(chat_id);

      //DELETE UNREAD MESSAGES
      let position = this.chats.findIndex((chat) => chat.id == chat_id);
      this.chats[position].unread = 0;
    },
    //get the game of the selected chat
    getGame(game_id) {
      const err = () => { 
        this.errors.gamesError = false;
        this.errors.noGameError = true;
        };

      fetch(`/lastGame?game_id=${game_id}`)
      .then(response => response.json())
      .catch(err)
      .then(data => this.game = data.game )
      .catch(err);
    },
    //get the conversation of the selected chat
    getConversation(chat_id){
        var selectedGame = this.allMessages.filter(message => message.chat_id == chat_id);
        this.messages.push(...selectedGame[0].messages);
    },

    loadGame(){
      fetch(`/game?chat_id=${this.chat_id}`)
        .then(async response => {
        const data = await response.json();
        // check for error response
        if(!response.ok){
          const error = (data && data.message) || response.statusText;
          return Promise.reject(error);
        }
        this.errors.gamesError = false;
        this.game = data.game;
        let position = this.chats.findIndex((chat) => chat.id == this.chat_id);
        this.chats[position].gameId = this.game.id;
      })
      .catch(_ => {
        this.errors.gamesError = false;
        this.errors.noGameError = true;
      });
    },

    async loadMoreMessages() {
      //TODO /conversation?chat_id=${chat_id}&chats_number=10}
    },

    async loadMoreChats() {
      var lengthChats = this.chats.length;

      fetch(`/chats?chats_number=${lengthChats+5}&offset=${lengthChats}`)
      .then(response => response.json())
      .then(json => {
        if (json.data.length === 0) {
          // There are no more chats to load.
          this.loads.moreChats = false;
          return;
        }

        this.chatsIds = [];

        json.data.forEach(element => {
          this.chats.push(element.chats);
          this.chatsIds.push(element.chats.id)
        });
        
        this.loadConversations()
      });
    },

    loadConversations() {
      fetch(`/conversation?chats_number=10&chats=${JSON.stringify(this.chatsIds)}`)
      .then(response => response.json())
      .catch(_ => this.errors.messagesError = true)
      .then(data => {
        this.allMessages.push(...data.messages);
      }).catch(_ => this.errors.messagesError = true);
    }
  },
  created() {
    //create the instancheID for this user
    this.instanceId = uuidUnique();
    //Get request using fetch with error handling
    fetch("/chats?chats_number=10")
      .then(async response => {
        const data = await response.json();
        // check for error response
        if(!response.ok){
          const error = (data && data.message) || response.statusText;
          return Promise.reject(error);
        }
        this.chats = [];
        data.data.forEach(element => {
          this.chats.push(element.chats);
          this.chatsIds.push(element.chats.id)
        });
        this.loads.loadChats = false;
        this.loadConversations();
      })
      .catch(_ => {
        this.loads.loadChats = false;
        this.errors.chatsError = true;
      });
  },
  mounted() {
    var channel = pusher.subscribe("nuevo-mensaje");
    channel.bind("App\\Events\\Notify", this.handlePackage);
  },
};

</script>
