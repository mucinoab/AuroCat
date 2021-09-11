<template>
  <div class="flex h-screen overflow-hidden">
    <!-- Left Section-->
    <div class="bg-white rounded shadow p-6 w-full lg:w-1/4">
      <div class="mb-4 text-center">
        <h1 class="text-grey-darkest">Chats</h1>
        <!-- <div class="flex mt-4 items-center justify-between">
          <Category 
            v-for="category in categories" 
            :category="category">
          </Category>
        </div> -->
      </div>

      <div v-if="load">
        <div class="flex items-center justify-center w-full h-full">
          <div class="flex justify-center items-center space-x-1 text-sm text-gray-700">
            <svg fill="none" class="w-6 h-6 animate-spin" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
              <path clip-rule="evenodd" d="M15.165 8.53a.5.5 0 01-.404.58A7 7 0 1023 16a.5.5 0 011 0 8 8 0 11-9.416-7.874.5.5 0 01.58.404z" fill="currentColor" fill-rule="evenodd"/>
            </svg>
            <div>Loading Chats...</div>
          </div>
        </div>
      </div>

      <div class="overflow-auto overflow-x-hidden h-3/4 w-full">
        <UserChat
          v-for="(chat, idx) in chats"
          :chat="chat"
          @click="loadConversation(chat.id, chat.name)"
        >
        </UserChat>
      </div>

      <div class="flex mt-4">
        <button class="flex justify-center items-center w-full p-2 m-2 rounded-lg bg-gray-600 bg-opacity-10 focus:outline-none focus:ring">
          <p class="text-xs font-semibold">Cargar más chats</p>
        </button>
      </div>
    </div>

    <!-- Middle Section -->
    <div class="bg-white rounded shadow p-6 w-full lg:w-3/4 flex flex-col justify-between">
      <!-- Chat name -->
      <div class="flex p-3 justify-center">
        <h1 class="text-grey-darkest" :name="name">{{ name }}</h1>
      </div>

      <!-- <div class="flex p-3 justify-center" v-if="chat_id!=''">
         <div class="hover:shadow-md p-4 rounded-full duration-1000 ease-in-out transform hover:scale-125 delay-200 hover:rotate-180 text-3xl font-bold text-center bg-gray-500		md:p-5 ">
        <svg xmlns="http://www.w3.org/2000/svg" class="text-white h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path strokeLinecap="round" strokeLinejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
    </div>
    </div> -->

      <div class="conversation overflow-auto flex flex-col items-stretch">
        <Message v-for="message in messages" :message="message"> </Message>
      </div>

      <!-- Input for writing a messages -->
      <div class="flex p-3" v-if="chat_id != ''">
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

    <!-- Right Section -->
    <template v-if="state != ''">
      <div class="bg-white rounded shadow flex flex-col lg:w-1/5  justify-center text-center">

        <div class="m-3">
          <template v-if="game.state == 2">
            <button class="font-bold rounded border-b-2 border-red-600 bg-red-500 text-white shadow-md py-2 px-6 inline-flex items-center">
              <span class="mr-2">Finalizado</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentcolor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
              </svg>
            </button>
          </template>
          <template v-else-if="!game.turn">
            <button class="font-bold rounded border-b-2  border-yellow-600 bg-yellow-500 text-white shadow-md py-2 px-6 inline-flex items-center">
              <span class="mr-2" >Espera Movimiento</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentcolor" d="M6 2v6h.01L6 8.01 10 12l-4 4 .01.01H6V22h12v-5.99h-.01L18 16l-4-4 4-3.99-.01-.01H18V2H6zm10 14.5V20H8v-3.5l4-4 4 4zm-4-5l-4-4V4h8v3.5l-4 4z"></path>
              </svg>
            </button>
          </template>
          <template v-else>
            <button class="font-bold rounded border-b-2 border-green-600 bg-green-500 text-white shadow-md py-2 px-6 inline-flex items-center">
              <span class="mr-2">Tu turno</span>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentcolor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
              </svg>
            </button>
          </template>
      </div>




      <div class="flex justify-center cursor-not-allowed">
          <Board :state="state" v-on:move="move" :newMove="newMove"> </Board>
        </div>

      <div class="col-span-12 sm:col-span-6 md:col-span-3">
        <div class="flex flex-row bg-white shadow-sm rounded p-4">
          <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-green-100 text-green-500">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
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
          <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-orange-100 text-orange-500">
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
          <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl bg-red-100 text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
          </div>
          <div class="flex flex-col flex-grow ml-4">
            <div class="text-sm text-gray-500">Ganador</div>
            <div class="font-bold text-lg">{{game.winner == null ? '' : this.name }}</div>
          </div>
        </div>
      </div>



        <div class="flex mt-4">
          <button class="flex justify-center items-center w-full p-2 m-2 rounded-lg  bg-red-900 focus:outline-none focus:ring">
            <p class="text-xs text-white font-semibold">Finalizar Partida</p>
          </button>
        </div>
      </div>
    </template>
  </div>
</template>

<style>
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

export default {
  components: {
    Category,
    UserChat,
    Message,
    Board,
  },
  data() {
    return {
      categories: [
        {
          name: "Agente",
          d: "M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z",
          id: 0,
        },
        {
          name: "Bot",
          d: "M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z",
          id: 1,
        },
        {
          name: "Finalizados",
          d: "M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4",
          id: 2,
        },
      ],
      chats: [],
      messages: [],
      message: "",
      userChatSelected: "",
      chat_id: "",
      instanceId: "",
      state: "",
      newMove: "",
      load: true,
      name: "",
    };
  },
  methods: {
    scrollToEnd(e) {
      var container = document.querySelector(".conversation");
      var scrollHeight = container.scrollHeight;
      container.scrollTop = scrollHeight;
    },
    handlePackage(pckg) {
      if (pckg.hasOwnProperty("callback")) {
        if (this.chat_id == pckg.id) {
          this.newMove = pckg.callback;
        }
      } else {
        let position = this.chats.findIndex((chat) => chat.id == pckg.id);
        if (pckg.hasOwnProperty("option")) {
          this.chats[position].unread = 0;
          return;
        }

        if (position === -1) {
          pckg.date = timeFromUnix(pckg.date * 1000);
          pckg.unread = 1;
          this.chats.unshift(pckg);
          return;
        } else if (pckg.id == this.chat_id) {
          if (this.instanceId != pckg.instanceId) {
            pckg.date = timeFromUnix(pckg.date * 1000);
            this.messages.push(pckg);
          }
        }

        this.chats[position].date = timeFromUnix(new Date().getTime());
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
        date: timeFromUnix(unixTime() * 1000),
      };
      this.messages.push(message);
      this.instanceId = uuid();
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
    readMessages(chat_id) {
      postData("/unread", { chat: chat_id, option: "DELETENOTIFICATIONS" });
    },
    loadConversation(chat_id, name) {
      this.state = "";
      this.chat_id = chat_id;
      this.name = name;
      this.showConversation(chat_id);
      this.getLastGame(chat_id);
    },
    async showConversation(chat_id) {
      fetch(`/conversation?chat_id=${chat_id}&chats_number=5`)
        .then((response) => response.json())
        .then((json) => {
          this.messages = json.conversation.map(function (message) {
            message.date = timeFromUnix(message.date * 1000);
            return message;
          });
        });
    },
    async getLastGame(chat_id) {
      fetch(`/lastGame?chat_id=${chat_id}`)
        .then((response) => response.json())
        .then((json) => {
          this.state = json.lastGame;
          this.game  = json.game;
        });
    },
  },
  async beforeCreate() {
    fetch("/chats")
      .then((response) => response.json())
      .then((json) => {
        this.chats.push(...json.chats);
        this.chats.forEach((ch) => {
          ch.date = timeFromUnix(ch.date * 1000);
          ch.lastMessage = ch.lastMessage;
          ch.unread = 0;
        });
      });
  },
  mounted() {
    var channel = pusher.subscribe("nuevo-mensaje");
    channel.bind("App\\Events\\Notify", this.handlePackage);
    this.scrollToEnd();
  },
  updated() {
    this.load = false;
    this.scrollToEnd();
  },
};
</script>
