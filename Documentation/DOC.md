## Aprendiendo sobre AuroCat
---
<details open>
  <summary><b>Contenido</b></summary>

- [Modelos](#modelos)
- [Métodos en los modelos](#métodos-en-los-modelos)
- [Servicios](#servicios)

</details>

### **Modelos**
---
📱 **TelegramUser:** Este objeto representa un usuario de Telegram.

| Campo |  Tipo | Descripción |
| ------| ----- | ----------- | 
| id    | unsignedBigInteger  | Identificador único para el usuario de Telegram |
| name    | String  | Nombre del usuario de Telegram |


🎮 **Game:** Este objeto representa un juego.

| Campo |  Tipo | Descripción |
| ------| ----- | ----------- | 
| id    | unsignedBigInteger  | Identificador único del juego |
| telegram_user_id    | unsignedBigInteger  | Identificador foráneo del usuario de Telegram |
| state | tinyInteger | Representa el estado del juego(bot, agente, finalizado)|
| winner | tinyInteger |Representa el ganador del juego
| opponent | tinyInteger | Representa al contrincante con el que se está jugando |
| date | unsignedInteger | Fecha en que se creó el juego en hora Unix|

📝 **State:** Este objeto representa el estado de un juego.

| Campo |  Tipo | Descripción |
| ------| ----- | ----------- | 
| id    | unsignedBigInteger  | Identificador único para el estado del juego |
| game_id | unsignedBigInteger  | Identificador foráneo del juego |
| board_state | text  | Representación del estado del juego en formato JSON |
| transmitter | tinyInteger | Representa al jugador que hizo el movimiento |
| turn | tinyInteger | Representa el turno del siguiente jugador |
| date | unsignedInteger | Última fecha de modificación del estado en hora Unix|

💬 **Message:** Este objeto representa un mensaje.

| Campo |  Tipo | Descripción |
| ------| ----- | ----------- | 
| id    | unsignedBigInteger  | Identificador único para el mensaje |
| game_id | unsignedBigInteger  | Identificador foráneo del juego |
| chat_id | integer  | Identificador único del usuario de Telegram |
| update_id | unsignedInteger | Identificador único de la actualización del mensaje|
| message | text | Mensaje entrante de tipo: texto, emoji y sticker |
| transmitter | tinyInteger | Representa al emisor del mensaje |
| date | unsignedInteger | Fecha del envío del estado en hora Unix|


### **Métodos en los modelos**
---
📱 **TelegramUser**

**createTelegramUserIfNotExist**
<br>
Utilice este método para crear un nuevo usuario u obtener el registro del usuario existente. 
- `En caso de éxito:`
    - Devuelve la instancia del nuevo usuario de Telegram
- `De lo contario:`
    - Devuelve la instancia del usuario de Telegram existente


| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `id` | unsignedBigInteger | Sí | Identificador único del usuario de Telegram |
| `name` | String | Opcional | Nombre del usuario de Telegram. *Default:* Invitado |

🎮 **Game**

**createGame**
<br>
Utilice este método para crear un nuevo juego. 
- `En caso de éxito:`
    - Devuelve una instancia de Game

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `telegram_user_id` | unsignedBigInteger | Sí | Identificador único del usuario de Telegram |
| `date` | unsignedInteger | Sí | Fecha de la creación del juego en hora Unix |
| `opponent` | tinyInteger | Opcional | Contrincante con el que se va a jugar. *Default:* false |

**getLastGame**
<br>
Utilice este método para obtener el último juego del usuario de Telegram que no se encuentre finalizado. 
- `En caso de éxito:`
    - Devuelve una instancia de Game
- `De lo contario:`
    - Null

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `telegram_user` | TelegramUser | Sí | Una instancia de TelegramUser |

**changeGameState**
<br>
Utilice este método para cambiar el estado de un juego. 

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `game` | Game | Sí | Una instancia de Game |
| `state` | tinyInteger | Sí | Estado del juego |

**changeGameStateToFinaled**
<br>
Utilice este método para cambiar el estado de un juego finalizado. 

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `game` | Game | Sí | Una instancia de Game |

**changeGameStateToFinaledWithWinner**
<br>
Utilice este método para establecer al ganador y finalizar el juego. 

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `game` | Game | Sí | Una instancia de Game |
| `winner` | tinyInteger | Sí | Ganador del juego |

🎮 **State**

**createState**
<br>
Utilice este método para crear un nuevo estado de un juego. 
- `En caso de éxito:`
    - Devuelve una instancia de State

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `game_id` | unsignedBigInteger | Sí | Identificador del juego |
| `board_state` | Array | Sí | Arreglo que representa el tablero de un juego |
| `transmitter` | tinyInteger | Sí | Jugador que hizo el movimiento |
| `turn` | tinyInteger | Sí | Turno del siguiente jugado |
| `date` | unsignedInteger | Sí | Fecha de la creación del estado en hora Unix |

**getAState**
<br>
Utilice este método para obtener la instancia de estado de un juego. 
- `En caso de éxito:`
    - Devuelve una instancia de State

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `game_id` | unsignedBigInteger | Sí | Identificador del juego |

**updateState**
<br>
Utilice este método para actualizar el estado de un juego.

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `game_id` | unsignedBigInteger | Sí | Identificador del juego |
| `board_state` | Array | Sí | Arreglo que representa el tablero del juego |
| `transmitter` | tinyInteger | Sí | Jugador que hizo el movimiento |

💬 **Message**

**createState**
<br>
Utilice este método para crear un nuevo mensaje. 
- `En caso de éxito:`
    - Devuelve una instancia de Message

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `game_id` | unsignedBigInteger | Sí | Identificador del juego |
| `chat_id` | unsignedBigInteger  | Sí |Identificador del usuario de Telegram |
| `update_id` | unsignedInteger | Sí | Identificador único de la actualización del mensaje |
| `message` | text | Sí | Mensaje entrante de tipo: texto, emoji y sticker |
| `transmitter` | tinyInteger | Sí | Jugador que hizo el movimiento |
| `date` | unsignedInteger | Sí | Fecha de la creación del mensaje en hora Unix |


### **Servicios**
---
📤 **TelegramService**

Implementación básica de la API de Telegram Bot

**send_msj**
<br>
Utilice este método para enviar mensajes, con teclado opcional.
- `En caso de éxito:`
    - Devuelve el id del mensaje.

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `msj` | String | Sí | Mensaje |
| `chat_id` | String  | Sí |Identificador del usuario de Telegram |
| `keyboard` | Array | Opcional | Arreglo con opciones. *Default:* [] |
| `keyboard_type` | String | Opcional | Tipo del teclado. *Default:* inline_keyboard |

**send_keyboard**
<br>
Utilice este método para enviar mensajes con teclado.
- `En caso de éxito:`
    - Devuelve el id del mensaje.

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `msj` | String | Sí | Mensaje |
| `chat_id` | String  | Sí |Identificador del usuario de Telegram |
| `keyboard` | Array | Sí | Arreglo con opciones|
| `keyboard_type` | String | Opcional | Tipo del teclado. *Default:* inline_keyboard |

**update_keyboard**
<br>
Utilice este método para actualizar el teclado de un mensaje enviado anteriormente.

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `chat_id` | String  | Sí |Identificador del usuario de Telegram |
| `message_id` | String | Sí | Arreglo con opciones|
| `keyboard` | Array | Sí | Arreglo con la representación del tablero del juego |

💭 **PropagateService**

Envío de mensajes mediante Websockets.

**propagate_msj**
<br>
Propaga el mensaje a los agentes en la vista web.

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `msj_data` | Array | Sí | Información del mensaje que se envía al frontend |

Elementos del arreglo **msj_data** del método propagate_msj.


| msj_data | Descripción |
| ---------| ----------- |
| `id`     | Identificador del usuario de Telegram |
| `name`   | Nombre del usuario de Telegram |
| `lastName`| Apellido del usuario de Telegram |
| `msg`    | Mensaje |
| `side`   | Indica el emisor del mensaje |
| `time`   | Hora de envío del mensaje |


🧩 **GatoService**

Maneja los juegos, entrada y salida de mensajes.

**handleGame**
<br>
Maneja todos los estados, entradas y salidas del juego.

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `update` | Array | Sí | Datos referentes a un movimiento en un juego |

Representación del parametro **update** del método handleGame en formato JSON
```php
{
    "update_id": 632610700,
    "callback_query": {
        "id": "7422842764417798785",
        "from": {
            "id": 1728265258,
            "is_bot": false,
            "first_name": "Prueba",
            "language_code": "es"
        },
        "message": {
            "message_id": 688,
            "from": {
                "id": 1932944007,
                "is_bot": true,
                "first_name": "Genabot",
                "username": "Genarolaureanobot"
            },
            "chat": {
                "id": 1728265258,
                "first_name": "Prueba",
                "type": "private"
            },
            "date": 1630565117,
            "edit_date": 1630565128,
            "text": "Marca la casilla.",
            "reply_markup": {
                "inline_keyboard": [
                    [
                        {
                            "text": "X",
                            "callback_data": "X,0,0,9,,688"
                        },
                        {
                            "text": " ",
                            "callback_data": " ,1,0,9,,688"
                        },
                        {
                            "text": " ",
                            "callback_data": " ,2,0,9,,688"
                        }
                    ],
                    [
                        {
                            "text": "X",
                            "callback_data": "X,3,0,9,,688"
                        },
                        {
                            "text": " ",
                            "callback_data": " ,4,0,9,,688"
                        },
                        {
                            "text": " ",
                            "callback_data": " ,5,0,9,,688"
                        }
                    ],
                    [
                        {
                            "text": " ",
                            "callback_data": " ,6,0,9,,688"
                        },
                        {
                            "text": " ",
                            "callback_data": " ,7,0,9,,688"
                        },
                        {
                            "text": " ",
                            "callback_data": " ,8,0,9,,688"
                        }
                    ]
                ]
            }
        },
        "chat_instance": "870282868517388886",
        "data": " ,6,0,9,,688"
    }
}

```

Elementos del objeto data y callback_data
```go
//          symbol, idx, bitmask p1, bitmask p2, player type,    game_id
    $data = $tile,  $i,  $white,     $black,     $practice_game, $game_id;
```
<div align="center">

| Elemento | Descripción |
| ---------| ----------- |
| `$tile`     | Representa el símbolo que ocupa la casilla (X , O). *Default:* " " |
| `$i`   | La posición dentro del tablero(0-9) |
| `$white`| La máscara de bits del jugador 1 |
| `$black`    | La máscara de bits del jugador 2 |
| `$practice_game`   | El tipo de jugador |
| `$game_id`   | El identificador único del juego |

</div>

**handleTelegramUserMessage**
<br>
Maneja todos los mensajes del usuario de Telegram.

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `update` | Array | Sí | Datos referentes a un mensaje de Telegram |


Representación del parametro **update** del método handleTelegramUserMessage en formato JSON
```php
{
    "update_id": 632610682,
    "message": {
        "message_id": 669,
        "from": {
            "id": 1728265258,
            "is_bot": false,
            "first_name": "Prueba",
            "language_code": "es"
        },
        "chat": {
            "id": 1728265258,
            "first_name": "Prueba",
            "type": "private"
        },
        "date": 1630564906,
        "text": "/start",
        "entities": [
            {
                "offset": 0,
                "length": 6,
                "type": "bot_command"
            }
        ]
    }
}
```


**handleAgentMessage**
<br>
Maneja todos los mensajes del Agente.

| Parámetro |  Tipo | Requerido | Descripción |
| --------- | ----- | --------- | ----------- |
| `update` | Array | Sí | Datos referentes a un mensaje del Agente |


Representación del parametro **update** del método handleAgentMessage en formato JSON
```php

```


🗃️ **CommandService**

Maneja todos los registros a la base de datos.


  


