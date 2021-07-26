// Maneja el almacenamiento de los chats en memoria
// Los chats se almacenan en un mapa que tiene como llave el id del chat y un
// arreglo de los mensajes como valor.
//
//  id : [msj_1, ..., msj_n]

var chats: Map<string, Array<{msj: string, time: number}>> = new Map();

function guardaMensaje(id: string, msj: string, time: number): void {
  let msjs = chats.get(id);

  if (msjs === undefined) {
    // añade nuevo chat
    const chats_cont = document.getElementById("chats");
    chats_cont.appendChild(newElement("div", "chat", id));
    chats.set(id, [{msj: msj, time: time}]);
  } else {
    msjs.push({msj: msj, time: time});
  }
}
