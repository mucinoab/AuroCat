"use strict";
var channel = pusher.subscribe("nuevo-mensaje");
channel.bind("App\\Events\\Notify", recibePaquete);
var chatId;
function recibePaquete(data) {
    document.getElementById("hidden_chat").style.setProperty("display", "block");
    chatId = data.id;
    nuevoMensaje(data.msj, data.time * 1000, Mensaje.Left);
}
function nuevoMensaje(msj, timeStamp, side) {
    const hora = new Date(timeStamp).toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
    });
    const chat = document.getElementById("chat");
    const mensaje = newElement("div", `message ${side}`, msj);
    mensaje.appendChild(newElement("div", "hora", hora));
    chat.appendChild(mensaje);
    mensaje.scrollIntoView(false);
}
async function mandaMensaje() {
    let input = document.getElementById("input");
    let str = input.value.trim();
    if (str.length != 0) {
        nuevoMensaje(str, new Date().getTime(), Mensaje.Right);
        postData("/send-telegram", { chat: chatId, msj: str });
        input.value = "";
    }
}
var Mensaje;
(function (Mensaje) {
    Mensaje["Left"] = "left";
    Mensaje["Right"] = "right";
})(Mensaje || (Mensaje = {}));
