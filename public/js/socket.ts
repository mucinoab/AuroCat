// conexión con back end
// @ts-ignore
var channel = pusher.subscribe("nuevo-mensaje");
channel.bind("App\\Events\\Notify", recibePaquete);

// id de conversación actual
var chatId: string;

// Maneja los mensajes entrantes
function recibePaquete(data: { id: string, msj: string, time: number }) {
  document.getElementById("hidden_chat").style.setProperty("display", "block");
  chatId = data.id;
  nuevoMensaje(data.msj, data.time * 1000, Mensaje.Left);
}

// Crea y añade a conversación nueva burbuja de mensaje
function nuevoMensaje(msj: string, timeStamp: number, side: Mensaje) {
  const hora  = new Date(timeStamp).toLocaleTimeString([], {
    hour: "2-digit",
    minute:"2-digit",
    hour12: false,
  });

  const chat = document.getElementById("chat");
  const mensaje = newElement("div", `message ${side}`, msj);

  mensaje.appendChild(newElement("div", "hora", hora));
  chat.appendChild(mensaje);
  mensaje.scrollIntoView(false);
}

// Manda mensaje y añade a UI
async function mandaMensaje() {
  let input = <HTMLInputElement>document.getElementById("input");
  let str = input.value.trim();

  if (str.length != 0) {
    nuevoMensaje(str, new Date().getTime(), Mensaje.Right);
    postData("/send-telegram", {chat: chatId , msj: str});
    input.value = "";
  }
}

// A qué lado de la conversación pertenece un mensaje.
enum Mensaje {
  Left = "left",
  Right = "right",
}
