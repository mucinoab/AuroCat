"use strict";

const chatsBox =  {
  data() {
    return {
      chats: [{name: "Loading..."}],
      activeIdx: null,
    };
  },

  // Load all the chats from the server.
  created() {
    fetch("/chats")
      .then(response => response.json())
      .then(json => {
        // remove the loading indicator
        this.chats.pop(); 

        this.chats.push(...json.chats);
        this.chats.forEach(e => e.time = timeFromUnix(e.time));
      });
  },

  methods: {
    // Highlight chat box on click
    toggle(idx) {
      this.activeIdx = idx;
      chatId = this.chats[idx].id;
    },

    // Updates the positions of the chats when a new message is sent or received 
    updateChat(id) {
      chatId = id = parseInt(id, 10);

      // find the position
      const pos = this.chats.findIndex(e => e.id === id);

      this.chats[pos].time = timeFromUnix(new Date().getTime());

      // shift elements 
      this.chats.unshift(this.chats.splice(pos, 1)[0]);

      // Update highlighted element
      if (pos === this.activeIdx || this.activeIdx === null)
        this.activeIdx = 0;
      else if (pos > this.activeIdx)
        this.activeIdx += 1;
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
      <div class="prev-message">
        {{ chats.id }}
      </div>
    </div>`,
});

const vm = app.mount("#chats");
