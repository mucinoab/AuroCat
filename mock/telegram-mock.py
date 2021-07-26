import json, requests

# manda mensaje al webhook, simulando ser una nueva actualización de telegram

update = json.loads("""{
  "message": {
    "chat": {
        "id": 966192933,
        "first_name": "Bruno A.",
        "last_name": "Muciño"
    },
    "date": 1627236162,
    "text": "hola"
    }}""")

url = 'http://localhost:8000/telegram-update'
x = requests.post(url, data = json.dumps(update))

print(x.status_code)
print(x.content)
