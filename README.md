<p align="center"><a href="https://aeolus-bot.herokuapp.com/" target="_blank"><img src="./public/images/logotipo.png" width="300"></a></p>

## Sobre AuroCat
---
AuroCat es una aplicación web para gestionar conversaciones de un chatbot de Telegram desarollada en Laravel.

- Uso de la [API de Telegram](https://core.telegram.org).
- Interfaz moderna con [Inertia](https://inertiajs.com/).
- [Pusher](https://pusher.com/) para una experiencia en tiempo real.
- Uso de Jetstream para la autenticación de [Fortify](https://laravel.com/docs/8.x/fortify).
- Uso de [Eloquent](https://laravel.com/docs/8.x/eloquent) para interactuar con la base de datos.

## Aprendiendo sobre AuroCat
---
<details open>
  <summary><b>Contenido</b></summary>

- [Modelos](#modelos)
- [Métodos en los modelos](#métodos-en-los-modelos)
- [Servicios](#servicios)
- [Utilidades](#utilidades)
- [Usa este proyecto](#usa-este-proyecto)
- [Conoce más](#conoce-más)
- [Autores](#autores)

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


## **Usa este proyecto**
---
1. Clona este repositorio con git.
2. Instala dependencias ejecutando `npm install` y `composer install` dentro del directorio que clonó (probablemente `InternAuro`).
3. Compile sus archivos ejecutando `npm run dev`.
4. Inicie el servidor de desarrollo con `php artisan serve`.
5. Abra el sitio de desarrollo yendo a `http://localhost:8000` en su navegador.

## **Conoce más**
---
AuroCat tiene un [documento](https://docs.google.com/document/d/1kcoQ_oWIf-p8IpjZMLlYjXl2TUNSo0JWUsnTUBQbums/edit?usp=sharing) sobre el proyecto en general.

El [catálogo de querys](./database/database.md) muestra el flujo del proyecto que se lleva desde la base de datos.

## **Autores**
---
Todas las personas que aportaron en este proyecto.

<div align="center">
  <a href="https://github.com/mucinoab/InternAuro/graphs/contributors">
    <img src="public/images/colaboradores.png"
      alt="Contributors"
      width="200" />
  </a>
</div>



