## Base de datos AuroCat


Diagrama de clases UML

<p align="center"><a href="https://lucid.app/lucidchart/invitations/accept/inv_5f529e6b-300e-4a0a-b139-f4d132b17f81?viewport_loc=-285%2C58%2C1789%2C924%2C0_0" target="_blank"><img src="../public/images/db/UML.svg" width="500"></a></p>


Catálogo de querys

<br/>

* **STATE:** Representa el estado del juego(bot,agente,finalizado)
    * 0 => bot
    * 1 => agente
    * 2 => finalizado
</br>
* **WINNER:** Representa el ganador
    * 0 => bot/agente
    * 1 => telegram_user
</br>
* **OPONENT:** Representa al contrincante con el que se esta jugando
    * 0 => bot
    * 1 => agente
</br>
* **TRANSMITTER:** Representa al emisor del mensaje
    * 0 => bot/agente
    * 1 => telegram_user

## Consultas de un juego entre un usuario y el bot

<font color="#EF7F1A">Cada vez que el usuario envía la palabra **/start**.</br></font>
Consultamos en nuestra tabla telegram_users ¿El usuario está registrado en nuestra tabla telegram_users?

> `1` SELECT id FROM telegram_users WHERE id = TELEGRAM_USER_ID;

 <font color="green"> Si el usuario no está registrado </font>

Creamos un registro del usuario en telegram_users:

> `2` INSERTO INTO telegram_users (id,name) VALUES (TELEGRAM_USER_ID,TELEGRAM_USER_NAME);


<font color="#EF7F1A"><br/>Consultamos en nuestra tabla games ¿Cuál es el estado del último juego del usuario?</font>

> `3` SELECT state FROM games WHERE telegram_user_id = TELEGRAM_USER_ID ORDER BY date LIMIT 1;

 <font color="green"> Si no hay registro</font>

Creamos un registro en la tabla games:

> `4` INSERTO INTO games (id,telegram_user_id,state,winner,opponent,date) VALUES (TELEGRAM_USER_ID,0,null,false,funcionDate); 

Creamos un registro con el mensaje que envio el usuario(*/start*): 

> `5` INSERT INTO messages (game_id,chat_id,update_id,message_transmitter,date) VALUES (GAME_ID,CHAT_ID,UPDATE_ID,MESSAGE,DATE,TRANSMITTER);

<font color="green">Si el estado es diferente de 2(finalizado)</font>

Aplicamos la query `5` para registrar el mensaje.

<font color="green">Si el estado es igual a 2(finalizado)</font>

Aplicamos las querys `4` y `5` para crear un nuevo registro en la tabla games y registrar el mensaje en la tabla messages.


<font color="#EF7F1A"><br/>El jugador inicia el bot con la palabra **/nuevo**.</font>

Aplicamos la query `3` para recuperar el estado del último juego del usuario.

<font color="green">Si el  estado es diferente de 2(finalizado).</font>

Cambiamos su estado a finalizado:

> `6` UPDATE games SET  state = 2  WHERE telegram_user_id = TELEGRAM_USER_ID;

Aplicamos la query `4` y `5` para crear un nuevo registro en la tabla games y registrar el mensaje en la tabla messages.



<font color="#EF7F1A"><br/>El jugador realiza una un movimiento en el tablero.</font>

Aplicamos la query `3` para recuperar el estado del último juego del usuario.

Creamos un nuevo registro en la tabla states con los movimientos que realizaron el agente y el bot:

> `7` INSERT INTO states (game_id,board_state,transmitter,turn,date) VALUES (GAME_ID,BOARD_STATE,TRANSMITTER,TURN_DATE); 



<font color="#EF7F1A"><br/>Acaba la partida</font>

Aplicamos query `3` para recuperar el juego y la query `5` para registrar el mensaje del ganador. 

Actualizamos el estado del juego a 2(finalizado) y el ganador correspondiente:

> `8` UPDATE games SET  state = 2 , winner = WINNER WHERE telegram_user_id = TELEGRAM_USER_ID;
