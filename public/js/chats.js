"use strict";
var chats = new Map();
function guardaMensaje(id, msj, time) {
    let msjs = chats.get(id);
    if (msjs === undefined) {
        const chats_cont = document.getElementById("chats");
        chats_cont.appendChild(newElement("div", "chat", id));
        chats.set(id, [{ msj: msj, time: time }]);
    }
    else {
        msjs.push({ msj: msj, time: time });
    }
}
