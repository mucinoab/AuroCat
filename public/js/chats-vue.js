"use strict";

// Input bar at the bottom of conversations.
const inputBar = document.getElementById("input_bar");
const nameHeader = document.getElementById("name-header");

// Switches between conversation elements by hiding the old one and showing the
// new one. 
function openConversation(oldConv, newConv) {
  if (oldConv != null)
    document.getElementById(`conversation-${oldConv}`).style.setProperty("display", "none");
  else 
    inputBar.style.setProperty("display", "block"); // Show the input bar.

  document.getElementById(`conversation-${newConv}`).style.setProperty("display", "block");
}

// Element where all the conversations are stored.
const chats = document.getElementById("conversations");

function appendConversation(id) {
  const chat = newElement("div", "messages");
  chat.id = `conversation-${id}`;

  chats.appendChild(chat);
}

const chatsBox =  {
  data() {
    return {
      chats: [{name: "Loading..."}],
      activeIdx: null,
    };
  },

  // Load all the chats from the server.
  async beforeCreate() {
    fetch("/chats")
      .then(response => response.json())
      .then(json => {
        // Remove the loading indicator
        this.chats.pop(); 

        this.chats.push(...json.chats);

        this.chats.forEach(ch => { 
          ch.time = timeFromUnix(ch.time);
          ch.lastMessage = "···" ;
          ch.unreadMessages = 0;
          ch.lastName = "";

          appendConversation(ch.id);
        });

        // TODO fill the conversations
      });
  },

  methods: {
    // Highlight chat box on click.
    toggle(idx) {
      idx = parseInt(idx, 10);

      const oldConv = this.activeIdx === null ? null : this.chats[this.activeIdx].id;
     
      // Update active idx, current conversation id, clear notification count
      // and header name.
      this.activeIdx = idx;
      this.chats[idx].unreadMessages = 0;

      chatId = this.chats[idx].id;
      nameHeader.innerText = `${this.chats[idx].name} ${this.chats[idx].lastName}`;

      openConversation(oldConv, chatId);
    },

    // Updates the positions of the chats when a new message is sent or received.
    updateChat(id, message, pckg={}) {
      id = parseInt(id, 10);

      // Find the position
      let pos = this.chats.findIndex(e => e.id === id);

      if (pos === -1) {
        // Not found, is a new conversation, append conversation box and push
        // new object.
        
        appendConversation(id);

        pos = this.chats.length;
        this.chats.push({ name: pckg.name, id: id });
      }

      this.chats[pos].time = timeFromUnix(new Date().getTime());
      this.chats[pos].lastMessage = message;
      this.chats[pos].lastName = pckg.lastName === undefined ? "" : pckg.lastName ;

      if (pos !== this.activeIdx)
        this.chats[pos].unreadMessages += 1;

      // Shift elements 
      this.chats.unshift(this.chats.splice(pos, 1)[0]);

      // Update highlighted element
      if (pos === this.activeIdx)
        this.activeIdx = 0;
      else if (pos > this.activeIdx && this.activeIdx !== null)
        this.activeIdx += 1;

      notify(pckg.name, message);
    }
  },
};

const app = Vue.createApp(chatsBox);

app.component("chat", {
  props: ["chats"],
  template:
    `<div class="chat">
      <div class="nameUser">
        {{ chats.name }}
      </div>
      <div class="chat-time">
        {{ chats.time }}
      </div>
      <div class="notification-count" v-show="chats.unreadMessages > 0">
        {{ chats.unreadMessages }}
      </div>
      <div class="prev-message">
        {{ chats.lastMessage }}
      </div>
    </div>`,
});

const vm = app.mount("#chats");
