<template>
  <div class="flex h-screen overflow-hidden">
    <!-- Left Section-->
    <div class="bg-white rounded shadow p-6 w-full lg:w-1/4">
      <div class="mb-4 text-center">
        <h1 class="text-black text-xl font-bold">Chats</h1>
      </div>
      <!-- chat error notification--> 
      <div class="shadow-lg rounded-lg bg-white mx-auto m-8 p-4 notification-box flex" v-if="errors.chatsError">
        <div class="pr-2">
          <svg class="fill-current text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path class="heroicon-ui" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-3.54-4.54a5 5 0 0 1 7.08 0 1 1 0 0 1-1.42 1.42 3 3 0 0 0-4.24 0 1 1 0 0 1-1.42-1.42zM9 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm6 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
          </svg>
        </div>
        <div class="">
          <div class="text-sm pb-2">
            Lo sentimos
          </div>
          <div class="text-sm text-gray-600  tracking-tight ">
            Al parecer tuvimos un error al obtener los datos.
          </div>
          <div class="flex justify-center pt-2">
            <a href="/" class="bg-transparent hover:bg-red-600 text-red-600 hover:text-white rounded shadow hover:shadow-lg py-2 px-4 border border-red-300 hover:border-transparent"> Recargar</a>
          </div>
        </div>
      </div>

      <template v-if="loads.loadChats">
        <!-- loading chats icon -->
        <div class="flex items-center justify-center w-full h-full">
          <div class="flex justify-center items-center space-x-1 text-sm text-gray-700">
            <svg fill="none" class="w-6 h-6 animate-spin" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
              <path clip-rule="evenodd" d="M15.165 8.53a.5.5 0 01-.404.58A7 7 0 1023 16a.5.5 0 011 0 8 8 0 11-9.416-7.874.5.5 0 01.58.404z" fill="currentColor" fill-rule="evenodd"/>
            </svg>
            <div>Cargando chats...</div>
          </div>
        </div>
      </template>
      <template v-else>

        <!-- chats -->
        <div class="overflow-auto overflow-x-hidden h-3/4 w-full">
          <UserChat
            v-for="(chat, idx) in chats"
            :chat="chat"
            @click="loadConversation(chat.id, chat.name,chat.gameId)"
            :chat_id="chat_id">
          </UserChat>

        </div>
        <!-- more chats button -->
        <div class="flex mt-4">
          <button class="flex justify-center items-center w-full p-2 m-2 rounded-lg bg-blue-600 focus:outline-none focus:ring">
            <p class="text-xs text-white text-base">Cargar más chats</p>
          </button>
        </div>
      </template>
    </div>

    <!-- Middle Section -->
    <template v-if="name != ''">
      <div class="bg-white rounded shadow p-6 w-full lg:w-3/4 flex flex-col justify-between">
        <!-- message error notification--> 
        <div class="shadow-lg rounded-lg bg-white mx-auto m-8 p-4 notification-box flex" v-if="errors.messagesError">
          <div class="pr-2">
            <svg class="fill-current text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
              <path class="heroicon-ui" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-3.54-4.54a5 5 0 0 1 7.08 0 1 1 0 0 1-1.42 1.42 3 3 0 0 0-4.24 0 1 1 0 0 1-1.42-1.42zM9 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm6 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </svg>
          </div>
          <div class="">
            <div class="text-sm pb-2">
              Lo sentimos
            </div>
            <div class="text-sm text-gray-600  tracking-tight ">
              Al parecer tuvimos un error al obtener los mensajes.
            </div>
            
          </div>
        </div>

        <div class="flex p-3 justify-between	border-b-2 border-gray-100">
          <!-- Chat name -->
          <h1 class="text-black text-xl font-bold" :name="name">{{ name }}</h1>
          <!-- Close chat icon -->
          <div>
            <button
              type="button"
              class="p-2 ml-2 text-gray-400 rounded-full hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring"
              @click="closeChat">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- loading messages icon -->
        <template v-show="loads.loadMessage">
          <div class="flex items-center justify-center w-full h-full" >
            <div class="flex justify-center items-center space-x-1 text-sm text-gray-700">
              <svg fill="none" class="w-6 h-6 animate-spin" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" d="M15.165 8.53a.5.5 0 01-.404.58A7 7 0 1023 16a.5.5 0 011 0 8 8 0 11-9.416-7.874.5.5 0 01.58.404z" fill="currentColor" fill-rule="evenodd"/>
              </svg>
              <div>Cargando mensajes...</div>
            </div>
          </div>
        </template>
       
        <!-- Messages -->
        <div class="conversation overflow-y-scroll overflow-x-hidden 	flex flex-col items-stretch flex-col-reverse">
          <Message v-for="message in messages" :message="message"> </Message>
        </div>

        <!-- Input for writing a messages -->
        <div class="flex p-3" v-show="inputAvailable">
          <div class="flex-1 px-3">
            <input type="text" class="w-full border-2 border-gray-100 rounded-full px-4 py-1 outline-none text-gray-500 focus:outline-none  focus:ring"
              placeholder="Escribe un mensaje..."
              v-model="message"
              @keyup.enter="sendMessage"/>
          </div>
          <div>
            <button
              type="button"
              class="p-2 ml-2 text-gray-400 rounded-full hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring"
              @click="sendMessage">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-6 w-6 transform rotate-90">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z">
                </path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </template>

    <!-- Right Section -->
    <template v-if="game != ''">
      <div class="bg-white rounded shadow flex flex-col lg:w-1/5  justify-center text-center">
        <div class="m-3">
          <template v-if="game.state == 2">
            <div class="font-bold select-none	rounded border-b-2 border-red-600 bg-red-500 text-white shadow-md py-2 px-6 inline-flex items-center">
              <span class="mr-2">Finalizado</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentcolor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
              </svg>
            </div>
          </template>
          <template v-else-if="game.state_relation.turn == 1">
            <div class="font-bold select-none rounded border-b-2  border-yellow-600 bg-yellow-500 text-white shadow-md py-2 px-6 inline-flex items-center">
              <span class="mr-2" >Espera Movimiento</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentcolor" d="M6 2v6h.01L6 8.01 10 12l-4 4 .01.01H6V22h12v-5.99h-.01L18 16l-4-4 4-3.99-.01-.01H18V2H6zm10 14.5V20H8v-3.5l4-4 4 4zm-4-5l-4-4V4h8v3.5l-4 4z"></path>
              </svg>
            </div>
          </template>
          <template v-else>
            <div class="font-bold select-none rounded border-b-2 border-green-600 bg-green-500 text-white shadow-md py-2 px-6 inline-flex items-center">
              <span class="mr-2">Tu turno</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentcolor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
              </svg>
            </div>
          </template>
        </div>

        <div class="flex justify-center" :class="{'cursor-not-allowed': boardAvailable}">
          <Board 
            :state="game" 
            :newMove="newMove"
            v-on:move="move">
          </Board>
        </div>

        <div class="col-span-12 sm:col-span-6 md:col-span-3">
          <div class="flex flex-row bg-white shadow-sm rounded p-4">
            <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-green-100 text-green-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <div class="flex flex-col flex-grow ml-4">
              <div class="text-sm text-gray-500">Partida</div>
              <div class="font-bold text-lg">{{game.state != 2 ? 'En curso' : 'Finalizada'}}</div>
            </div>
          </div>
        </div>

        <div class="col-span-12 sm:col-span-6 md:col-span-3">
          <div class="flex flex-row bg-white shadow-sm rounded p-4">
            <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-red-100 text-red-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div class="flex flex-col flex-grow ml-4">
              <div class="text-sm text-gray-500">Oponente</div>
              <div class="font-bold text-lg">{{game.opponent ? 'Agente' : 'Bot'}}</div>
            </div>
          </div>
        </div>

        <div class="col-span-12 sm:col-span-6 md:col-span-3">
          <div class="flex flex-row bg-white shadow-sm rounded p-4">
            <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-orange-100 text-orange-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
            </div>
            <div class="flex flex-col flex-grow ml-4">
              <div class="text-sm text-gray-500">Ganador</div>
              <div class="font-bold text-lg">{{ setWinner }}</div>
            </div>
          </div>
        </div>

        <div class="flex mt-4">
          <button class=" font-bold flex justify-center items-center w-full p-2 m-2 rounded-lg  bg-red-500 focus:outline-none focus:ring">
            <p class="text-xs text-white text-base">Finalizar Partida</p>
          </button>
        </div>
      </div>
    </template>

  </div>
</template>

<style>
/* To scroll messages to buttom */
.scroll {
  width: 300px;
  max-height: 150px;
  overflow: scroll;
  background: lightgrey;
  margin-bottom: 20px;
}
</style>

<script>
import Category from "./Category.vue";
import UserChat from "./UserChat.vue";
import Message from "./Message.vue";
import Board from "./Board.vue";
import uuidUnique from "/js/instanceId.js";

export default {
  components: {
    Category,
    UserChat,
    Message,
    Board,
  },
  data() {
    return {
      WIN_VALUES:[7, 56, 448, 73, 146, 292, 273, 84],
      errors:{
        chatsError:false,
        messagesError:false
      },
      loads:{
        loadChats:true,
        loadMessage:true
      },
      // save all the chats
      chats: [],
      // save the games of all chats
      games:[],
      // save the ids of all chats
      chatsIds:[],
      //save the all conversations
      allMessages: [],
      //save the messages of the current conversation
      messages: [],
      // the value of the input message
      message: "",

      // userChatSelected: "",
      chat_id: "",
      instanceId: "",
      // the game state of selected chat
      state: '',
      //save the new move to update the board
      newMove: "",
      //save the name of the user
      name: "",
      //save the game information
      game: ''
    }

  },
  computed:{
    inputAvailable(){
      if(this.chat_id != '') return true;

      return false;
    },
    boardAvailable(){
      if(this.game.state == 2 || this.game.state_relation.turn == 1) return true;

      return false;
    },
    setWinner(){
      if(this.game.winner!=undefined){
        var opponent = this.game.opponent == 0 ? 'bot' : 'agente';
        return this.game.winner == 0 ? opponent : this.name;
      }
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
      if (pckg.hasOwnProperty("callback")) {
        // this send a event to the child to update the board
        this.newMove = pckg.callback; 

        //search for the chat to update the position and state value
        let position = this.chats.findIndex((chat) => chat.id == pckg.id);
        var data = pckg.callback.data.split(",");
        var game_id = data[5];
        //ELIMINE TODAS LAS OPCIONES PARA CAMBIO DE COLORES Y ESTADOS EN EL CHAT Y AL MOMENTO DE JUGAR PARA
        //NO HACER MÁS CONFUSIÓN, ESO PUEDE HASTA OMITIRSE
       

        //HERE LOGIC FOR UPDATE BOARDS ---


        this.chats[position].gameId = game_id;
        // Shift elements
        this.chats.unshift(this.chats.splice(position, 1)[0]);

      } else {
        //save messages from all conversation
        let position = this.chats.findIndex((chat) => chat.id == pckg.id);

        if (position === -1) {
          pckg.unread = 1;
          pckg.lastMessage = pckg.message;
          pckg.state = 2;
          this.chats.unshift(pckg);

          var obj = {
            'chat_id':pckg.id,
            'messages':[{
              'chat_id':pckg.id,
              'message':pckg.message,
              'transmitter':pckg.transmitter,
              'date':pckg.date,
              'game_id':'',
            }]
          };

          this.allMessages.push(obj);
          return;
        }

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
    // To send the message to the bottom
    scrollToEnd() {
      var container = document.querySelector(".conversation");
      if(container!=undefined){
        var scrollHeight = container.scrollHeight;
        container.scrollTop = scrollHeight;
      }
    },
    // To change the winner -- open to changes
    isWin(white,black){
      for (var i = 0; i < 7; i++) {
        if ((black & this.WIN_VALUES[i]) == this.WIN_VALUES[i]) return 1;
        if ((white & this.WIN_VALUES[i]) == this.WIN_VALUES[i]) return 0;
      }
      return 2;
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
      postData("/send-telegram", {
        chat: this.chat_id,
        msg: this.message,
        senderId: this.instanceId,
      });

      this.message = "";
    },
    move(data) {
      var msgId = data.split(",")[5];
      postData("/telegram-update", {
        callback_query: {
          data: data,
          message: {
            chat: { id: this.chat_id },
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
      if(game_id == ''){
        //TODO: create a element to charge the game for this user
      }else{
        this.getGame(game_id);
      }
      this.chat_id = chat_id;
      this.name = name;
      this.getConversation(chat_id);

      //DELETE UNREAD MESSAGES
      let position = this.chats.findIndex((chat) => chat.id == chat_id);
      this.chats[position].unread = 0;
    },
    //get the game of the selected chat
    async getGame(game_id) {
      fetch(`/lastGame?game_id=${game_id}`)
        .then((response) => response.json())
        .then((json) => {
          this.game = json.game;
        })
    },
    //get the conversation of the selected chat
    getConversation(chat_id){
        var selectedGame = this.allMessages.filter(message => message.chat_id == chat_id);
        this.messages = selectedGame[0].messages;
    },
    async loadMoreMessages() {
      //TODO /conversation?chat_id=${chat_id}&chats_number=10}
    },
    async loadMoreChats() {
      //TODO /chats?chats_number=5&offset=
    },
    async loadConversations() {
      fetch(`/conversation?chats_number=10&chats=${JSON.stringify(this.chatsIds)}`)
        .then((response) => response.json())
        .then((json) => {
          this.allMessages = json.messages;
        })
        .catch(error=> {
          this.errors.messagesError = true;
        });
    }
  },
  created() {
    //create the instancheID for this user
    this.instanceId = uuidUnique();

    //Get request using fetch with error handling
    fetch("/chats?chats_number=20")
      .then(async response => {
        const data = await response.json();
        // check for error response
        if(!response.ok){
          const error = (data && data.message) || response.statusText;
          return Promise.reject(error);
        }

        data.data.forEach(element => {
          this.chats.push(element.chats);
          this.chatsIds.push(element.chats.id)
        });
        this.loads.loadChats = false;
        //TODO ASYNC
        this.loadConversations();
      })
      .catch(error=> {
        this.loads.loadChats = false;
        this.errors.chatsError = true;
      });
  },
  mounted() {
    var channel = pusher.subscribe("nuevo-mensaje");
    channel.bind("App\\Events\\Notify", this.handlePackage);
    this.scrollToEnd();
  },
  updated() {
    this.scrollToEnd();
  }
};

</script>
