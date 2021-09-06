"use strict";
var channel = pusher.subscribe("nuevo-mensaje");
channel.bind("App\\Events\\Notify", handlePackage);
const instanceId = uuid();
var chatId;
const messageInput = document.getElementById("input");
function handlePackage(pckg) {
    if (pckg.hasOwnProperty("callback")) {
        drawBoard(pckg);
    }
    else if (pckg.instanceId !== instanceId) {
        vm.updateChat(pckg.id, pckg.msg, pckg);
        drawMessage(pckg.msg, pckg.id, pckg.time * 1000, pckg.side);
    }
}
function drawMessage(msj, chatId, timeStamp, side) {
    const time = timeFromUnix(timeStamp);
    const chat = getOrNew(`conversation-${chatId}`, "div", "messages");
    ;
    const message = newElement("div", `message ${side}`, msj);
    message.appendChild(newElement("div", "hora", time));
    chat.appendChild(message);
    message.scrollIntoView(false);
}
async function sendMessage() {
    let msg = messageInput.value.trim();
    if (msg.length != 0) {
        vm.updateChat(chatId, msg);
        drawMessage(msg, String(chatId), unixTime() * 1000, MessageSide.Right);
        postData("/send-telegram", { chat: chatId, msg: msg, senderId: instanceId });
        messageInput.value = "";
    }
}
function drawBoard(state) {
    const data = state.callback.data.split(',');
    const messageId = data[5];
    const gameId = `juego${messageId}`;
    const white = parseInt(data[2], 10);
    const black = parseInt(data[3], 10);
    const game = document.getElementById(gameId);
    const board = createBoard(white, black, gameId, messageId);
    if (game === null) {
        const chat = document.getElementById(`conversation-${state.id}`);
        chat.appendChild(board);
    }
    else {
        game.parentNode.replaceChild(board, game);
    }
    if (data[4].length === 0) {
        state.name = "";
        vm.updateChat(state.id, "Es tu turno.", state);
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
        callback_query: {
            data: data,
            message: {
                chat: { id: chatId },
                message_id: msgId,
                date: unixTime(),
            },
            agent: 1,
        }
    });
}
var MessageSide;
(function (MessageSide) {
    MessageSide["Left"] = "left";
    MessageSide["Right"] = "right";
})(MessageSide || (MessageSide = {}));
