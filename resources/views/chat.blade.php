<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>Test Chat</title>

  <link rel="stylesheet" href="/css/style.css">
  <script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>
  <script>
    var pusher = new Pusher('{{env("MIX_PUSHER_APP_KEY")}}', {
      cluster: '{{env("PUSHER_APP_CLUSTER")}}',
      encrypted: true
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/vue@3.2.4/dist/vue.global.prod.min.js" defer></script>
  <script src="/js/utils.js" defer></script>
  <script src="/js/socket.js" defer></script>
  <script src="/js/chats-vue.js" defer></script>
</head>

<body>
  <div class="flex-container">
    <div id="chats" class="flex-left">
      <div class="flex-left-head">
        <h2>Chats</h2>
      </div>
      <chat v-for="(chat, idx) in chats"
        v-bind:chats="chat"
        v-on:click="toggle(idx)"
        :class="{'chat-activo': idx == activeIdx}"
      ></chat>
    </div>

    <div class="flex-right">
      <div class="flex-right-head">
        <div class="chat-icon-cliente"> </div>
        <h2 id="name-header"></h2>
      </div>

      <div id="conversations" style="overflow-y: scroll;">
        <form id="input_bar" action="javascript:sendMessage()" class="input_bar" autocomplete="off">
          <input id="input" type="text" class="input">
          <button type="button" onclick="sendMessage()" style="width: 10%">Manda</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
