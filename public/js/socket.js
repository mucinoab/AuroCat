"use strict";
var channel = pusher.subscribe("nuevo-mensaje");
channel.bind("App\\Events\\Notify", handlePackage);
var chatId;
const instanceId = uuid();
function handlePackage(data) {
    document.getElementById("hidden_chat").style.setProperty("display", "block");
    chatId = data.id;
    if (data.instanceId !== instanceId) {
        drawMessage(data.msj, data.time * 1000, data.side);
    }
}
function drawMessage(msj, timeStamp, side) {
    const time = new Date(timeStamp).toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
    });
    const chat = document.getElementById("chat");
    const message = newElement("div", `message ${side}`, msj);
    message.appendChild(newElement("div", "hora", time));
    chat.appendChild(message);
    message.scrollIntoView(false);
}
async function sendMessage() {
    let input = document.getElementById("input");
    let str = input.value.trim();
    if (str.length != 0) {
        drawMessage(str, new Date().getTime(), MessageSide.Right);
        postData("/send-telegram", { chat: chatId, msj: str, senderId: instanceId });
        input.value = "";
    }
}
var MessageSide;
(function (MessageSide) {
    MessageSide["Left"] = "left";
    MessageSide["Right"] = "right";
})(MessageSide || (MessageSide = {}));
