"use strict";
var channel = pusher.subscribe("nuevo-mensaje");
channel.bind("App\\Events\\Notify", handlePackage);
var chatId;
const instanceId = uuid();
function handlePackage(pckg) {
    if (pckg.hasOwnProperty("callback")) {
        drawBoard(pckg.callback);
    }
    else if (pckg.instanceId !== instanceId) {
        vm.updateChat(pckg.id);
        drawMessage(pckg.msg, pckg.time * 1000, pckg.side);
    }
}
function drawMessage(msj, timeStamp, side) {
    const time = timeFromUnix(timeStamp);
    const chat = document.getElementById("chat");
    const message = newElement("div", `message ${side}`, msj);
    message.appendChild(newElement("div", "hora", time));
    chat.appendChild(message);
    message.scrollIntoView(false);
}
async function sendMessage() {
    let input = document.getElementById("input");
    let msg = input.value.trim();
    if (msg.length != 0) {
        vm.updateChat(chatId);
        drawMessage(msg, unixTime() * 1000, MessageSide.Right);
        postData("/send-telegram", { chat: chatId, msg: msg, senderId: instanceId });
        input.value = "";
    }
}
function drawBoard(state) {
    const data = state.data.split(',');
    const white = parseInt(data[2], 10);
    const black = parseInt(data[3], 10);
    const gameId = `juego${data[5]}`;
    const game = document.getElementById(gameId);
    const board = createBoard(white, black, gameId, data[5]);
    if (game === null) {
        const chat = document.getElementById("chat");
        drawMessage("Marca la casilla.", unixTime() * 1000, MessageSide.Right);
        chat.appendChild(board);
    }
    else {
        game.parentNode.replaceChild(board, game);
    }
}
function createBoard(white, black, gameId, msgId) {
    const board = newElement("div", "grid");
    board.id = gameId;
    for (let i = 0; i < 9; i += 1) {
        const mask = 1 << i;
        let piece = ' ';
        if ((mask & white) != 0)
            piece = 'O';
        else if ((mask & black) != 0)
            piece = 'X';
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
async function boardMove(msgId, pos, data) {
    document.getElementById(`${msgId}${pos}`).innerHTML = "O";
    postData("/telegram-update", {
        "callback_query": {
            "data": data,
            "message": {
                "chat": { "id": chatId },
                "message_id": msgId,
                "date": unixTime(),
            },
            "agent": 1,
        }
    });
}
var MessageSide;
(function (MessageSide) {
    MessageSide["Left"] = "left";
    MessageSide["Right"] = "right";
})(MessageSide || (MessageSide = {}));
