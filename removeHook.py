# Delete the webhook and clean all the pending updates
import requests, os

token = os.environ.get("TELEGRAM_TOKEN")

if token is None:
    print("Error: Variable de entorno 'TELEGRAM_TOKEN' no encontrada.")
    exit(1)

webhook_url = f"https://api.telegram.org/bot{token}/deleteWebhook?drop_pending_updates=true"

req = requests.get(webhook_url)

if req.status_code == 200:
    print(f"Listo.")
else:
    print(f"Error :(")

