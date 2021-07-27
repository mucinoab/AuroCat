# Crean un webhook a un bot de telegram

# Este webhook lo apunta a tu instancia local del proyecto usando ngrok, de
# esta manera puedes probar los cambios que realices.

# Se necesitan dos cosas:

# - ngrok corriendo y apuntando al mismo puerto que el servidor de laravel
#   esto se hace con el comando "ngrok http 8000"

# - La variable de entrono, "TELEGRAM_TOKEN" con el token de tu bot de pruebas,
#   si no cuentas con uno lo puedes conseguir aquí https://t.me/BotFather

import requests, json, os

req = requests.get("http://127.0.0.1:4040/api/tunnels")
token = os.environ.get("TELEGRAM_TOKEN")

if token is None:
    print("Error: Variable de entorno 'TELEGRAM_TOKEN' no encontrada.")
    exit(1)

if req.status_code != 200:
    print("Error: Verifica que ngrok este corriendo.")
    exit(1)

ngrok_url = json.loads(req.content)["tunnels"][0]["public_url"]
ngrok_url = ngrok_url.replace("http://", "https://")

webhook_url = f"https://api.telegram.org/bot{token}/setwebhook?url={ngrok_url}/telegram-update"

req = requests.get(webhook_url)

if req.status_code == 200:
    print(f"Listo, visita el proyecto aquí: {ngrok_url}")
else:
    print(f"Error: Verifica los datos.\nStatus Code {req.content}")

