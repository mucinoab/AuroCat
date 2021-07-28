<!DOCTYPE html>
<head>
  <title>Chat</title>
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="{{asset('css/normalize.css')}}">
  <script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>
  <script src="/js/utils.js"></script>
</head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
var pusher = new Pusher('{{env("MIX_PUSHER_APP_KEY")}}', {
  cluster: '{{env("PUSHER_APP_CLUSTER")}}',
  encrypted: true
});
</script>
<script src="/js/socket.js"></script>

<body>
  <div class="flex-container">
    <div id="chats" class="flex-left">
      <div class="flex-left-head">
        <h2>Chats</h2>
      </div>
      <div class="chat">
      </div>
    </div>

    <div class="flex-right">
      <div class="flex-right-head">
        <h2>Nombre Cliente</h2>
      </div>
      <p>
        Envía un mensaje <a href="https://t.me/aeolus_help_bot">al bot</a> para iniciar.
      </p>
      <div id="hidden_chat" style="display:none">
        <div id="chat" class="messages">
        </div>
        <input id="input" type="text">
        <button type="button" onclick="mandaMensaje()">Manda</button>
      </div>
    </div>
  </div>

</body>
