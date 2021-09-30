import json, requests
# manda mensaje al webhook, simulando ser una nueva actualización de telegram
update = json.loads(r'''{
  "update_id": 632613456,
    "message": {
        "message_id": 4874,
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
        "date": 1632975620,
        "text": "Que onda"
    }
    }''')

url = 'https://wzufswv05i.sharedwithexpose.com/telegram-update'
for _ in range(50):
    x = requests.post(url, data = json.dumps(update))
    print(x.status_code)
