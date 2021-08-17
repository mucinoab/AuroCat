// @ts-ignore
var channel = pusher.subscribe("nuevo-mensaje");
channel.bind("App\\Events\\Notify", handlePackage);

// HACK: id of the current conversation
var chatId: string;

// Unique identifier of the current instance
const instanceId = uuid();

// Handles all the incoming messages
function handlePackage(data: { id: string, msj: string, time: number, side: string, instanceId: string | undefined }) {
  document.getElementById("hidden_chat").style.setProperty("display", "block");
  chatId = data.id;

  if (data.instanceId !== instanceId) {
    // We only need to draw the message if it is from another instance
    drawMessage(data.msj, data.time * 1000, data.side as MessageSide);
  }
}

// Draw message bubble in chat window
function drawMessage(msj: string, timeStamp: number, side: MessageSide): void {
  const time = timeFromUnix(timeStamp);
  const chat = document.getElementById("chat");
  const message = newElement("div", `message ${side}`, msj);

  message.appendChild(newElement("div", "hora", time));
  chat.appendChild(message);
  message.scrollIntoView(false);
}

// Sends message and update the UI
async function sendMessage() {
  let input = <HTMLInputElement>document.getElementById("input");
  let str = input.value.trim();

  if (str.length != 0) {
    drawMessage(str, new Date().getTime(), MessageSide.Right);
    postData("/send-telegram", { chat: chatId , msj: str, senderId: instanceId });
    input.value = ""; // clears the text input area
  }
}

enum MessageSide {
  Left = "left",
  Right = "right",
}
