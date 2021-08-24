// @ts-ignore
var channel = pusher.subscribe("nuevo-mensaje");
channel.bind("App\\Events\\Notify", handlePackage);

// Unique identifier of the current instance.
const instanceId = uuid();

// ID of the currently selected chat.
var chatId: number;

// Input element where the messages are typed.
const messageInput = <HTMLInputElement>document.getElementById("input");

// Handles all the incoming messages
function handlePackage(pckg: MsgPackage) {
  if (pckg.hasOwnProperty("callback")) {
    drawBoard(pckg);
  } else if (pckg.instanceId !== instanceId) {
    // We only need to draw the message if it is from another instance

    // @ts-ignore
    vm.updateChat(pckg.id, pckg.msg, pckg);
    drawMessage(pckg.msg, pckg.id, pckg.time * 1000, pckg.side as MessageSide);
  }
}

// Draw message bubble in chat
function drawMessage(msj: string, chatId: string, timeStamp: number, side: MessageSide): void {
  const time = timeFromUnix(timeStamp);
  const chat = getOrNew(`conversation-${chatId}`, "div", "messages");;
  const message = newElement("div", `message ${side}`, msj);

  message.appendChild(newElement("div", "hora", time));
  chat.appendChild(message);
  message.scrollIntoView(false);
}

// Sends message and update the UI
async function sendMessage() {
  let msg = messageInput.value.trim();

  if (msg.length != 0) {
    // @ts-ignore
    vm.updateChat(chatId, msg);

    drawMessage(msg, String(chatId), unixTime() * 1000, MessageSide.Right);
    postData("/send-telegram", { chat: chatId , msg: msg, senderId: instanceId });

    messageInput.value = ""; // clears the text input area.
  }
}

// Draws or updates a Gato board from a given state.
function drawBoard(state: MsgPackage) {
  const data = state.callback.data.split(',');

  const messageId = data[5];
  const gameId = `juego${messageId}`;

  const white = parseInt(data[2], 10);
  const black = parseInt(data[3], 10);

  const game = document.getElementById(gameId);
  const board = createBoard(white, black, gameId, messageId);

  if (game === null) {
    const chat = document.getElementById(`conversation-${state.id}`);
    drawMessage("Marca la casilla.", state.id, unixTime() * 1000, MessageSide.Right);
    chat.appendChild(board);
  } else {
    // Updates the board.
    game.parentNode.replaceChild(board, game);
  }
}

// Creates the HTML element that represents the Gato board.
function createBoard(white: number, black: number, gameId: string, msgId: string): HTMLElement {
  const board = newElement("div", "grid");
  board.id = gameId;

  for (let i = 0; i < 9; i += 1) {
    const mask = 1 << i;
    let piece = ' ';

    if ((mask & white) != 0) piece = 'O';
    else if ((mask & black) != 0) piece = 'X';

    const tile = newElement("div", "unselectable");
    tile.appendChild(newElement("span", "", piece));
    tile.id = `${msgId}${i}`;
    tile.onclick = _ => {
      boardMove(msgId, i, `${piece},${i},${white},${black},false,${msgId}`);
    };

    board.appendChild(tile);
  }

  return board;
}

// Sends a board move by sending a callback query that mimics the ones sent by Telegram.
async function boardMove(msgId: string, pos: number, data: string): Promise<void> {
  document.getElementById(`${msgId}${pos}`).innerHTML = "O"; // Updates the UI

  postData("/telegram-update", {
    callback_query : {
      data: data,
      message: {
        chat: { id: chatId },
        message_id: msgId,
        date: unixTime(),
      },
      // Signal the server that is an agent move.
      agent: 1,
    }
  });
}

enum MessageSide {
  Left = "left",
  Right = "right",
}
